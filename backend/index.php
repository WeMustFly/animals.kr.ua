<?php

require __DIR__ . '/../config.php';

$animals = [];
try {
    $dbh = new PDO('mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'], $config['db_user'], $config['db_password']);
    foreach($dbh->query('SELECT * from animals') as $row) {
        $animals[] = $row;
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

include(__DIR__ . '/../frontend/index.html');
