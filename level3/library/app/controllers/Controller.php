<?php

namespace app\controllers;

use app\views\View;

/**
 * The base app's controller
 *
 * Class Controller
 * @package app\controllers
 */
class Controller
{
    /* View object which is responsible for templates representing */
    protected View $view;

    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../templates');
    }
}