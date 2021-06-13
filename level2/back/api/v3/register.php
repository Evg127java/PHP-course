<?php

/**
 *  Registers a user if it is valid
 */

require_once __DIR__ . '/dbConnection.php';
require_once __DIR__ . '/headers.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/constants.php';

$data = getInputData();

/* Get a PDO connection instance */
$pdo = connection();

/* Get user data from input */
$login = $data['login'];
$password = $data['pass'];

$isLoginExist = isEntityExist($pdo, $login, 'login', USERS);
if ($isLoginExist) {
    http_response_code(400);
    echo json_encode(['error' => 'Specified login is already exist']);
} else {
    saveUser($pdo, $login, $password);
    echo json_encode(['ok' => true]);
}