<?php
session_start();
/**
 * Gets all th items from DB
 */

require_once __DIR__ . '/dbConnection.php';
require_once __DIR__ . '/headers.php';
require_once __DIR__ . '/functions.php';
checkUserSession();

/* Initiate a PDO connection */
$pdo = connection();

$items = getItemsByUser($pdo, $_SESSION['user']);
echo json_encode(['items' => $items]);