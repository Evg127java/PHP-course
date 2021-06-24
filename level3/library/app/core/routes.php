<?php

use app\controllers\AdminController;
use app\controllers\BooksController;
use app\controllers\ServiceController;

return [
    '~^/(\d*)$~' => [BooksController::class, 'showAll'],
    '~^/book/(\d+)$~' => [BooksController::class, 'showOne'],
    '~^/book/click/(\d+)$~' => [BooksController::class, 'click'],
    '~^/books/search/(.*)$~' => [BooksController::class, 'search'],
    '~^/admin/(\d*)$~' => [AdminController::class, 'main'],
    '~^/admin/delete/(\d+)$~' => [AdminController::class, 'delete'],
    '~^/admin/add/(\d*)$~' => [AdminController::class, 'add'],
    '~^/admin/logout$~' => [AdminController::class, 'logout'],
    '~^/migration$~' => [ServiceController::class, 'migration'],
];