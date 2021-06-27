<?php
namespace app\services\cron;

use app\services\Db;

require_once __DIR__ . '/../Db.php';
$db = Db::getInstance();

$sql = 'DELETE books, author_book
        FROM books 
        JOIN author_book 
        ON author_book.book_id = books.id
        WHERE now() >= books.deleted_date + INTERVAL 1 HOUR ';

$db->queryWithoutResult($sql);