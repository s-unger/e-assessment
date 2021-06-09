<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=e-assessment_db', 'e-assessment_user', 'topsecretdbpass');
if (!isset($_SESSION['userid'])) {
    die('<p>Du bist ausgeloggt</p><p>Bitte zuerst <a href="src/login.php">einloggen</a></p>');
} else {
    $userid = $_SESSION['userid'];
    $statement = $pdo->prepare("SELECT username FROM users WHERE id = :userid");
    $result = $statement->execute(array('userid' => $userid));
    $user = $statement->fetch();
}

?>

<DOCTYPE html>
    <html>

    <head>
        <title>Mathe-Tiger</title>
        <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
        <script>

            function login() {
                $('#content').load('src/login.php');
            }

            function register() {
                $('#content').load('src/register.php');
            }
        </script>

    </head>
    <body>
    <h1>Werde zum Mathetiger!</h1>
    <p>Werde mit uns zum Mathetiger. Das hier ist die Startseite mit statischem und...</p>
    <?php echo "...dynamischem Content"; ?>


    <div id="content">


        <br>
        <div><?php echo 'Gerade eingeloggt ist: ' . $user["username"] ?></div>
        <br>
        <form action="src/logout.php" method="post">
            <input type="submit" value="logout">
        </form>
    </div>


    </body>
    </html>

