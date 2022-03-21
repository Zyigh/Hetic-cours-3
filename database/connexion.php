<?php

$dbuser = 'root';
$dbpwd = 'root';
$dbhost = '127.0.0.1';
$dbport = 3310;
$dbname = 'twitter';
$dsn = sprintf('mysql:host=%s;port=%d;dbname=%s', $dbhost, $dbport, $dbname);

try {
    $pdo = new \PDO($dsn, $dbuser, $dbpwd);
    // Transforme toutes les erreurs de PDO en Exception
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    // Utilise par défaut FETCH_ASSOC
    $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    // Définit que les chars doivent être encodés en UTF8
    $pdo->exec('SET NAMES UTF8');
} catch (\PDOException $e) {
    http_response_code(500);
    die($e->getMessage());
}
