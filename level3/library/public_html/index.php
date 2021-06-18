<?php
ini_set('display_errors', 1);

use app\core\Router;
use app\exceptions\UnAllowedValueException;
use app\views\View;
use app\exceptions\DbException;
use app\exceptions\NotFoundException;

define('TEMPLATES_PATH', '/../app/templates');

/* Use classes autoloader */
spl_autoload_register(function (string $className) {
    require_once __DIR__ . '/../' . str_replace('\\', '/', $className) . '.php';
});

/* Get the app router instance */
$router = new Router();

try {
    $router->run();
} catch (NotFoundException $e) {
    $view = new View(__DIR__ . TEMPLATES_PATH);
    $view->renderHtml('404.php', ['error' => $e->getMessage()], 404);
} catch (UnAllowedValueException $e) {
    $view = new View(__DIR__ . TEMPLATES_PATH);
    $view->renderHtml('400.php', ['error' => $e->getMessage()], 400);
} catch (DbException $exception) {
    $view = new View(__DIR__ . TEMPLATES_PATH . '/errors');
    $view->renderHtml('500.php', ['error' => $exception->getMessage()], 500);
}