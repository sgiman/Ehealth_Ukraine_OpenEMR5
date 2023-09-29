<?php
//---------------------------- 
//                   mysql.php 
//----------------------------
define('DB_SERVER', 'db18.freehost.com.ua');
define('DB_USERNAME', 'sgiman_openemr');
define('DB_PASSWORD', 'PEWfuXLjh');
define('DB_DATABASE', 'sgiman_openemr'); 
$db = @mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die ('Ошибка соединения с БД');
if(!$db) die(mysqli_connect_error());
mysqli_set_charset($db, 'utf8') or die ('Не установлена кодировка');
?>