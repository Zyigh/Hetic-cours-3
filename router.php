<?php

require_once __DIR__ . '/database/connexion.php';

$path = $_SERVER['PATH_INFO'] ?? "/";
$method = $_SERVER['REQUEST_METHOD'];

if ($path === '/login') {
    if ($method === 'GET') {
        require_once __DIR__ . "/views/login-form.php";
        exit;
    } else if ($method === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            echo '<h1>Bad Request</h1>';
            http_response_code(400);
        }

        $hashedPwd = hash('sha512', $password);
        $query = "SELECT 
            `u`.`id` 
        FROM `user` u
        WHERE
            `u`.`name` = :username
        AND
            `u`.`password` = :pwd
        ;";

        $stmt = $pdo->prepare($query);
        $stmt->bindValue('username', $username, PDO::PARAM_STR);
        $stmt->bindValue('pwd', $hashedPwd, PDO::PARAM_STR);

        if (!$stmt->execute()) {
            http_response_code(500);
            die($stmt->errorInfo()[2]);
        }

        if ($result = $stmt->fetch()) {
            header('Location: /');
            exit;
        }

        http_response_code(401);
        echo '<h1>Unauthorized</h1>';
    } else {
        echo '<h1>Method not allowed</h1>';
        http_response_code(405);
        exit;
    }
} else if ($path === '/signin') {
    if ($method === 'GET') {
        require_once __DIR__ . "/views/login-form.php";
        exit;
    } else if ($method === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            echo '<h1>Bad Request</h1>';
            http_response_code(400);
        }

        $hashedPwd = hash('sha512', $password);
        $query = "INSERT INTO `user`
            (`name`, `password`)
            VALUES
            (:uname, :pwd)
        ;";

        $stmt = $pdo->prepare($query);
        $stmt->bindValue('pwd', $hashedPwd, \PDO::PARAM_STR);
        $stmt->bindValue('uname', $username, \PDO::PARAM_STR);

        if (!$stmt->execute()) {
            http_response_code(500);
            die($stmt->errorInfo()[2]);
        }

        header('Location: /');
        exit;
    } else {
        echo '<h1>Method not allowed</h1>';
        http_response_code(405);
        exit;
    }
} else if ($path === '/') {
    require_once __DIR__ . '/views/home.php';
    exit;
} else {
    http_response_code(404);
    echo '<h1>Not Found</h1>';
}
