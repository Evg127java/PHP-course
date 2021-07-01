<?php

use app\services\Db;

/**
 * Main file of migrations.
 * Runs all the init migrations for the project
 */

/* DB connection */
$dbOptions = (require __DIR__ . '/../../core/config.php')['db'];
$dbConnection = Db::getInstance();

/* Try to get new migrations */
$files = getMigrationFiles($dbConnection);

/* Check if there are new migrations */
if (empty($files)) {
    echo 'Database is actual.';
} else {
    echo 'Migration started<br><br>';
    /* Set locale for correct displaying russian in DB */
    $locale = 'en_US.utf-8';
    setlocale(LC_ALL, $locale);
    putenv('LC_ALL=' . $locale);
    foreach ($files as $file) {
        migrate($dbConnection, $file);
        echo basename($file) . '<br />';
    }
    echo '<br>Migration finished';
}

/**
 * Gets migration files if the are
 *
 * @param $dbConnection  DB connection
 * @return array|null    Files of migration to run
 */
function getMigrationFiles(DB $dbConnection): ?array
{
    /* Get folder to migrations files */
    $sqlFolder = str_replace('\\', '/', realpath(dirname(__FILE__)) . '/');

    /* Get all migrations files */
    $allFiles = glob($sqlFolder . '*.sql');

    /* Check if versions table exists in DB. If it does, it means the DB is not empty */
    $query = sprintf("show tables from `%s` like '%s'", 'library', 'versions');

    /* If the query returns any value except of 0 that means that it is not the first migration */
    $isFirstMigration = $dbConnection->queryWithResult($query);

    /* Get all the migrations files if isFirstMigration is empty, that means we have no migrations yet */
    if (empty($isFirstMigration)) {
        return $allFiles;
    }

    /*----------------------------- If it is not the first migration -----------------------------------*/

    $versionsFiles = [];
    /* Get all file names from Versions table in DB */
    $query = sprintf('SELECT `name` FROM `%s`', 'versions');
    $tables = $dbConnection->queryWithResult($query);

    foreach ($tables as $entry => $file) {
        array_push($versionsFiles, $sqlFolder . $file->name);
    }

    /* Return only new migrations files */
    return array_diff($allFiles, $versionsFiles);
}

/**
 * Runs a separate migration
 *
 * @param $dbConnection DB connection
 * @param $file         'migration file
 */
function migrate(DB $dbConnection, string $file): void
{
    /* Form the mySQL query command from the outer migration file */
    $command = sprintf('mysql -u %s -p%s -h %s -D %s < %s', 'user', '12_b*2B', 'localhost', 'library', $file);
    shell_exec($command);

    /*------------------ Put a migration entry into the version table in DB --------------------*/

    /* Get a filename without a path */
    $baseName = basename($file);
    $query = sprintf('INSERT INTO `%s` (`name`) VALUES("%s")', 'versions', $baseName);
    $dbConnection->queryWithoutResult($query);
}