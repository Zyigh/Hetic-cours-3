<?php

require_once __DIR__ . '/database/connexion.php';

$path = $_SERVER['PATH_INFO'] ?? "/";
$method = $_SERVER['REQUEST_METHOD'];

ob_start();
if ($method === 'GET') {
    $title = '';
    $fileToRequire = null;
    switch ($path) {
        case '/login':
            $title = 'Log in';
            $fileToRequire = "/views/login-form.php";
            break;
        case '/signin':
            $title = 'Sign in';
            $fileToRequire = "/views/login-form.php";
            break;
        case '/':
            $fileToRequire = '/views/home.php';
            break;
    }

    if (null === $fileToRequire) {
        http_response_code(404);
        $fileToRequire = "/views/exceptions/404.php";
    }

    require_once __DIR__ . $fileToRequire;
} else if ($method === 'POST') {
    require_once __DIR__ . '/logic/validateUsernameAndPassword.php';
    require_once __DIR__ . '/database/login.php';
    require_once __DIR__ . '/database/signIn.php';

    try {
        switch ($path) {
            case '/login':
                list($username, $password) = validateUsernameAndPassword($_POST);
                findUser($username, $password, $pdo);
                header('Location: /');
                exit;
            case '/signin':
                [$username, $password] = validateUsernameAndPassword($_POST);
                createUser($username, $password, $pdo);

                header('Location: /');
                exit;
            default:
                throw new \Exception('', 404);
        }
    } catch (Exception $e) {
        http_response_code($e->getCode());
        require_once __DIR__ . sprintf('/views/exceptions/%d.php', $e->getCode());
    }
} else {
    http_response_code(405);
    require_once __DIR__ . '/views/exceptions/405.php';
}

$content = ob_get_clean();
require_once __DIR__ . '/views/base.php';
