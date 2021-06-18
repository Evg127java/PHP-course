<?php

use app\controllers\BooksController;

return [
    '~^/(\d*)$~' => [BooksController::class, 'showAll'],
    '~^/book/(\d+)$~' => [BooksController::class, 'showOne'],
];