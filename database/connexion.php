<?php

$dbuser = 'root';
$dbpwd = 'root';
$dbhost = '127.0.0.1';
$dbport = 3310;
$dbname = 'twitter';
$dsn = sprintf('mysql:host=%s;port=%d;dbname=%s', $dbhost, $dbport, $dbname);

try {
    $pdo = new \PDO($dsn, $dbuser, $dbpwd);
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

    $pdo->exec('SET NAMES UTF8');
} catch (\PDOException $e) {
    http_response_code(500);
    die($e->getMessage());
}
