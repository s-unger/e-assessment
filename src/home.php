<?php if(!isset($_SESSION)) session_start();
//redirect to basepage if not logged in
if (!isset($_SESSION['userid'])) {
    header("Location: ../index.php");
}
include "../index.php"; ?>
<style>
    <?php include '../style.css'; ?>
</style>
<?php


if ($_POST) {
    if (isset($_POST['newExam'])) {
        newExam();
    }
}
if ($_POST) {
    if (isset($_POST['newTest'])) {
        newTest();
    }
}

?>
<div class="content">
    <form action="test.php" method="post">
        <input class="btn" type="submit" name="newTest" value="Übungsmodus"/>
    </form>
</div>
<div class="content">
<form action="test.php" method="post">
    <input class="btn" type="submit" name="newExam" value="Prüfungsmodus"/>
</form>
</div>