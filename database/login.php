<?php

/**
 * @param string $username
 * @param string $password
 * @return array
 * @throws \Exception
 */
function findUser(string $username, string $password, \PDO $pdo): array {
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
    $stmt->bindValue('pwd', $password, PDO::PARAM_STR);

    if (!$stmt->execute()) {
        throw new \Exception($pdo->errorInfo()[2], 500);
    }

    if ($result = $stmt->fetch()) {
        return array_merge([
            'username' => $username,
            'password' => $password
        ], $result);
    }

    throw new \Exception('', 401);
}
