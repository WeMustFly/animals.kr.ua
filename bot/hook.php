<?php

// Load composer
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';

$bot_api_key  = $config['bot_api_key'];
$bot_username = $config['bot_username'];

// Define all IDs of admin users in this array (leave as empty array if not used)
$admin_users = [
    $config['bot_admin'],
];

// Define all paths for your custom commands in this array (leave as empty array if not used)
$commands_paths = [
    __DIR__ . '/Commands/',
];

// Enter your MySQL database credentials
$mysql_credentials = [
    'host'     => $config['db_host'],
    'user'     => $config['db_user'],
    'password' => $config['db_password'],
    'database' => $config['db_name'],
];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Add commands paths containing your custom commands
    $telegram->addCommandsPaths($commands_paths);

    // Enable admin users
    $telegram->enableAdmins($admin_users);

    // Enable MySQL
    $telegram->enableMySql($mysql_credentials);

    // Set custom Upload and Download paths
    $telegram->setDownloadPath(__DIR__ . '/../www/images');
    $telegram->setUploadPath(__DIR__ . '/../www/images');

    // Handle telegram webhook request
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    echo $e->getMessage();
}