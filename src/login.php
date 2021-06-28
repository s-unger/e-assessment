<?php
session_start();

//base from https://www.php-einfach.de/experte/php-codebeispiele/loginscript/

$pdo = new PDO('mysql:host=localhost;dbname=e-assessment_db', 'e-assessment_user', 'topsecretdbpass');
//redirect to homepage if already logged in
if (isset($_SESSION['userid'])) {
    header("Location: home.php");
}
$errorMessage = "";
if (isset($_GET['login'])) {
    $username = $_POST['username'];
    $passwort = $_POST['passwort'];

    $statement = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $result = $statement->execute(array('username' => $username));
    $user = $statement->fetch();

    //Überprüfung des Passworts
    if ($user !== false && password_verify($passwort, $user['passwort'])) {
        $_SESSION['userid'] = $user['id'];
        header("Location: home.php");
    } else {
        $errorMessage = "Username oder Passwort war ungültig.<br>";
    }

}

include "../index.php"; ?>
<style>
    <?php include '../style.css'; ?>
</style>


<script>
    // don't show login/logout button while logging in
    document.getElementById("login").classList.add('disabled');
</script>
<div class="content">
    <form action="?login=1" method="post">
        <h1>Login</h1>
        <p class="u-title">
        Username:
        </p>
        <input class="reg-input" type="username" size="40" maxlength="250" name="username" placeholder="Username">

        <p class="title">
            Dein Passwort:
        </p>
        <input class="reg-input" type="password" size="40" maxlength="250" name="passwort" placeholder="Passwort"><br>

        <br>
        <span style="color: #D4842c"><?php echo $errorMessage ?></span><br>
        <input class="btn abgeben"type="submit" value="Einloggen">

    </form>
    <form action="register.php" method="post">
        <p>Wenn du noch keinen Account hast, registriere dich hier:     
            <input class="btn" type="submit" value="Register">
        </p>
    </form>
</div>
