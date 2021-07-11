<?php if(!isset($_SESSION)) session_start();
//redirect to basepage if not logged in
if (!isset($_SESSION['userid'])) {
    header("Location: ../index.php");
}
include "../index.php";
/**
 * Misconception reminders to be added to the LA if a certain misconception has been repeated multiple times in a row.
 * Reminders for misconception 0, 1 and 2.
 * Prototype only for question 5.
 * Links are mock links (should link to help sites for the specific problem).
 */
$misconceptionReminder5_0 = "Fehlertyp: <b>Keine Überträge</b> 
<br>Beachte den <b>Zehnerübergang</b>! Nach der Erweiterung der Einerziffer des Minuenden findet ein <b>Übertrag</b> zur Zehnerziffer statt. 
<br><a href=home.php>Lern mehr!</a>";
$misconceptionReminder5_1 = "Fehlertyp: <b>Spaltenweise Unterschiedsbildung</b>
<br>An der Einerstelle kann nicht einfach die kleinere von der größeren Ziffer abgezogen werden. 
Um an der Einerstelle Minus zu rechnen, benötigst du einen Übertrag in die Zehnerstelle.
<br><a href=home.php>Lern mehr!</a>";
$misconceptionReminder5_2 = "Fehlertyp: <b>Probleme mit dem Textverständnis</b>
<br>Lies dir genau die Aufgabentexte durch. Um welche Rechentypen handelt es sich?
<br><a href=home.php>Lern mehr!</a>";
?>
<style>
    <?php include '../style.css'; ?>
</style>

<div class="content">
    <form action="test.php" method="post">
        <input class="btn" type="submit" name="newTest" value="Übungsmodus"/>
    </form>
    <?php 
      if ($_SESSION['vr']) {
        echo '<form action="test.php" method="post"><input class="btn" type="submit" name="newExam" value="Prüfungsmodus"/></form>';
      } 
    ?>
</div>
