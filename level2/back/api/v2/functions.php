<?php

require_once __DIR__ . '/headers.php';
require_once __DIR__ . '/constants.php';

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
 * Checks if an item with specified id exists in DB
 *
 * @param PDO $pdo PDO object for connection to DB
 * @param int $id ID to check
 * @return bool     True if exists, false otherwise
 */
function isItemExist(PDO $pdo, int $id): bool
{
    $stm = $pdo->prepare('SELECT * FROM items WHERE id = :id');
    $stm->execute([':id' => $id]);
    return $stm->rowCount() > 0;
}

/**
 * Checks if DB was affected by the query
 *
 * @param PDOStatement $stm PDO Statement to check
 * @return bool             True if DB was changed, false otherwise
 */
function isDBChanged(PDOStatement $stm): bool
{
    return $stm->rowCount() > 0;
}

/**
 * Gets options for connections to DB
 *
 * @return array Array of config options
 */
function getDBOptions(): array
{
    return require __DIR__ . '/' . DB_CONFIG_FILE;
}

/**
 * Creates table in DB
 *
 * @param PDO $pdo PDO connection instance
 */
function createTodoTable(PDO $pdo): void
{
    $query = "CREATE TABLE IF NOT EXISTS items (
        `id` INT(11) NOT NULL AUTO_INCREMENT, 
        `text` VARCHAR(255) NOT NULL, 
        `checked` TINYINT(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $pdo->exec($query);
}