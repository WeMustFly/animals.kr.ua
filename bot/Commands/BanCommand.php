<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\DB;

/**
 * Admin "/ban" command
 *
 * Inactive all animals by username.
 */
class BanCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'ban';

    /**
     * @var string
     */
    protected $description = 'Ban all messages from user';
    /**
     * @var string
     */
    protected $usage = '/ban <username>';

    /**
     * @var string
     */
    protected $version = '1.1.0';

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
        $username = trim($message->getText(true));

        if ($username === '') {
            $text = 'Command usage: ' . $this->getUsage();
        } else if ($this->getTelegram()->isAdmin()) {
            try {
                $dbh = DB::getPdo();
                $upd = $dbh->prepare("UPDATE animals "
                    . "SET status = 1 "
                    . "WHERE author = ?");
                $upd->execute([$username]);

                $count = $upd->rowCount();
                $text = "Было деактивировано {$count} записей о животных.";
            } catch (PDOException $e) {
                $text = "Внутренняя ошибка: " . $e->getMessage() . ".";
            }
        } else {
            $text = "Ошибка: Даннай команда доступна только Администраторам. Вы не Администратор.";
        }

        return Request::sendMessage([
            'chat_id' => $chat_id,
            'text'    => $text,
        ]);
    }
}
