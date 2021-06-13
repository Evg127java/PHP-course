<?php

require_once __DIR__ . '/constants.php';

header('Access-Control-Allow-Origin:' . FRONT_HOST);
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Set-Cookie');