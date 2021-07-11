<?php if(!isset($_SESSION)) session_start();
if (isset($_GET["vr"])) {
  if ($_GET["vr"] == "true") {
    $_SESSION['vr'] = true;
  } else {
    $_SESSION['vr'] = false;
  }
} else {
  $_SESSION['vr'] = false;
}

?>

<DOCTYPE html>
    <html>

    <?php
      include "src/include.php"; 
      include "src/include-header.php"; 
    ?>
    </body>
    </html>
