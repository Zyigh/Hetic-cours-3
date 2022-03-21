<?php

/**
 * Récupère un username et un password depuis un tableau
 * Si le tableau ne contient pas l'un ou l'autre, lève une exception
 * Sinon, le password est hashé en [SHA512](https://www.dcode.fr/hash-sha512)
 * Renvoie un tableau avec le username et le mot de passe hashé
 *
 * @param array $data
 * @return array
 * @throws \Exception
 */
function validateUsernameAndPassword(array $data): array {
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    if ($username === '' || $password === '') {
        throw new \Exception('', 400);
    }

    $hashedPwd = hash('sha512', $password);

    return [
        $username,
        $hashedPwd,
    ];
}
