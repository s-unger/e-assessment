<?php
session_start();
$login = "";
$loginSrc = "";
$url = "";
$pdo = new PDO('mysql:host=localhost;dbname=e-assessment_db', 'e-assessment_user', 'topsecretdbpass');
if (!isset($_SESSION['userid'])) {
    $login = "Login";
    $loginSrc = "src/login";
} else {
    $userid = $_SESSION['userid'];
    $statement = $pdo->prepare("SELECT username FROM users WHERE id = :userid");
    $result = $statement->execute(array('userid' => $userid));
    $user = $statement->fetch();
    $login = "Logout";
    $url .= $_SERVER['REQUEST_URI'];
    if (strpos($url, 'src')) {
        $loginSrc = "logout";
    } else {
        $loginSrc = "src/logout";
    }
}

?>

<DOCTYPE html>
    <html>

    <head>
        <title>Mathe-Tiger</title>
        <link rel="stylesheet" href="style.css">
        <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    </head>
    <body>
    <div class="headerWrapper">
        <div class="header">
            <h1 style="flex: 1;">Werde zum Mathetiger!</h1>
            <!--            source: https://www.pngwing.com/de/free-png-blxte/download & https://www.pngwing.com/de/free-png-vegxa/download-->
            <img src="/images/tiger.png"/>
        </div>
    </div>
    <div class="nav-wrapper">
        <ul>
            <li><span><?php if (isset($user)) {
                        echo $user["username"];
                    } ?></span></li>
            <li style="float: right;"><a id="login" href="<?php echo $loginSrc ?>.php"><?php echo $login ?></a>
            </li>
            <li style="float: left;"><a id="test" href="src/test.php">Mach den Test!</a>
            </li>
        </ul>
    </div>
    </body>
    </html>

