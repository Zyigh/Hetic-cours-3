<?php
// On récupère la connexion à la base de donnée de manière globale
require_once __DIR__ . '/database/connexion.php';

// L'utilisateur est stocké dans la session
// la session est représentée par le tableau $_SESSION
// On stockera l'utilisateur à l'index user, donc il sera accessible à l'index user
$user = $_SESSION['user'] ?? null;
//var_dump($_SESSION);

// Le path correspond à toute la partie de l'url à partir du "/"
// Sans prendre en compte les paramètres
// Pour http://localhost/une-route?param=yolo
// $path vaudra "/une-route"
// pour http://localhost il n'existera pas, donc on lui force la valeur "/"
$path = $_SERVER['PATH_INFO'] ?? "/";
// La méthode HTTP avec laquelle on essaye d'accéder à la ressource
$method = $_SERVER['REQUEST_METHOD'];

// Ouverture d'un buffer où on va écrire tous les outputs
// Chaque "echo" n'apparaitra pas, mais sera écrit dans ce buffer
ob_start();
// On gère dans un premier temps les routes en GET
if ($method === 'GET') {
    require_once __DIR__ . '/logic/homepage.php';
    $title = '';
    $pageExists = false;

    // Le switch va gérer toutes nos routes que l'on définit (en GET)
    // On va définir la page à require pour l'écrire dans le buffer
    switch ($path) {
        case '/login':
            $pageExists = true;
            $title = 'Log in';
            $loginOrSignIn = '/signin';
            require_once __DIR__ . "/views/login-form.php";
            break;
        case '/signin':
            $pageExists = true;
            $title = 'Sign in';
            $loginOrSignIn = '/login';
            require_once __DIR__ . "/views/login-form.php";
            break;
        case '/':
            $pageExists = true;
            if (null === $user) {
                header('Location: /login');
                exit;
            }
            echo getHomepage($pdo);

            break;
        case '/logout':
            // On détruit la session, c'est à dire qu'on vide le tableau $_SESSION
            session_destroy();
            header('Location: /');
            exit;
    }

    // Si rien n'a été définit comme page à afficher, alors la route n'existe pas
    // On montre donc notre page d'erreur 404 => NOT FOUND
    if (!$pageExists) {
        // Pour définir le status code de la réponse
        // voir https://twitter.com/stevelosh/status/372740571749572610?lang=fr
        // ou https://http.cat/
        // ou https://twitter.com/codedoesmeme/status/1111676582802976770
        http_response_code(404);
        $fileToRequire = "/views/exceptions/404.php";
    }
// On gère maintenant les routes en POST
} else if ($method === 'POST') {
    // Les fichiers dont on va avoir besoin
    require_once __DIR__ . '/logic/validateUsernameAndPassword.php';
    require_once __DIR__ . '/database/login.php';
    require_once __DIR__ . '/database/signIn.php';
    require_once __DIR__ . '/database/tweet.php';

    // Notre code va lever des exceptions qui nous donnera des infos sur le code HTTP à renvoyer
    try {
        switch ($path) {
            case '/tweet':
                addTweet($_POST['tweet'], $pdo);
                header('Location: /');
                exit;
            case '/login':
                // On valide la requête avec la fonction validateUsernameAndPassword
                // Elle renvoie un tableau contenant le username et le password qu'on récupère avec la fonction list
                list($username, $password) = validateUsernameAndPassword($_POST);
                // On va chercher l'utilisateur
                // La méthode peut lever des Exceptions dans différents cas
                $_SESSION['user'] = findUser($username, $password, $pdo);
                header('Location: /');
                exit;
            case '/signin':
                list($username, $password) = validateUsernameAndPassword($_POST);
                // On essaye crée l'utilisateur
                // La méthode peut lever des Exceptions dans différents cas
                createUser($username, $password, $pdo);

                header('Location: /');
                exit;
            default:
                // Par défaut, la route n'existe pas, on lève une Exception 404
                throw new \Exception('', 404);
        }
    // Toutes les exceptions levées dans le try seront récupérées dans le catch, dans la variable $e
    } catch (Exception $e) {
        // Le code HTTP renvoyé sera utilisé comme code de réponse
        http_response_code($e->getCode());
        // On charge la vue qui correspond à l'erreur
        require_once __DIR__ . sprintf('/views/exceptions/%d.php', $e->getCode());
    }
// Si on est sur une autre méthode, les routes ne sont pas implémentées
// On renvoie donc par défaut une erreur 405
} else {
    http_response_code(405);
    require_once __DIR__ . '/views/exceptions/405.php';
}

// On sotcke dans une variable $content le contenu du buffer où on a écrit le contenu de notre page
// Puis on vide le buffer
$content = ob_get_clean();
// On n'a plus qu'à afficher le contenu dans notre fichier de base
require_once __DIR__ . '/views/base.php';
