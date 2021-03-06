<?php
session_start();

//base from https://www.php-einfach.de/experte/php-codebeispiele/loginscript/

include("password.php");
include "include.php";
//redirect to homepage if already logged in
if (isset($_SESSION['userid'])) {
    header("Location: home.php");
}
$feedbackUserName = "";
$feedbackPW = "";
$feedbackPWSame = "";
$errorMessage = "";

include "../index.php"; ?>
    <style>
        <?php include '../style.css'; ?>
    </style>

    <script>
        // don't show login/logout button while registering
        document.getElementById("login").classList.add('disabled');
    </script>
<?php
$showFormular = true; //Variable ob das Registrierungsformular angezeigt werden soll

if (isset($_GET['register'])) {
    $error = false;
    $username = $_POST['username'];
    $passwort = $_POST['passwort'];
    $passwort2 = $_POST['passwort2'];

    if (strlen($username) == 0) {
        $feedbackUserName = "Bitte einen Usernamen angeben.<br>";
        $error = true;
    }
    if (strlen($passwort) == 0) {
        $feedbackPW = "Bitte ein Passwort angeben.<br>";
        $error = true;
    }
    if ($passwort != $passwort2) {
        $feedbackPWSame = "Die Passwörter müssen übereinstimmen.<br>";
        $error = true;
    }

    //Überprüfe, dass der Username noch nicht registriert wurde
    if (!$error) {
        $statement = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $result = $statement->execute(array('username' => $username));
        $user = $statement->fetch();

        if ($user !== false) {
            $feedbackUserName = "Dieser Username ist bereits vergeben.";
            $error = true;
        }
    }

    //Keine Fehler, wir können den Nutzer registrieren
    if (!$error) {
        $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);

        $statement = $pdo->prepare("INSERT INTO users (username, passwort) VALUES (:username, :passwort)");
        $result = $statement->execute(array('username' => $username, 'passwort' => $passwort_hash));

        if ($result) {
            echo '<div class="content">Du wurdest erfolgreich registriert. <a href="login.php">Zum Login</a> </div>';
            $showFormular = false;
        } else {
            $errorMessage = "Beim Abspeichern ist leider ein Fehler aufgetreten.";
        }
    }
}

if ($showFormular) {
    ?>
    <div class="content">

        <form class="form" action="?register=1" method="post">
            <span style="color: #D4842c"><?php echo $errorMessage ?></span>
            <h1>Register</h1>
            <p class="u-title">
            Username:
            </p>
            <input class="reg-input" type="username" size="40" maxlength="250" name="username" placeholder="Username">
            <p class="p-title">
            Dein Passwort:
            </p>
            <input class="reg-input" type="password" size="40" maxlength="250" name="passwort" placeholder="Passwort">
            <p class="pw-title">
            Passwort wiederholen:
            </p>
            <input class="reg-input" type="password" size="40" maxlength="250" name="passwort2" placeholder="Passwort wiederholen" >

            <span style="color: #D4842c"><?php echo $feedbackUserName ?></span>
            <span style="color: #D4842c"><?php echo $feedbackPW ?></span>
            <span style="color: #D4842c"><?php echo $feedbackPWSame ?></span>
            <br>

            <input class="btn abgeben reg" type="submit" value="Registrieren">


        </form>
        
        <form action="login.php" method="post">

            <input class="btn" type="submit" value="zurück zum Login">

        </form>

    </div>
    <?php
} //Ende von if($showFormular)
?>
