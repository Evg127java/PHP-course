<?php

/**
 * Adds to the DB table of items a new one with specified data from API request
 */

require_once __DIR__ . '/dbConnection.php';
require_once __DIR__ . '/headers.php';
require_once __DIR__ . '/functions.php';

/* Get PDO connection */
$pdo = connection();

/* get input data */
$data = getInputData();

$isItemChanged = false;

/* The query of an item updating in the DB */
if (isEntityExist($pdo, $id)) {
    $stm = $pdo->prepare('UPDATE `items` 
                               SET text = :text, checked = :checked
                               WHERE id = :id'
    );
    $stm->execute(['text' => $data['text'], 'checked' => (int)$data['checked'], 'id' => $data['id']]);
    $isItemChanged = isDBChanged($stm);
}

/* Give response to the front if the operation was success or not */
echo json_encode(['ok' => $isItemChanged]);