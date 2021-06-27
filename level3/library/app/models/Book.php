<?php

namespace app\models;

use app\services\Db;
use app\services\UserDataProcessor;

/**
 * Processes DB actions with books
 *
 * Class Book
 * @package app\models
 */
class Book
{

    /**
     * Default image filename for a book
     */
    const DEFAULT_IMG = 'loading.gif';

    /**
     * Default book description
     */
    const BOOK_DESCRIPTION = 'Description is absent';

    /**
     * @var 'book' id value
     */
    public $id;

    /**
     * @var 'book clicks counter'
     */
    public $clicks;

    /**
     * @var 'book views counter'
     */
    public $views;

    /**
     * Gets all the books from DB
     * The response includes authors field for a book
     *
     * @return array Array of books
     */
    public static function getAll(): array
    {
        $db = Db::getInstance();
        $sql = 'SELECT books.*, GROUP_CONCAT(authors.author SEPARATOR \', \') AS author 
                FROM books 
                LEFT JOIN author_book ON author_book.book_id = books.id 
                LEFT JOIN authors ON authors.id = author_book.author_id 
                WHERE books.is_deleted = 0 
                GROUP BY books.id';
        return $db->queryWithResult($sql, [], static::class);
    }

    /**
     * Gets books amount defined by the limit's value
     *
     * @param $offset  'from what id to take entries'
     * @param $limit   ' how many entries to take'
     * @return array    Array of specified entries amount
     */
    public static function getByLimit(int $offset, int $limit): array
    {
        $db = Db::getInstance();
        $sql = 'SELECT books.*, GROUP_CONCAT(authors.author SEPARATOR \', \') AS author 
                FROM books 
                LEFT JOIN author_book ON author_book.book_id = books.id 
                LEFT JOIN authors ON authors.id = author_book.author_id 
                WHERE books.is_deleted = 0
                GROUP BY books.id 
                LIMIT ? OFFSET ?';
        return $db->queryWithResult($sql, [$limit, $offset], static::class);
    }

    /**
     * Gets a book from DB by specified id value
     *
     * @param $id
     * @return mixed|null
     */
    public static function getById(int $id): ?Book
    {
        $db = Db::getInstance();
        $sql = 'SELECT books.*, GROUP_CONCAT(authors.author SEPARATOR \', \') AS author 
                FROM books 
                LEFT JOIN author_book ON author_book.book_id = books.id 
                LEFT JOIN authors ON authors.id = author_book.author_id 
                WHERE books.is_deleted = 0 
                GROUP BY books.id
                HAVING books.id = :id';
        $entity = $db->queryWithResult($sql, [':id' => $id], static::class);
        return $entity ? $entity[0] : null;
    }

    /**
     * Gets books amount in the DB(except of soft deleted)
     *
     * @return mixed
     */
    public static function getBooksNumber(): int
    {
        $db = Db::getInstance();
        $sql = 'SELECT COUNT(*) as number FROM books WHERE books.is_deleted = 0';
        $number = $db->queryWithResult($sql);
        return $number[0]->number;
    }

    /**
     * Gets books relevant to the search string value
     *
     * @param string $title
     * @return array|null
     */
    public static function getByTitle(string $title): ?array
    {
        $db = Db::getInstance();
        $sql = 'SELECT books.*, GROUP_CONCAT(authors.author SEPARATOR \', \') AS author 
                FROM books 
                LEFT JOIN author_book ON author_book.book_id = books.id 
                LEFT JOIN authors ON authors.id = author_book.author_id 
                WHERE books.is_deleted = 0
                GROUP BY books.id
                HAVING books.title LIKE :title';
        return $db->queryWithResult($sql, [':title' => '%' . $title . '%'], static::class);
    }

    /**
     * Set book's delete time
     */
    public function setBookDeletedTime(): Book
    {
        $db = Db::getInstance();
        /* Get current data in the proper format to save in DB */
        $date = new \DateTime('now');
        $date = $date->format('Y-m-d H:i:s');

        /* Set deleted_date */
        $sql2 = 'UPDATE books SET deleted_date = :date  WHERE id = :id';
        $db->queryWithoutResult($sql2, [':id' => $this->id, ':date' => $date]);
        return $this;
    }

    /**
     * Marks a book as deleted
     */
    public function markBookAsDeleted(): Book
    {
        $db = Db::getInstance();
        /* Set is_deleted attribute */
        $sql = 'UPDATE books SET is_deleted = 1 WHERE id = :id';
        $db->queryWithoutResult($sql, [':id' => $this->id]);
        return $this;
    }

    /**
     * Updates views counter
     */
    public function updateViews()
    {
        $db = Db::getInstance();
        $sql = 'UPDATE books SET views = :views WHERE id = :id';
        $db->queryWithoutResult($sql, [':views' => $this->views + 1, ':id' => $this->id]);
    }

    /**
     * Updates clicks counter
     */
    public function updateClicks()
    {
        $db = Db::getInstance();
        $sql = 'UPDATE books SET clicks = :clicks WHERE id = :id';
        $db->queryWithoutResult($sql, [':clicks' => $this->clicks + 1, ':id' => $this->id]);
    }

    /**
     * Adds a book to DB
     *
     * @param $file
     * @param $post
     */
    public static function addBook(array $file, array $post): void
    {
        /* Add default description if input description is absent */
        $description = empty($post['description']) ? self::BOOK_DESCRIPTION : $post['description'];
        $db = Db::getInstance();

        /* Get id for uploaded image file naming */
        $bookId = self::getMaxId($db, 'books') + 1;

        /* Try to upload image file */
        $imgName = self::bookImageFileUpload($file, $bookId);

        if ($imgName == null) {
            $uploadedFileName = self::DEFAULT_IMG;
        } else {
            $uploadedFileName = $imgName;
        }

        $sql = 'INSERT INTO books (title, year, about, img) VALUES (?, ?, ?, ?)';
        $db->queryWithoutResult($sql, [$post['title'], $post['year'], $description, $uploadedFileName]);

        /* Bind book and its authors */
        self::BindBookAndAuthors($db, $bookId, $post);
    }

    /**
     * Gets max id value in the DB specified table
     *
     * @param Db $db
     * @param $table
     * @return mixed
     */
    private static function getMaxId(Db $db, string $table): int
    {
        $sql = 'SELECT * FROM ' . $table . ' ORDER BY id DESC LIMIT 1';
        return $db->queryWithResult($sql)[0]->id;
    }

    /**
     * Binds a book instance with its authors
     *
     * @param $db
     * @param $bookId  'Book id which must be bound
     * @param $post    'Input data which contains authors set
     */
    private static function BindBookAndAuthors($db, $bookId, $post)
    {

        $authors = UserDataProcessor::getAuthors($post);

        foreach ($authors as $author) {
            $sql = 'SELECT id FROM authors WHERE author = :author ';
            $authorFromDb = $db->queryWithResult($sql, [':author' => $author]);
            if (!empty($authorFromDb)) {
                /* Get author id from DB if it exists */
                $authorId = $authorFromDb[0]->id;
            } else {
                /* Otherwise insert author to DB and get its id */
                $sql = 'INSERT INTO authors (author) VALUES (?)';
                $db->queryWithoutResult($sql, [$author]);
                $authorId = $db->getLastInsertId();
            }
            /* Put an entry with author id and book id to the pivot table */
            $sql = 'INSERT INTO author_book (author_id, book_id) VALUES (?, ?)';
            $db->queryWithoutResult($sql, [$authorId, $bookId]);
        }
    }

    /**
     * Uploads image file
     *
     * @param $file         array of a file attachment
     * @param int $bookId   relative book's id
     * @return string|null  uploaded image file name or null
     */
    private static function bookImageFileUpload(array $file, int $bookId): ?string
    {
        if ($file['error'] != 0) {
            return null;
        }

        /* Path with images to upload */
        $path = $_SERVER['DOCUMENT_ROOT'] . '/images/';

        /* Get uploading file ext */
        list(, $fileExt) = preg_split('#\.#', $file['name']);

        /* Uploading file name */
        $img = $bookId . '.' . $fileExt;

        /* Full path and file name to upload */
        $fileToSave = $path . $bookId . '.' . $fileExt;

        return move_uploaded_file($file['tmp_name'], $fileToSave) ? $img : null;
    }

    /**
     * Gets only fields contained "author" from the upload form
     *
     * @param $array  'Input data array
     * @return array   Authors array
     */
    private static function getBookAuthorsFromInput(array $array): array
    {
        $authors = [];
        foreach ($array as $item => $value) {
            if (preg_match('#author#', $item) && $value != '') {
                $authors[] = $value;
            }
        }
        return $authors;

    }
}