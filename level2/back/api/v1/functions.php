<?php

require_once 'headers.php';
require_once 'constants.php';

function getItemsFromFile()
{
    return json_decode(file_get_contents(__DIR__ . '/' . JSON_DATA_FILE), true);
}

function getInputData()
{
    return json_decode(file_get_contents(PHP_INPUT), true);
}

function getLastID(): int
{
    $lastID = file_get_contents(__DIR__ . '/' . LAST_ID_FILE);
    return  $lastID == false ? 0 : $lastID;
}

