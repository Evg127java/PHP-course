<?php

namespace app\services;

use app\models\Book;

/**
 * Class Paginator
 * @package app\services
 */
class Paginator
{
    /**
     * Returns the paginator instance with current parameters
     *
     * @param $currentPage 'Current page from view
     * @param $limit        'pages per page value
     * @return array        paginator parameters array
     */
    public static function getPaginator($currentPage, $limit)
    {
        /* Get all the books amount from DB */
        $booksNumber = Book::getBooksNumber();

        /* Get pages amount */
        $pages = ceil($booksNumber / $limit);

        /* Define if is there the next page from the current one */
        $hasNext = (($currentPage - 1) * $limit) + $limit < $booksNumber;

        /* Define if is there the previous page from the current one */
        $hasPrevious = $currentPage > 1;

        return
            [
                'pages' => $pages,
                'currentPage' => $currentPage,
                'hasNext' => $hasNext,
                'hasPrevious' => $hasPrevious,
            ];
    }
}