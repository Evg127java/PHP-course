<?php

/**
 * Adds to the DB table of items a new one with specified data from API request
 */

require_once __DIR__ . '/dbConnection.php';
require_once __DIR__ . '/headers.php';
require_once __DIR__ . '/functions.php';

/* Get input data */
$data = getInputData();
$id = $data['id'];

/* Initiate a PDO connection */
$pdo = connection();

$isItemDeleted = false;

/* The query of an item deleting from the DB if the item exists */
if (isEntityExist($pdo, $id)) {
    $stm = $pdo->prepare('DELETE  from items WHERE id = :id');
    $stm->execute([':id' => $id]);
    $isItemDeleted = isDBChanged($stm);
}

/* Give response to the front if the operation was success or not */
echo json_encode(['ok' => $isItemDeleted]);