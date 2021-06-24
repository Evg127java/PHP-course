<?php

namespace app\controllers;


use app\Exceptions\NotFoundException;
use app\models\Book;
use app\services\Paginator;

/**
 * Controls books operation
 *
 * Class BooksController
 * @package app\controllers
 */
class BooksController extends Controller
{
    /* Set the number of books on the page to display */
    private const LIMIT = 20;

    /**
     * Displays books according to limit value for a separate page if paginator is active.
     * Otherwise displays all the books from the library
     *
     * @param $page paginator's page value
     */
    function showAll($page): void
    {
        /* Get paginator's parameters */
        $currentPage = $page === '' ? 1 : $page;
        $limit = self::LIMIT;
        $offset = ($currentPage - 1) * $limit;
        $paginator = Paginator::getPaginator($currentPage, $limit);
        $books = Book::getByLimit($offset, $limit);

        $this->view->renderHtml('books-page.php', ['books' => $books, 'paginator' => $paginator, 'title' => 'shpp-library: all books']);
        exit();
    }

    /**
     * Displays one book from the library specified by the passed id value
     *
     * @param $id                 book's id to display
     * @throws NotFoundException  Throw NotFoundException if the passed id is not correct
     */
    function showOne(int $id): void
    {
        /* Try to get a book from Db by specified ID */
        $book = Book::getbyId($id);
        if ($book === null) {
            throw new NotFoundException('ERROR_404. Page not found');
        }
        /* Update views counter for the specified book after displaying it */
        $book->updateViews();
        $this->view->renderHtml('book-page.php', ['book' => $book, 'title' => 'shpp-library: one book']);
        exit();
    }

}