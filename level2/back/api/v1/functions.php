<?php

require_once 'headers.php';
require_once 'constants.php';

/**
 * Gets decoded items from the json file
 *
 * @return mixed
 */
function getItemsFromFile()
{
    return json_decode(file_get_contents(__DIR__ . '/' . JSON_DATA_FILE), true);
}

/**
 * Gets data from input stream
 *
 * @return mixed
 */
function getInputData()
{
    return json_decode(file_get_contents(PHP_INPUT), true);
}

/**
 * Gets the last assigned ID of an item
 *
 * @return int ID value
 */
function getLastID(): int
{
    $lastID = file_get_contents(__DIR__ . '/' . LAST_ID_FILE);
    return  $lastID == false ? 0 : $lastID;
}

