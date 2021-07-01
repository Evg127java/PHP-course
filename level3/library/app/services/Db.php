<?php

namespace app\services;

use app\Exceptions\DbException;
use PDO;
use PDOException;

/**
 * Makes a single instance of PDO connection to the DB
 *
 * Class Db
 * @package app\services
 */
class Db
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var
     */
    private static $db;

    /**
     * Implements a singleton pattern of PDO connection
     *
     * @return Db
     */
    public static function getInstance(): self
    {
        /* Gets PDO connection if it exist or make a new one */
        if (self::$db === null) {
            self::$db = new self();
        }

        return self::$db;
    }

    /**
     * Db constructor.
     * @throws DbException
     */
    private function __construct()
    {
        /* Get PDO connection config */
        $dbOptions = (require __DIR__ . '/../core/config.php')['db'];

        try {
            $this->pdo = new PDO(
                'mysql:host=' . $dbOptions['host'] . '; dbname=' . $dbOptions['dbname'] . '; charset=utf8',
                $dbOptions['user'],
                $dbOptions['password']
            );

            /* Set optional PDO connection's attributes */
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        } catch (PDOException $exception) {
            throw new DbException('DB connecting error<br>' . $exception->getMessage());
        }
    }

    /**
     * Makes query that doesn't expect data retrieving
     *
     * @param string $sql  SQL query string
     * @param array $param Parameters for the query
     */
    public function queryWithoutResult(string $sql, array $param = [])
    {
        $sth = $this->pdo->prepare($sql);
        $sth->execute($param);
    }

    /**
     * Makes query which expects some data as a response
     *
     * @param string $sql        SQL query string
     * @param array $param       Parameters for the query
     * @param string $className  Response unit class name
     * @return array             Response as an array
     */
    public function queryWithResult(string $sql, array $param = [], string $className = 'stdClass'): array
    {
        $sth = $this->pdo->prepare($sql);
        $sth->execute($param);
        return $sth->fetchAll(PDO::FETCH_CLASS, $className);
    }

    /**
     * Gets the last ID added to th DB
     *
     * @return int  Id value
     */
    public function getLastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }
}