<?php

/**
 * @param string $content
 * @param PDO $pdo
 * @throws \Exception
 */
function addTweet(string $content, \PDO $pdo) {
    $query = "INSERT INTO `tweet`
                    (content, user_id) 
                    VALUES 
                    (:content, :uid)
                ;";

    $stmt = $pdo->prepare($query);
    $stmt->bindValue('content', $content, \PDO::PARAM_STR);
    $stmt->bindValue('uid', $_SESSION['user']['id'], \PDO::PARAM_INT);

    if (!$stmt->execute()) {
        throw new \Exception($pdo->errorInfo()[2], 500);
    }
}

function getTweets(\PDO $pdo): array {
    $query = "SELECT 
                `t`.`content`,
                `u`.`name` as author
              FROM `tweet` t, `user` u
              WHERE
                `t`.`user_id` = `u`.`id`
            ;";

    $stmt = $pdo->prepare($query);
    if (!$stmt->execute()) {
        throw new \Exception($pdo->errorInfo()[2], 500);
    }

    return $stmt->fetchAll();
}
