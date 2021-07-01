<?php

namespace app\core;

use app\exceptions\NotFoundException;

const ROUTES = '/../core/routes.php';
/**
 * Class Router routes all requests to the library app
 * @package app\core
 */
class Router
{
    public function run()
    {
        /* Get allowed app's routes array */
        $routes = require __DIR__ . ROUTES;

        /* Get request URI as a rout */
        $route = $_SERVER['REQUEST_URI'] ?? '';

        $isRouteFound = false;

        /* Walk through the allowed routes array to verify the input one as allowed or not */
        foreach ($routes as $pattern => $controllerNameAndAction) {
            preg_match($pattern, $route, $matches);
            if (!empty($matches)) {
                $isRouteFound = true;
                break;
            }
        }

        /* Throw the exception if the input rout is not allowed */
        if (!$isRouteFound) {
            throw new NotFoundException('Wrong page');
        }

        unset($matches[0]);

        /* Define associated controller and action */
        $controllerName = $controllerNameAndAction[0];
        $controllerAction = $controllerNameAndAction[1];
        $controller = new $controllerName();
        $controller->$controllerAction(...$matches);
    }
}