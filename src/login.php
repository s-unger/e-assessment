<!--base from https://www.php-einfach.de/experte/php-codebeispiele/loginscript/-->

<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=e-assessment_db', 'e-assessment_user', 'topsecretdbpass');
if (isset($_GET['login'])) {
    $username = $_POST['username'];
    $passwort = $_POST['passwort'];

    $statement = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $result = $statement->execute(array('username' => $username));
    $user = $statement->fetch();

    //Überprüfung des Passworts
    if ($user !== false && password_verify($passwort, $user['passwort'])) {
        $_SESSION['userid'] = $user['id'];
        header("Location: ../index.php");
    } else {
        $errorMessage = "Username oder Passwort war ungültig.<br>";
    }

}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<?php
if (isset($errorMessage)) {
    echo $errorMessage;
}
?>

<form action="?login=1" method="post">
    <h1>Login</h1>
    Username:<br>
    <input type="username" size="40" maxlength="250" name="username"><br><br>

    Dein Passwort:<br>
    <input type="password" size="40" maxlength="250" name="passwort"><br>

    <br>
    <input type="submit" value="Einloggen">

</form>
<form action="register.php" method="post">
    <p>Wenn du noch keinen Account hast, registriere dich hier:
        <input type="submit" value="Register">
    </p>
</form>
</body>
</html>