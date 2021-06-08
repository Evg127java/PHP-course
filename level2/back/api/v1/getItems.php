<?php
require_once 'headers.php';
require_once 'constants.php';

/* Just give all the items from the file to the front */
echo $data = file_get_contents(JSON_DATA_FILE);