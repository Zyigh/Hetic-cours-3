<?php

/**
 * Va chercher un user par rapport à un username et un password dans une DB accessible via $pdo
 * Lève une Exception si la requête ne fonctionne pas, ou si aucun utilisateur n'a été trouvé
 * Renvoie un tableau contenant l'id, le name et le pwd du user
 *
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
        ], $result);
    }

    throw new \Exception('', 401);
}
