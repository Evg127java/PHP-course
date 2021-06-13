<?php

require_once __DIR__ . '/headers.php';
require_once __DIR__ . '/constants.php';

/**
 * Gets data from input stream
 *
 * @return array
 */
function getInputData(): array
{
    $input = json_decode(file_get_contents(PHP_INPUT), true);
    return sanitizeData($input);
}

/**
 * Gets options for connections to DB
 *
 * @return array Array of config options
 */
function getDBOptions()
{
    return require __DIR__ . '/' . DB_CONFIG_FILE;
}

/**
 * Creates items table in DB
 *
 * @param PDO $pdo PDO connection instance
 */
function createTodoTable(PDO $pdo)
{
    $query = "CREATE TABLE IF NOT EXISTS items (
        `id` INT(11) NOT NULL AUTO_INCREMENT, 
        `text` VARCHAR(255) NOT NULL, 
        `checked` TINYINT(1) NOT NULL DEFAULT '0',
        `user` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $pdo->exec($query);
}

/**
 * Creates users table in DB
 *
 * @param PDO $pdo PDO connection instance
 */
function createUsersTable(PDO $pdo)
{
    $query = "CREATE TABLE IF NOT EXISTS users (
        `id` INT(11) NOT NULL AUTO_INCREMENT, 
        `login` VARCHAR(255) NOT NULL,
        `password_hash` VARCHAR(255) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
    $pdo->exec($query);
}

/**
 * Gets items only for the specified user
 *
 * @param $pdo    PDO connection instance
 * @param $user 'User' name
 * @return        array
 */
function getItemsByUser(PDO $pdo, string $user): array
{
    $stm = $pdo->prepare('SELECT * from items WHERE user = :user');
    $stm->execute(['user' => $user]);
    return $stm->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Checks if the specified entry (field value) exists in the DB
 *
 * @param $pdo    PDO connection instance
 * @param $value
 * @param $entityTitle
 * @param $entityTable
 * @return bool   true if exists, false otherwise
 */
function isEntityExist(PDO $pdo, string $value, string $entityTitle, string $entityTable): bool
{
    $stm = $pdo->prepare('SELECT * FROM ' . $entityTable . ' WHERE ' . $entityTitle . ' = :value');
    $stm->execute([':value' => $value]);
    return $stm->rowCount() > 0;
}

/**
 * Checks if a user is logged in the current session
 *
 * @return bool
 */
function isUserLoggedIn(): bool
{
    return isset($_SESSION['user']);
}

/**
 * Checks if the specified user is verified
 *
 * @param $pdo       PDO connection instance
 * @param $login user's login
 * @param $password user's password
 * @return bool      true if the user is verified, false otherwise
 */
function isUserVerified(PDO $pdo, string $login, string $password): bool
{
    $stm = $pdo->prepare('SELECT * FROM users WHERE login = :login');
    $stm->execute([':login' => $login]);

    if ($stm->rowCount() > 0) {
        $result = $stm->fetchAll(PDO::FETCH_ASSOC);
        if (password_verify($password, $result[0]['password_hash'])) {
            return true;
        }
    }
    return false;
}

/**
 * Removes illegal symbols from user's input array
 *
 * @param array $data User's data array
 * @return array      Sanitized data array
 */
function sanitizeData(array $data): array
{
    $outputArray = [];
    foreach ($data as $name => $value) {
        $outputArray[$name] = filter_var(trim($value), FILTER_SANITIZE_STRING);
    }
    return $outputArray;
}

/**
 * Adds a new user to users DB table
 *
 * @param $pdo       PDO connection instance
 * @param $login user's login
 * @param $password user's password
 */
function saveUser(PDO $pdo, string $login, string $password): void
{
    $login = filter_var(trim($login), FILTER_SANITIZE_STRING);
    $password = filter_var(trim($password), FILTER_SANITIZE_STRING);
    $stm = $pdo->prepare('INSERT INTO users (login, password_hash) 
                                   VALUES (:login, :password)'
    );
    $stm->execute([':login' => $login, ':password' => password_hash($password, PASSWORD_DEFAULT)]);
}

/**
 * Adds a new item to items DB table
 *
 * @param $pdo    PDO connection instance
 * @param $data 'items' data array
 * @param $user user's name associated with the item
 * @return mixed  Last inserted item's value or 0
 */
function addItem(PDO $pdo, array $data, string $user): int
{
    $stm = $pdo->prepare('INSERT INTO items (text, checked, user) 
                                   VALUES (:text, :checked, :user)'
    );
    $stm->execute([':text' => $data['text'], ':checked' => 0, ':user' => $user]);
    return $pdo->lastInsertId();
}

/**
 *  Deletes th specified item from items DB table
 *
 * @param $pdo    PDO connection instance
 * @param $id item's id to delete
 * @return mixed  deleted item's value or 0
 */
function deleteItem(PDO $pdo, int $id): int
{
    $stm = $pdo->prepare('DELETE  from items WHERE id = :id');
    return $stm->execute([':id' => $id]);
}

/**
 * Changes the specified item in items DB table
 *
 * @param $pdo    PDO connection instance
 * @param $data user's data to change
 * @return bool   True if item was changed, false otherwise
 */
function changeItem(PDO $pdo, array $data)
{
    $stm = $pdo->prepare('UPDATE `items` 
                               SET text = :text, checked = :checked
                               WHERE id = :id'
    );
    $stm->execute(['text' => $data['text'], 'checked' => (int)$data['checked'], 'id' => $data['id']]);
    return $stm->rowCount() > 0;
}