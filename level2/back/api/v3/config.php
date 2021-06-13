<?php
/**
 * Returns DB configuration for connection
 *
 * DB must consist a table 'items' with the following fields:
 *
 * 'id'(int(11), auto_increment),
 * 'text'(varchar(255)),
 * 'checked'(tinyint(1), default = 0),
 * 'user'(varchar(255))
 * Engine: InnoDB, Collation: utf8_general_ci
 *
 * and the table 'users' with the following fields:
 *
 * 'id'(int(11), auto_increment),
 * 'login'(varchar(255)),
 * 'password_hash'(varchar(255)),
 * Engine: InnoDB, Collation: utf8_general_ci
 *
 * user, password and dsn must be noticed for the actual DB
 */

return [
    'dsn' => 'mysql:host=localhost;dbname=todoDB',
    'user' => 'user',
    'password' => '12_b*2B',
];
