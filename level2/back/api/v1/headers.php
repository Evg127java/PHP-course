<?php

require_once 'constants.php';

/* Special headers to make possible cross domain requests */
header('Access-Control-Allow-Origin:' . FRONT_HOST);
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');