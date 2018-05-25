<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\TelegramBot\Commands\SystemCommands;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\DB;
/**
 * Generic message command
 *
 * Gets executed when any type of message is sent.
 */
class GenericmessageCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'genericmessage';

    /**
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Command execute method if MySQL is required but not available
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function executeNoDb()
    {
        // Do nothing
        return Request::emptyResponse();
    }

    private function addAnimal($name, $author, $picture)
    {
        if ($name === '') {
            $text = 'Error: name is empty.';
        } else {
            try {
                $sql = "INSERT INTO animals "
                    . "(name, author, picture, status) "
                    . "VALUES ('{$name}', '{$author}', '{$picture}', 0)";

                $dbh = DB::getPdo();
                $dbh->query($sql);
                $animalId = $dbh->lastInsertId();

                $text = "Animal #{$animalId} has been added.";
            } catch (PDOException $e) {
                $text = "Error: " . $e->getMessage();
            }
        }

        return $text;
    }

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $type = $message->getType();
        $chat = $message->getChat();

        if ($type === 'photo') {
            $photo = $message->getPhoto();
            $caption = $message->getCaption();
            $originalPhoto = $photo[2];
            $file_id = $originalPhoto->getFileId();

            $data = [
                'chat_id' => $chat->getId(),
            ];

            $file = Request::getFile(['file_id' => $file_id]);
            if ($file->isOk() && Request::downloadFile($file->getResult())) {
                $author = $chat->getUsername(); 
                $picture = $file->getResult()->getFilePath();
                $data['text'] = $this->addAnimal($caption, $author, $picture);
            } else {
                $data['text'] = 'Failed to download.';
            }

            return Request::sendMessage($data);        
        } else {
            //If a conversation is busy, execute the conversation command after handling the message
            $conversation = new Conversation(
                $message->getFrom()->getId(),
                $chat->getId()
            );

            //Fetch conversation command if it exists and execute it
            if ($conversation->exists() && ($command = $conversation->getCommand())) {
                return $this->telegram->executeCommand($command);
            }
        }

        return Request::emptyResponse();
    }
}