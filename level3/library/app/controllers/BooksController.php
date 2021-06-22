<?php

namespace app\controllers;


use app\Exceptions\NotFoundException;
use app\models\Book;

/**
 * Controls books operation
 *
 * Class BooksController
 * @package app\controllers
 */
class BooksController extends Controller
{
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