<?php

/**
 * Makes user logged out
 */

session_start();
require_once __DIR__ . '/headers.php';
require_once __DIR__ . '/functions.php';

/* reset cookie */
setcookie('sessionID', '', 0, '/');

/* Unset session var and destroy */
unset($_SESSION['user']);
session_destroy();

$isUserLoggedIn = !isUserLoggedIn();
echo json_encode(["ok" => $isUserLoggedIn]);