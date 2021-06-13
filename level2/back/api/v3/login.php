<?php

/**
 *  Checks users authorization
 */

session_start();
require_once __DIR__ . '/dbConnection.php';
require_once __DIR__ . '/headers.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/constants.php';

$data = getInputData();

/* Get a PDO connection instance */
$pdo = connection();

/* Get user data from input */
$userName = $data['login'];
$password = $data['pass'];

$isUserVerified = isUserVerified($pdo, $userName, $password);

if ($isUserVerified) {

    /* Set user cookie and session var for a verified user */
    setcookie('sessionID', session_id() . ':' . $userName, time() + COOKIE_TIME, '/');
    $_SESSION['user'] = $userName;

    echo json_encode(['ok' => true]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Wrong login data']);
}
