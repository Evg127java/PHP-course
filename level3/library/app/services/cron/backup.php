<?php
$dbOptions = (require __DIR__ . '/../../core/config.php')['db'];
$date = (new DateTime('now'))->format('Y-m-d_H:i:s');
$fileName = $date . '_db_backup.sql';
$folderName = __DIR__ . '/backup/';
$command = 'mysqldump --user=' . $dbOptions['user'] . ' --password=' . $dbOptions['password'] . ' --host=' . $dbOptions['host'] . ' ' . $dbOptions['dbname'] . ' > ' . $folderName . $fileName;
exec($command);