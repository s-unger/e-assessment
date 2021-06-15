<?php
session_start();
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
    if (isset($_POST['newTest'])) {
        newTest();
    }
}

function newTest()
{
    $_SESSION['newQuestions'] = true;
}

?>
<div class="content">
    <form action="test.php" method="post">
        <input type="submit" name="newTest" value="Mach den Test"/>
    </form>
</div>