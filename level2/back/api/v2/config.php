<?php

/**
 * Returns DB configuration for connection
 *
 * DB must consist a table with:
 *
 * 'id'(int(11), auto_increment),
 * 'text'(varchar(255)) and
 * 'checked'(tinyint(1), default = 0) fields
 * Engine: InnoDB, Collation: utf8_general_ci
 *
 * user, password and dsn must be noticed for the actual DB
 */

return
    [
        'dsn' => 'mysql:host=localhost;dbname=todoDB',  //DSN is needed
        'user' => 'user',                               //User name in DB is needed
        'password' => 'pass',                           //Password in DB is needed
    ];
