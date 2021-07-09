<?php if(!isset($_SESSION)) session_start();
$pdo = new PDO('mysql:host=localhost;dbname=e-assessment_db', 'e-assessment_user', 'topsecretdbpass');
$feedback1 = "";
$feedback2 = "";
$feedback3 = "";
$feedback4 = "";
$feedback5 = "";
$feedback6 = "";
$feedback7 = "";
$feedbackTotal = "";
$_SESSION['isSubmittable'] = true;
include('questionGenerator.php');
include('questionEvaluator.php');
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
$q1 = "";
$q2 = "";
$q3 = "";
$q41 = "";
$q42 = "";
$q43 = "";
$q5 = "";
$q6 = "";
$q7 = "";
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

$correct = 0;
if ($q1 == "" || $q2 == "" || $q3 == "" || $q41 == "" || $q42 == "" || $q43 == "" || $q5 == "" || $q6 == "" || $q7 == "") {
    $feedbackTotal = "Bitte alle Fragen beantworten.";
} else {
    $_SESSION['isSubmittable'] = false;
    $table = $_SESSION['isExam'] ? "exam_answers" : "answers";
    $statement = $pdo->prepare("INSERT INTO $table (userId, questionId, correctness) VALUES (:userId, :questionId, :correctness)");

    /* Evaluate question 1 */
    $evaluation1 = evaluateQuestion1();
    $points1 = $evaluation1["points"];
    $feedback1 = $evaluation1["feedback"];
    $result = $statement->execute(array('userId' => $_SESSION['userid'], 'questionId' => 0, 'correctness' => $points1));

    /* Evaluate question 2 */
    $evaluation2 = evaluateQuestion2();
    $points2 = $evaluation2["points"];
    $feedback2 = $evaluation2["feedback"];
    $result = $statement->execute(array('userId' => $_SESSION['userid'], 'questionId' => 1, 'correctness' => $points2));

    /* Evaluate question 3 */
    $evaluation3 = evaluateQuestion3();
    $points3 = $evaluation3["points"];
    $feedback3 = $evaluation3["feedback"];
    $result = $statement->execute(array('userId' => $_SESSION['userid'], 'questionId' => 2, 'correctness' => $points3));

    /* Evaluate question 4 */
    $evaluation4 = evaluateQuestion4();
    $points4 = $evaluation4["points"];
    $feedback4 = $evaluation4["feedback"];
    $result = $statement->execute(array('userId' => $_SESSION['userid'], 'questionId' => 3, 'correctness' => $points4));

    /* Evaluate question 5 */
    $evaluation5 = evaluateQuestion5();
    $points5 = $evaluation5["points"];
    $feedback5 = $evaluation5["feedback"];
    $result = $statement->execute(array('userId' => $_SESSION['userid'], 'questionId' => 4, 'correctness' => $points5));

    /* Evaluate question 6 */
    $evaluation6 = evaluateQuestion6();
    $points6 = $evaluation6["points"];
    $feedback6 = $evaluation6["feedback"];
    $result = $statement->execute(array('userId' => $_SESSION['userid'], 'questionId' => 5, 'correctness' => $points6));

    /* Evaluate question 7 */
    $evaluation7 = evaluateQuestion7();
    $points7 = $evaluation7["points"];
    $feedback7= $evaluation7["feedback"];
    $result = $statement->execute(array('userId' => $_SESSION['userid'], 'questionId' => 6, 'correctness' => $points7));

    /* Evaluate Total */
    $evaluationTotal = evaluateTotal();
    $pointsTotal = $evaluationTotal["points"];
    $feedbackTotal= $evaluationTotal["feedback"];
}

?>
<?php
if ($_POST) {
    if (isset($_POST['check'])) {
        check();
    } elseif (isset($_POST['newTest'])) {
        newTest();
    } elseif (isset($_POST['newExam'])) {
        newExam();

    }
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
        <br>
        <?php if ($_SESSION['isSubmittable'] == true) : ?>
        <input class="btn abgeben" type="submit" name="check" value="Test abgeben">
        <?php  endif; ?>

    </form>

    <?php if ($_SESSION['isExam'] == false) : ?>
         <form action="test.php" method="post">
            <input class="btn neuer-test" type="submit" name="newTest" value="Neuer Test">
         </form>
    <?php  endif; ?>


</div>