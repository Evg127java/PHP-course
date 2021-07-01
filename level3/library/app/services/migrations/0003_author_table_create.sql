CREATE TABLE if not exists `authors`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `author` varchar(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;