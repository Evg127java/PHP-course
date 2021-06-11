<?php

/**
 * Gets all th items from DB
 */

require_once __DIR__ . '/dbConnection.php';
require_once __DIR__ . '/headers.php';
require_once __DIR__ . '/functions.php';

/* Initiate a PDO connection */
$pdo = connection();

/* The query of getting all the items from the DB */
$stm = $pdo->query("SELECT * from items");
$items = $stm->fetchAll(PDO::FETCH_ASSOC);

/* Give items array to the front */
echo json_encode(['items' => $items]);