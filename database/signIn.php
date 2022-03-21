<?php

/**
 * @param string $username
 * @param string $password
 * @throws \Exception
 */
function createUser(string $username, string $password, \PDO $pdo) {
    $query = "INSERT INTO `user`
        (`name`, `password`)
        VALUES
        (:uname, :pwd)
    ;";

    $stmt = $pdo->prepare($query);
    $stmt->bindValue('pwd', $password, \PDO::PARAM_STR);
    $stmt->bindValue('uname', $username, \PDO::PARAM_STR);

    if (!$stmt->execute()) {
        throw new \Exception($stmt->errorInfo()[2], 500);
    }
}
