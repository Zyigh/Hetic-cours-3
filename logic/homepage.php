<?php

require_once __DIR__ . '/../database/tweet.php';

function getHomepage(\PDO $pdo): string {
    ob_start();
    require_once __DIR__ . '/../views/tweets/home.php';
    $tweets = getTweets($pdo);
    require_once __DIR__ . '/../views/tweets/list.php';

    return ob_get_clean();
}
