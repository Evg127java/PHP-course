<?php

/**
 *  Changes the specified item in DB with passed data
 */

require_once __DIR__ . '/dbConnection.php';
require_once __DIR__ . '/headers.php';
require_once __DIR__ . '/functions.php';

/* Get PDO connection */
$pdo = connection();
$isItemChanged = false;
$data = getInputData();
/* The query of an item updating in the DB */
if (isEntityExist($pdo, $data['id'], 'id', ITEMS)) {
    $isItemChanged = changeItem($pdo, $data);
}

/* Give response to the front if the operation was success or not */
echo json_encode(['ok' => $isItemChanged]);