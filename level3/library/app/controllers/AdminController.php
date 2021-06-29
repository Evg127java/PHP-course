<?php

namespace app\controllers;

use app\Exceptions\NotFoundException;
use app\exceptions\UnAllowedValueException;
use app\models\Book;
use app\services\Paginator;
use app\services\UserDataProcessor;

/**
 * Controller of admin panel
 *
 * Class AdminController
 * @package app\controllers
 */
class AdminController extends Controller
{
    /* Set the number of books on the page to display */
    private const LIMIT = 5;

    /**
     * Main action for th admin panel
     *
     * @param $page page's number if pagination is active
     */
    public function main($page): void
    {
        $isUserAuthenticated = $this->checkAuth();
        if (!$isUserAuthenticated) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');

            /* The content which a user will see after press the "cancel" button */
            echo 'You are not authenticated!<br><a href="/">Home page</a>';
            exit();
        }

        /* Run runAdmin action if user is authenticated */
        $this->runAdmin($page);
    }

    /**
     * Checks if user is authenticated
     *
     * @return bool
     */
    private function checkAuth(): bool
    {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            return false;
        }
        $adminAuthData = (require __DIR__ . '/../core/config.php')['adminAuthData'];
        $login = $_SERVER['PHP_AUTH_USER'];
        $pass = $_SERVER['PHP_AUTH_PW'];
        return $adminAuthData['pass'] === $pass && $adminAuthData['login'] === $login;
    }

    /**
     * Runs actually admin panel if user is authenticated
     *
     * @param $page  page's number if pagination is active
     */
    private function runAdmin($page): void
    {
        /* Get paginator's parameters */
        $limit = self::LIMIT;
        $currentPage = $page === '' ? 1 : $page;
        $offset = ($currentPage - 1) * $limit;
        $books = Book::getByLimit($offset, $limit);
        $paginator = Paginator::getPaginator($currentPage, $limit);

        $this->view->renderHtml('admin.php', ['books' => $books, 'paginator' => $paginator, 'title' => 'admin page']);
        exit();
    }

    /**
     * Adds a new book to the library
     *
     * @param int $page page's number if pagination is active
     * @throws UnAllowedValueException
     */
    public function add(int $page): void
    {
        if (!empty($_POST)) {
            /* Preprocess user's input data */
            $sanitizedUserData = UserDataProcessor::sanitizeFormData($_POST);
            $isValidUserData = UserDataProcessor::validationFormData($sanitizedUserData);

            if ($isValidUserData) {
                $file = $_FILES['file'];
                Book::addBook($file, $sanitizedUserData);
            }
        }
        header('location: /admin/' . $page);
        exit();
    }

    /**
     * Deletes a book from the library
     *
     * @param int $id             The book's id value
     * @throws NotFoundException  NotFoundException if id is wrong
     */
    public function delete(int $id): void
    {
        $book = Book::getbyId($id);
        if ($book === null) {
            throw new NotFoundException('ERROR_404. Item with passed ID is not found');
        }
        $book->markAsDeleted()->setDeletedTime();

        /* Redirect to itself after delete */
        header('location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}