<?php

/**
 * Deletes a specified item from the DB
 */

require_once __DIR__ . '/dbConnection.php';
require_once __DIR__ . '/headers.php';
require_once __DIR__ . '/functions.php';

$data = getInputData();
$id = $data['id'];

/* Initiate a PDO connection */
$pdo = connection();

if (!isEntityExist($pdo, $id, 'id', ITEMS)) {
    echo json_encode(['error' => 'Wrong id']);
    return;
}

$idDeleteSuccessful = deleteItem($pdo, $id);
if ($idDeleteSuccessful) {
    echo json_encode(['ok' => true]);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Delete failed']);
}