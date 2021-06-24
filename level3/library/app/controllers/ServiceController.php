<?php

namespace app\controllers;

/**
 * Controls migrations
 *
 * Class ServiceController
 * @package app\controllers
 */
class ServiceController extends Controller
{
    /**
     * Runs migrations via browser request like:
     * http://library/migrations
     */
    public function migration()
    {
        require_once __DIR__ . '/../services/migrations/migration.php';
    }
}