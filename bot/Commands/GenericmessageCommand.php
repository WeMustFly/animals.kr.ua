<?php

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
        try {
            $sql = "INSERT INTO animals "
                . "(name, author, picture, status) "
                . "VALUES ('{$name}', '{$author}', '{$picture}', 0)";

            $dbh = DB::getPdo();
            $dbh->query($sql);
            $animalId = $dbh->lastInsertId();

            $text = "Животное №{$animalId} успешно добавлено на сайт!"
                . "\n" . "https://animals.kr.ua/{$animalId}";
        } catch (PDOException $e) {
            $text = "Внутренняя ошибка: " . $e->getMessage() . ".";
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
            $caption = trim($message->getCaption());
            $author = $chat->getUsername();

            if (empty($caption)) {
                $text = "Ошибка: Вы не указали подпись для фото животного :-(";
            } else if (empty($author)) {
                $text = "Ошибка: Вы не указали имя пользователя :-(";
            } else {
                $originalPhoto = $photo[2];
                $file_id = $originalPhoto->getFileId();

                $file = Request::getFile(['file_id' => $file_id]);
                if ($file->isOk() && Request::downloadFile($file->getResult())) {
                    $picture = $file->getResult()->getFilePath();
                    $text = $this->addAnimal($caption, $author, $picture);
                } else {
                    $text = 'Ошика во время загрузки.';
                }
            }

            return Request::sendMessage([
                'chat_id' => $chat->getId(),
                'text' => $text
            ]);
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
