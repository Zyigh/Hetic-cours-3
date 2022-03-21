<?php

require_once __DIR__ . '/database/connexion.php';

$path = $_SERVER['PATH_INFO'] ?? "/";
$method = $_SERVER['REQUEST_METHOD'];

ob_start();

if ($path === '/login') {
    if ($method === 'GET') {
        $title = 'Log in';
        require_once __DIR__ . "/views/login-form.php";
    } else if ($method === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            http_response_code(400);
            require_once __DIR__ . '/views/exceptions/400.php';
        } else {
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

            try {
                if (!$stmt->execute()) {
                    throw new \Exception($stmt->errorInfo()[2]);
                }

                if ($result = $stmt->fetch()) {
                    header('Location: /');
                    exit;
                }

                http_response_code(401);
                require_once __DIR__ . '/views/exceptions/401.php';
            } catch (\Exception $e) {
                http_response_code(500);
                $message = $e->getMessage();

                require_once __DIR__ . '/views/exceptions/500.php';
            }
        }
    } else {
        http_response_code(405);
        require_once __DIR__ . '/views/exceptions/405.php';
    }
} else if ($path === '/signin') {
    if ($method === 'GET') {
        $title = 'Sign in';
        require_once __DIR__ . "/views/login-form.php";
    } else if ($method === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            http_response_code(400);
            require_once __DIR__ . '/views/exceptions/400.php';
        } else {
            $hashedPwd = hash('sha512', $password);
            $query = "INSERT INTO `user`
                (`name`, `password`)
                VALUES
                (:uname, :pwd)
            ;";

            $stmt = $pdo->prepare($query);
            $stmt->bindValue('pwd', $hashedPwd, \PDO::PARAM_STR);
            $stmt->bindValue('uname', $username, \PDO::PARAM_STR);

            try {
                if (!$stmt->execute()) {
                    throw new Exception($stmt->errorInfo()[2]);
                }

                header('Location: /');
                exit;
            } catch (\Exception $e) {
                http_response_code(500);
                $message = $e->getMessage();

                require_once __DIR__ . '/views/exceptions/500.php';
            }
        }
    } else {
        http_response_code(405);
        require_once __DIR__ . '/views/exceptions/405.php';
    }
} else if ($path === '/') {
    require_once __DIR__ . '/views/home.php';
} else {
    http_response_code(404);
    require_once __DIR__ . '/views/exceptions/404.php';
}

$content = ob_get_clean();
require_once __DIR__ . '/views/base.php';
