CREATE TABLE if not exists `author_book`
(
   `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
   `book_id` int(11) ,
   `author_id` int(11),
   FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
   FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci;