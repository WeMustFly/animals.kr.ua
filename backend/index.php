<?php

require __DIR__ . '/../config.php';

try {
    $dbh = new PDO('mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'], $config['db_user'], $config['db_password']);
    foreach($dbh->query('SELECT * from animals') as $row) {
        print_r($row);
    }
    $dbh = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$html = file_get_contents(__DIR__ . '/../frontend/index.html');
echo $html;
