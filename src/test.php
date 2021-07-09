<?php session_start();
$pdo = new PDO('mysql:host=localhost;dbname=e-assessment_db', 'e-assessment_user', 'topsecretdbpass');
$feedback1 = "";
$feedback2 = "";
$feedback3 = "";
$feedback4 = "";
$feedback5 = "";
$feedback6 = "";
$feedback7 = "";
$feedback8 = "";
$feedbackTotal = "";
$pointsTotal = 12;
include('questionGenerator.php');
include "../index.php"; ?>
<style>
    <?php include '../style.css'; ?>
</style>
<script>
    function allowDrop(e) {
        e.preventDefault();
    }

    function drag(e) {
        e.originalEvent.dataTransfer.setData('Text', e.target.id);
    }

    function drop(e) {
        e.preventDefault();
        var data = e.originalEvent.dataTransfer.getData('Text');
        document.getElementById(data).setAttribute('name', e.target.getAttribute('name'));
        e.target.appendChild(document.getElementById(data));
        $(this).off('dragover drop');
        // make all empty dropContainers droppable again
        $('.dropContainer').each(function () {
            if ($(this).is(':empty')) {
                $(this).on('dragover', allowDrop);
                $(this).on('drop', drop);
            }
        });
    }

    function dropOnDropBaseHandler(e) {
        e.preventDefault();
        var data = e.originalEvent.dataTransfer.getData('Text');
        document.getElementById(data).removeAttribute('name');
        e.target.appendChild(document.getElementById(data));
        $('.dropContainer').each(function () {
            if ($(this).is(':empty')) {
                $(this).on('dragover', allowDrop);
                $(this).on('drop', drop);
            }
        });
    }

    $(document).ready(function () {
        $('.drag').on('dragstart', drag);
        $('.dropContainer, .dropBase').on('dragover', allowDrop);
        $('.dropContainer').on('drop', drop);
        $('.dropBase').on('drop', dropOnDropBaseHandler);
    });
</script>
<?php

//if ($_SERVER["REQUEST_METHOD"] == "POST") {
//if(isset($_POST["check"])){
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['check'])) {


    $statement = $pdo->prepare("UPDATE users SET amount_of_tests = amount_of_tests+1 WHERE id = :id ");
    //$statement->bindParam('i', $_SESSION['userId']);
    // $result = $statement->execute([$_SESSION['userId']]);

    $result = $statement->execute(array('id' => $_SESSION['userid']));
}
$q1 = "";
$q2 = "";
$q3 = "";
$q41 = "";
$q42 = "";
$q43 = "";
$q5 = "";
$q6 = "";
$q7 = "";
$q8 = "";
if (isset($_POST['ans1'])) {
    $q1 = $_POST['ans1'];
}
if (isset($_POST['ans2'])) {
    $q2 = $_POST['ans2'];
}
if (isset($_POST['ans3'])) {
    $q3 = $_POST['ans3'];
}
if (isset($_POST['ans4-1'])) {
    $q41 = $_POST['ans4-1'];
}
if (isset($_POST['ans4-2'])) {
    $q42 = $_POST['ans4-2'];
}
if (isset($_POST['ans4-3'])) {
    $q43 = $_POST['ans4-3'];
}
if (isset($_POST['ans5'])) {
    $q5 = $_POST['ans5'];
}
if (isset($_POST['ans6'])) {
    $q6 = $_POST['ans6'];
}
if (isset($_POST['ans7'])) {
    $q7 = $_POST['ans7'];
}
if (isset($_POST['ans8'])) {
    $q8 = $_POST['ans8'];
}

$correct = 0;
$correctness = 0;


if ($q1 == "" || $q2 == "" || $q3 == "" || $q41 == "" || $q42 == "" || $q43 == "" || $q5 == "" || $q6 == "" || $q7 == "" || $q8 == "" ) {
    $feedbackTotal = "Bitte alle Fragen beantworten.";
} else {
    $statement = $pdo->prepare("INSERT INTO answers (userId, questionId, correctness) VALUES (:userId, :questionId, :correctness)");
    ($q1 == "true") ? $truefalseGerman = "Wahr" : $truefalseGerman = "Falsch";
    $feedback1 = "Deine Antwort: \"$truefalseGerman\"<br>";
    if (($q1 == "true") == $_SESSION['solution_truefalse_1']) {
        $correctness = 1;
        $correct++;
        $feedback1 .= "<b>Richtig!</b>";
    } else {
        $correctness = 0;
        $feedback1 .= "<b>Leider falsch!</b> <br>" . $_SESSION['feedback_truefalse_1'];
    }
    $result = $statement->execute(array('userId' => $_SESSION['userid'], 'questionId' => 0, 'correctness' => $correctness));

    $feedback2 = "Deine Antwort: $q2<br>";
    if ($q2 == $_SESSION['solution_multiplechoice_1']) {
        $correctness = 1;
        $correct++;
        $feedback2 .= "<b>Richtig!</b>";
    } else {
        $correctness = 0;
        if ($q2 == $_SESSION['solution_multiplechoice_1'] + 1 || $q2 == $_SESSION['solution_multiplechoice_1'] - 1) {
            $feedback2 .= "<b>Fast richtig!</b> Die richtige Antwort ist " . $_SESSION['solution_multiplechoice_1'] . ".";
        } else {
            $feedback2 .= "<b>Leider falsch!</b>  Die richtige Antwort ist " . $_SESSION['solution_multiplechoice_1'] . ".";
        }
    }
    $result = $statement->execute(array('userId' => $_SESSION['userid'], 'questionId' => 1, 'correctness' => $correctness));

    $feedback3 = "Deine Antwort: $q3<br>";
    if ($q3 == $_SESSION['solution_numerical_1']) {
        $correctness = 1;
        $correct++;
        $feedback3 .= "<b>Richtig!</b>";
    } else {
        $correctness = 0;
        if ($q3 == $_SESSION['solution_numerical_1'] + 1 || $q3 == $_SESSION['solution_numerical_1'] - 1) {
            $feedback3 .= "<b>Fast richtig!</b> Die richtige Antwort ist " . $_SESSION['solution_numerical_1'] . ".";
        } else {
            $feedback3 .= "<b>Leider falsch!</b>  Die richtige Antwort ist " . $_SESSION['solution_numerical_1'] . ".";
        }
    }
    $result = $statement->execute(array('userId' => $_SESSION['userid'], 'questionId' => 2, 'correctness' => $correctness));

    $feedback4 = "Deine Antwort: <br>$q41<br>$q42<br>$q43<br>";
    $correct4 = 0;
    if ($q41 == $_SESSION['solution_matching_1'][0]) $correct4++;
    if ($q42 == $_SESSION['solution_matching_1'][1]) $correct4++;
    if ($q43 == $_SESSION['solution_matching_1'][2]) $correct4++;
    $correct += $correct4;
    $feedback4 .= "<b>" . $correct4 . " von 3 richtig!</b>";
    $correctness = 1;
    if ($correct4 <3) {
        $feedback4 .= "<br>Die richtige Reihenfolge ist: <br>" . $_SESSION['solution_matching_1'][0]
            . "<br>" . $_SESSION['solution_matching_1'][1]
            . "<br>" . $_SESSION['solution_matching_1'][2];
        $correctness = 0;
    }
    $result = $statement->execute(array('userId' => $_SESSION['userid'], 'questionId' => 3, 'correctness' => $correctness));

    $feedback5 = "Deine Antwort: $q5<br>";
    if ($q5 == $_SESSION['solution_numerical_2']) {
        $correctness = 1;
        $correct = $correct + 2;
        $feedback5 .= "<b>Richtig!</b>";
    } else {
        $feedback5 .= "<b>Leider falsch!</b> Die richtige Antwort ist " . $_SESSION['solution_numerical_2'] . ".";
        $correctness = 0;
        if ($q5 == $_SESSION['misc_carry1_numerical_2']) {
            $feedback5 .= "<br>Beachte den <b>Zehnerübergang</b>! Nach der Erweiterung der Einerstelle findet ein <b>Übertrag</b> in die Zehnerstelle statt.";
        } else if ($q5 == $_SESSION['misc_carry2_numerical_2']) {
            $feedback5 .= "<br>An der Einerstelle kann nicht einfach die kleinere von der größeren Ziffer abgezogen werden. Um an der Einerstelle Minus zu rechnen, benötigst du einen Übertrag in die Zehnerstelle.";
        } else if ($q5 == $_SESSION['misc_operator_numerical_2']) {
            $feedback5 .= "<br>Lies noch einmal genau den Aufgabentext! Es werden Bücher <b>weggenommen</b>.";
        }
    }
    $result = $statement->execute(array('userId' => $_SESSION['userid'], 'questionId' => 4, 'correctness' => $correctness));

    $feedback6 = "Deine Antwort: $q6<br>";
    if ($q6 == $_SESSION['solution_multiplechoice_2']) {
        $correct = $correct + 2;
        $correctness = 1;
        $feedback6 .= "<b>Richtig!</b>";
    } else {
        $feedback6 .= "<b>Leider falsch!</b>  Die richtige Antwort ist " . $_SESSION['solution_multiplechoice_2'] . ".";
        $correctness = 0;
        for ($i = 0; $i < 4; $i++) {
            if ($q6 == $_SESSION['options_multiplechoice_2'][$i][0]) $feedback6 .= "<br>" . $_SESSION['options_multiplechoice_2'][$i][1];
        }
    }
    $result = $statement->execute(array('userId' => $_SESSION['userid'], 'questionId' => 5, 'correctness' => $correctness));

    $feedback7 = "Deine Antwort: $q7<br>";
    if ($q7 == $_SESSION['solution_short_text_1']) {
        $correct++;
        $correctness = 1;
        $feedback7 .= "<b>Richtig!</b>";
    }else {
        $feedback7 .= "<b>Leider falsch!</b>  Die richtige Antwort ist " . $_SESSION['solution_short_text_1'] . ".";
        $correctness = 0;
    }
    $result = $statement->execute(array('userId' => $_SESSION['userid'], 'questionId' => 6, 'correctness' => $correctness));


    $feedback8 = "Deine Antwort: $q8<br>";
    if ($q8 == $_SESSION['solution_text_to_term']) {
        $correct++;
        $correctness = 1;
        $feedback8 .= "<b>Richtig!</b>";
    }else {
        $feedback8 .= "<b>Leider falsch!</b>  Die richtige Antwort ist " . $_SESSION['solution_text_to_term'] . ".";
        $correctness = 0;
    }
    $result = $statement->execute(array('userId' => $_SESSION['userid'], 'questionId' => 7, 'correctness' => $correctness));


    $feedbackTotal = "Deine erreichte Punktzahl: $correct von $pointsTotal";
    if ($correct == $pointsTotal-3 || $correct == $pointsTotal-4) $feedbackTotal .= "<br>Gute Leistung. Weiter so!";
    else if ($correct == $pointsTotal-1 || $correct == $pointsTotal-2) $feedbackTotal .= "<br>Sehr gute Leistung. Weiter so!";
    else if ($correct == $pointsTotal) $feedbackTotal .= "<br>Perfekte Leistung. Weiter so!";


    $statement2 = $pdo->prepare("INSERT INTO tests (userId, date, amount, correct) VALUES (:userId, :date, :amount, :correct) ON DUPLICATE KEY UPDATE amount = amount+1, correct = (correct+$correct)/2");
    $result2 = $statement2->execute(array('userId' => $_SESSION['userid'], 'date' => date("Y/m/d"), 'amount' => 1, 'correct' => $correct));

}
?>
<?php
if ($_POST) {
    if (isset($_POST['check'])) {
        check();
    } elseif (isset($_POST['newTest'])) {
        newTest();
    }
}

function newTest()
{
    $_SESSION['newQuestions'] = true;
}

function check()
{
    $_SESSION['newQuestions'] = false;
}

?>
<div class="content">
    <h1>Mache den Test</h1>
    <p>Finde hier heraus, wie gut du in Mathe bist. Immer wieder im Schuljahr werden Tests hier benotet.</p>
    <form action="test.php" method="post">
        <span class="feedback"><?php echo $feedbackTotal ?></span>
        <br>

        <div class="question">
            <p class="q-title"> Aufgabe 1 (1P)</p>
            <p class="q1"> <?php
            if ($_SESSION['newQuestions'] == true) {
                generate_truefalse_1();
            }
            echo $_SESSION['question_truefalse_1'] . "<br>";
            ?>
            </p>
            <input type="radio" name="ans1" value="true"><label>Wahr</label>
            <input type="radio" name="ans1" value="false"><label>Falsch</label>

            <br>
            <span class="feedback"><?php echo $feedback1 ?></span>
        </div>

        <div class="question">
            <p class="q-title"> Aufgabe 2 (1P)</p>
            <p class="q1">
            <?php
            if ($_SESSION['newQuestions'] == true) {
                generate_multiplechoice_1();
            }
            echo $_SESSION['question_multiplechoice_1'] . "<br>";
            $opt1 = $_SESSION['options_multiplechoice_1'][0];
            $opt2 = $_SESSION['options_multiplechoice_1'][1];
            $opt3 = $_SESSION['options_multiplechoice_1'][2];
            $opt4 = $_SESSION['options_multiplechoice_1'][3];
            ?>
            </p>
            <input type="radio" name="ans2" value="<?= $opt1 ?>"><label><?= $opt1 ?></label>
            <input type="radio" name="ans2" value="<?= $opt2 ?>"><label><?= $opt2 ?></label>
            <input type="radio" name="ans2" value="<?= $opt3 ?>"><label><?= $opt3 ?></label>
            <input type="radio" name="ans2" value="<?= $opt4 ?>"><label><?= $opt4 ?></label>

            <br>
            <span class="feedback"><?php echo $feedback2 ?></span>
        </div>

        <div class="question">
            <p class="q-title"> Aufgabe 3 (1P)</p>
            <p class="q1">
            <?php
            if ($_SESSION['newQuestions'] == true) {
                generate_numerical_1();
            }
            echo $_SESSION['question_numerical_1'] . "<br>";
            ?>
            </p>
            <input class="input-ans" type="number" name="ans3">

            <br>
            <span class="feedback"><?php echo $feedback3 ?></span>
        </div>


        <div class="question">
            <p class="q-title"> Aufgabe 4 (3P)</p>
            <p class="q1">
            <?php
            if ($_SESSION['newQuestions'] == true) {
                generate_matching_1();
            }
            echo $_SESSION['question_matching_1'] . "<br>"; ?>
            </p>

            <div class="dropBase">
                <input type="text" class="drag" id="drag1" draggable="true"
                       value="<?= $_SESSION['options_matching_1'][1][0] ?>" readonly></input>
                <input type="text" class="drag" id="drag2" draggable="true"
                       value="<?= $_SESSION['options_matching_1'][1][1] ?>" readonly></input>
                <input type="text" class="drag" id="drag3" draggable="true"
                       value="<?= $_SESSION['options_matching_1'][1][2] ?>" readonly></input>
            </div>


            <div class="matching">
                <div>
                    <div class="container"><?php echo $_SESSION['options_matching_1'][0][0] ?></div>
                    <br>
                    <div class="container"><?php echo $_SESSION['options_matching_1'][0][1] ?></div>
                    <br>
                    <div class="container"><?php echo $_SESSION['options_matching_1'][0][2] ?></div>
                </div>


                <div class="matchingRight">
                    <div type="text" name="ans4-1" class="dropContainer"></div>
                    <br>
                    <div type="text" name="ans4-2" class="dropContainer"></div>
                    <br>
                    <div type="text" name="ans4-3" class="dropContainer"></div>
                </div>

            </div>

            <br>
            <span class="feedback"><?php echo $feedback4 ?></span>
        </div>

        <div class="question">
            <p class="q-title"> Aufgabe 5 (2P)</p>
            <p class="q1">
            <?php
            if ($_SESSION['newQuestions'] == true) {
                generate_numerical_2();
            }
            echo $_SESSION['question_numerical_2'] . "<br>";
            ?>
            </p>
            <input type="number" name="ans5">

            <br>
            <span class="feedback"><?php echo $feedback5 ?></span>
        </div>

        <div class="question">
            <p class="q-title">  Aufgabe 6 (2P)</p>
            <p class="q1">
                <?php
            if ($_SESSION['newQuestions'] == true) {
                generate_multiplechoice_2();
            }
            echo $_SESSION['question_multiplechoice_2'] . "<br>";
            $opt1_2 = $_SESSION['options_multiplechoice_2'][0][0];
            $opt2_2 = $_SESSION['options_multiplechoice_2'][1][0];
            $opt3_2 = $_SESSION['options_multiplechoice_2'][2][0];
            $opt4_2 = $_SESSION['options_multiplechoice_2'][3][0];
            ?>
            </p>
            <input type="radio" name="ans6" value="<?= $opt1_2 ?>"><label><?= $opt1_2 ?></label>
            <input type="radio" name="ans6" value="<?= $opt2_2 ?>"><label><?= $opt2_2 ?></label>
            <input type="radio" name="ans6" value="<?= $opt3_2 ?>"><label><?= $opt3_2 ?></label>
            <input type="radio" name="ans6" value="<?= $opt4_2 ?>"><label><?= $opt4_2 ?></label>

            <br>
            <span class="feedback"><?php echo $feedback6 ?></span>
        </div>

        <div class="question">
            <p class="q-title"> Aufgabe 7 (1P)</p>
            <p class="q1">
            <?php
            if ($_SESSION['newQuestions'] == true) {
                generate_short_text();
            }
            echo $_SESSION['question_short_text_1'] . "<br>";
            ?>
            </p>
            <input class="input-ans" type="text" name="ans7">

            <br>
            <span class="feedback"><?php echo $feedback7 ?></span>
        </div>

        <div class="question">
            <p class="q-title"> Aufgabe 8 (1P)</p>
            <p class="q1">
            <?php
            if ($_SESSION['newQuestions'] == true) {
                generate_text_to_term();
            }
            echo $_SESSION['question_text_to_term'] . "<br>";
            ?>
            </p>
            <input class="input-ans" type="text" name="ans8">

            <br>
            <span class="feedback"><?php echo $feedback8 ?></span>
        </div>

        <br>
        <input class="btn abgeben" type="submit" name="check" value="Test abgeben">

    </form>
    <form action="test.php" method="post">
        <input class="btn neuer-test" type="submit" name="newTest" value="Neuer Test">
    </form>
</div>