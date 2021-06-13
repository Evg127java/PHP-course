<?php
session_start();
/**
 * Adds to the file of items a new one with specified data from API request
 */

require_once __DIR__ . '/dbConnection.php';
require_once __DIR__ . '/headers.php';
require_once __DIR__ . '/functions.php';

/* Operate of adding an item if only request method is POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* Get PDO connection */
    $pdo = connection();

    $data = getInputData();

    /* Get user login from session */
    $user = $_SESSION['user'];

    $id = addItem($pdo, $data, $user);

    /* Give response to the front with the added item id value */
    echo json_encode(['id' => $id]);
}