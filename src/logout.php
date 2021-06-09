<!--base from https://www.php-einfach.de/experte/php-codebeispiele/loginscript/-->

<?php
session_start();
session_destroy();


$is_page_refreshed = (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] == 'max-age=0' || $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache');

if ($is_page_refreshed) {
    header("Location: ../index.php");
}
?>