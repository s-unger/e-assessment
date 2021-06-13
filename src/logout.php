<!--base from https://www.php-einfach.de/experte/php-codebeispiele/loginscript/-->

<?php
session_start();
session_destroy();

header("Location: ../index.php");

?>