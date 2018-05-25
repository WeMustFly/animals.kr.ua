<?php

$botDir = __DIR__ . '/../bot'; 
$action = $_REQUEST['action'] ?? false;

if ($action === 'set') {
    include_once($botDir . '/set.php');
} else if ($action === 'unset') {
    include_once($botDir . '/unset.php');
} else if ($action === 'hook') {
    include_once($botDir . '/hook.php');
} else {
    echo "Hello! I'm a bot.";
}
