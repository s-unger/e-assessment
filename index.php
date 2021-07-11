<?php if(!isset($_SESSION)) session_start();
error_reporting(-1);
ini_set('display_errors', 'On');
if (isset($_GET['vr'])) {
  if ($_GET['vr'] == "true") {
    $_SESSION['vr'] = true;
  } else {
    $_SESSION['vr'] = false;
  }
} else {
  $_SESSION['vr'] = false;
}
$login = "";
$loginSrc = "";
$url = "";
$newQuestions = true;
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

function newExam(){
    $_SESSION['newQuestions'] = true;
    $_SESSION['isExam'] = true;
    $_SESSION['isSubmittable'] = true;
}

function newTest()
{
    $_SESSION['newQuestions'] = true;
    $_SESSION['isExam'] = false;
    $_SESSION['isSubmittable'] = true;
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
            <li><a href="home.php"><?php if (isset($user)) {
                        echo "Hallo, ";
                        echo $user["username"]; 
                    } ?></a></li>
            <li style="float: right;"><a id="login" href="<?php echo $loginSrc ?>.php"><?php echo $login ?></a>
            </li>
        </ul>
        <?php if ($_SESSION['vr']) echo "TRUE"; ?>
    </div>
    </body>
    </html>
