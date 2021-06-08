<?php

/**
 * Deletes an item with specified id from the items file
 */

require_once 'headers.php';
require_once 'constants.php';
require_once __DIR__ . '/functions.php';

$id = getInputData()['id'];
$items = getItemsFromFile()['items'];
$isDeleteSuccess = deleteItem($items, $id);

/* Rearrange items in the array after deleting for correct processing */
$items = array_values($items);
file_put_contents(JSON_DATA_FILE, json_encode(['items' => $items]));

/* Give response to the front if the operation is successful or not */
echo json_encode(["ok" => $isDeleteSuccess]);

/**
 * Deletes an item from the items file
 *
 * @param array $items Array of existed items
 * @param int $id      Item ID to delete
 * @return bool        True if success, false otherwise
 */
function deleteItem(array &$items, int $id): bool
{
    foreach ($items as $key => $item) {
        if ($item['id'] == $id) {
            unset($items[$key]);
            return true;
        }
    }
    return false;
}

