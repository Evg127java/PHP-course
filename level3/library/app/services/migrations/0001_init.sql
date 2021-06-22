create table if not exists `books`
(
    `id`      int(11) NOT NULL AUTO_INCREMENT,
    `title`   varchar(255) NOT NULL,
    `year`    int(4) NOT NULL,
    `img`     varchar(255) DEFAULT NULL,
    `pages`   int NOT NULL DEFAULT 1,
    `about`   varchar(1000) DEFAULT NULL,
    `is_deleted` TINYINT(1) NOT NULL DEFAULT 0,
    `deleted_date` datetime DEFAULT NULL,
    `clicks`  int(11) NOT NULL DEFAULT 0,
    `views`   int NOT NULL DEFAULT 0,
    primary key (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

create table if not exists `versions`
(
    `id`      int(11) NOT NULL AUTO_INCREMENT,
    `name`    varchar(255) NOT NULL,
    `created` timestamp DEFAULT current_timestamp,
    primary key (id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
