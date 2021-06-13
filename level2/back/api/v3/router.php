<?php

/* routes associated actions due to the input value */
$allowedActions = ['addItem', 'changeItem', 'deleteItem'];
if (in_array($_GET['action'], $allowedActions)) {
    require_once $_GET['action'] . '.php';
}