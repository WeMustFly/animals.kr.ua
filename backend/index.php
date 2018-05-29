<?php

require __DIR__ . '/../config.php';

$id = intval($_REQUEST['id']);
$animals = [];
try {
    $dbh = new PDO('mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'], $config['db_user'], $config['db_password']);


    if ($id) {
        $sql = 'SELECT * from animals WHERE id = ' . $id;
        foreach($dbh->query($sql) as $row) {
            $animal = $row;
        }

        if (empty($animal)) {
            header("Location: /");
            die();
        }
    } else {
        $sql = 'SELECT * from animals WHERE status = 0';
        foreach($dbh->query($sql) as $row) {
            $animals[] = $row;
        }
    }

    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

include(__DIR__ . '/../www/dist/index.html');
