<?php

// Load composer
require_once __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config.php';

$bot_api_key  = $config['bot_api_key'];
$bot_username = $config['bot_username'];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Delete webhook
    $result = $telegram->deleteWebhook();
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}