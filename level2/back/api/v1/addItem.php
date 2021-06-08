<?php

/**
 * Adds to the file of items a new one with specified data from API request
 */

require_once 'headers.php';
require_once 'constants.php';
require_once __DIR__ . '/functions.php';

/* Operate of adding an item if only request method is POST */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /* Make a new item to add */
    $item = createItem(getInputData());
    $id = $item['id'];

    /* Get all the items */
    $items = getItemsFromFile();
    /* Add the created item to the array of all the items */
    $items ['items'][] = $item;

    /* Update json file with the new item */
    file_put_contents(JSON_DATA_FILE, json_encode($items));
    /* Update the last ID with the last added item id value */
    file_put_contents(LAST_ID_FILE, $id);

    /* Give response to the front with the added item id value */
    echo json_encode(['id' => $id]);
}

/**
 * Creates a new item from input data
 *
 * @param array $data Array of input data
 * @return array      Array of created item data
 */
function createItem(array $data): array
{
    return [
        'id' => getLastID() + 1,
        'text' => $data["text"],
        'checked' => DEFAULT_CHECKED_VALUE,
    ];
}