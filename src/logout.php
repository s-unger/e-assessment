<?php
session_start();
session_destroy();

//base from https://www.php-einfach.de/experte/php-codebeispiele/loginscript/

header("Location: ../index.php");

?>