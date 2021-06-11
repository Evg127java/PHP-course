<?php

/**
 * Makes init PDO connection for a custom user configuration
 * Creates 'items' table in DB
 * @return PDO PDO instance
 */
function connection(): PDO
{
    /* get config data for connection to DB */
    $dbOptions = getDBOptions();

    try {
        $pdo = new PDO($dbOptions['dsn'], $dbOptions['user'], $dbOptions['password']);

        /* Create a table in DB */
        createTodoTable($pdo);

        return $pdo;
    } catch (PDOException $exception) {
        http_response_code(500);
        echo json_encode(['error' => $exception->getMessage()]);
        exit;
    }
}