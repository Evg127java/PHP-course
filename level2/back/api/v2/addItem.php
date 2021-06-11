<?php

/**
 * Adds to the DB table of items a new one with specified data from API request
 */

require_once __DIR__ . '/dbConnection.php';
require_once __DIR__ . '/headers.php';
require_once __DIR__ . '/functions.php';

/* Operate of adding an item if only request method is POST */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /* Get PDO connection */
    $pdo = connection();

    /* get input data */
    $data = getInputData();

    /* The query of an item inserting to the DB */
    $stm = $pdo->prepare('INSERT INTO items (text, checked) 
                                   VALUES (:text, :checked)'
    );
    $stm->execute([':text' => $data['text'], ':checked' => 0]);

    /* Get the last id from PDO */
    $id = $pdo->lastInsertId();

    /* Give response to the front with the added item id value */
    echo json_encode(['id' => $id]);
}