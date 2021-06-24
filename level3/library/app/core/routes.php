<?php

use app\controllers\BooksController;
use app\controllers\ServiceController;

return [
    '~^/(\d*)$~' => [BooksController::class, 'showAll'],
    '~^/book/(\d+)$~' => [BooksController::class, 'showOne'],
    '~^/migration$~' => [ServiceController::class, 'migration'],
    '~^/books/search/(.*)$~' => [BooksController::class, 'search'],
    '~^/admin/(\d*)$~' => [AdminController::class, 'main'],
];