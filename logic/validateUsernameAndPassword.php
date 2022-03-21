<?php

/**
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
