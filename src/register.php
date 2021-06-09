<!--base from https://www.php-einfach.de/experte/php-codebeispiele/loginscript/-->

<?php
session_start();
include("src/password.php");
$pdo = new PDO('mysql:host=localhost;dbname=e-assessment_db', 'e-assessment_user', 'topsecretdbpass');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrierung</title>
</head>
<body>

<?php
$showFormular = true; //Variable ob das Registrierungsformular angezeigt werden soll

if (isset($_GET['register'])) {
    $error = false;
    $username = $_POST['username'];
    $passwort = $_POST['passwort'];
    $passwort2 = $_POST['passwort2'];

    if (strlen($username) == 0) {
        echo 'Bitte einen Usernamen angeben.<br>';
        $error = true;
    }
    if (strlen($passwort) == 0) {
        echo 'Bitte ein Passwort angeben.<br>';
        $error = true;
    }
    if ($passwort != $passwort2) {
        echo 'Die Passwörter müssen übereinstimmen.<br>';
        $error = true;
    }

    //Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
    if (!$error) {
        $statement = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $result = $statement->execute(array('username' => $username));
        $user = $statement->fetch();

        if ($user !== false) {
            echo 'Dieser Username ist bereits vergeben.<br>';
            $error = true;
        }
    }

    //Keine Fehler, wir können den Nutzer registrieren
    if (!$error) {
        $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);

        $statement = $pdo->prepare("INSERT INTO users (username, passwort) VALUES (:username, :passwort)");
        $result = $statement->execute(array('username' => $username, 'passwort' => $passwort_hash));

        if ($result) {
            echo 'Du wurdest erfolgreich registriert. <a href="login.php">Zum Login</a>';
            $showFormular = false;
        } else {
            echo 'Beim Abspeichern ist leider ein Fehler aufgetreten.<br>';
        }
    }
}

if ($showFormular) {
    ?>

    <form action="?register=1" method="post">
        <h1>Register</h1>
        Username:<br>
        <input type="username" size="40" maxlength="250" name="username"><br><br>

        Dein Passwort:<br>
        <input type="password" size="40" maxlength="250" name="passwort"><br><br>

        Passwort wiederholen:<br>
        <input type="password" size="40" maxlength="250" name="passwort2"><br><br>

        <input type="submit" value="Registrieren">
    </form>
    <br>
    <form action="login.php" method="post">

        <input type="submit" value="zurück zum Login">

    </form>
    <?php
} //Ende von if($showFormular)
?>

</body>
</html>