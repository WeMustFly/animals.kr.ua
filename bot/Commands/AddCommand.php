<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\DB;

/**
 * User "/add" command
 *
 * Add an animal into our database.
 */
class AddCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'add';

    /**
     * @var string
     */
    protected $description = 'Add an animal';
    /**
     * @var string
     */
    protected $usage = '/add <name>';

    /**
     * @var string
     */
    protected $version = '0.1.0';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();
        $chat_username = $message->getChat()->getUsername();
        $name = trim($message->getText(true));

        if ($name === '') {
            $text = 'Command usage: ' . $this->getUsage();
        } else {
            try {
                $sql = "INSERT INTO animals "
                    . "(name, author, picture, status) "
                    . "VALUES ('{$name}', '{$chat_username}', '', 0)";

                $dbh = DB::getPdo();
                $dbh->query($sql);
                $animal_id = $dbh->lastInsertId();

                $text = "Animal #{$animal_id} has been added.";
            } catch (PDOException $e) {
                $text = "Error!: " . $e->getMessage();
            }
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
