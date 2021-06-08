<?php

/**
 * Changes an item from the file of items with specified data from API request
 */

require_once 'headers.php';
require_once 'constants.php';
require_once __DIR__ . '/functions.php';

$inputData = getInputData();
/* Get the item id to change */
$itemIDoChange = $inputData['id'];
/* Make a new item for changing with existed */
$newItem = createItem($inputData);
$items = getItemsFromFile();

/* Save the items array with the new one to the file if changing operation is successful */
$isChangeSuccess = changeItem($items, $itemIDoChange, $newItem);
file_put_contents(JSON_DATA_FILE, json_encode($items));

/* Give response to the front if the operation is successful or not */
echo json_encode(['ok' => $isChangeSuccess]);

/**
 * Creates a new item with data from input stream
 *
 * @param array $data Input data array
 * @return array      Array of the new item data
 */
function createItem(array $data): array
{
    return [
        'id' => $data['id'],
        'text' => $data['text'],
        'checked' => $data['checked'],
    ];
}

/**
 * Changes the existed item by the created one with the specified id
 *
 * @param array $items    Items array
 * @param int $id         ID of the item to change
 * @param array $newItem  A new item
 * @return bool           True if success, false otherwise
 */
function changeItem(array &$items, int $id, array $newItem): bool
{
    foreach ($items['items'] as $key => $item) {
        foreach ($item as $value) {
            if ($value === $id) {
                $items['items'][$key] = $newItem;
                return true;
            }
        }
    }
    return false;
}
