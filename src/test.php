<?php session_start();
$feedback1 = "";
$feedback2 = "";
$feedback3 = "";
$feedback4 = "";
$feedback7 = "";
$feedbackTotal = "";
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
$q1 = "";
$q2 = "";
$q3 = "";
$q41 = "";
$q42 = "";
$q43 = "";
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
if (isset($_POST['ans7'])) {
    $q7 = $_POST['ans7'];
}

$correct = 0;

if ($q1 == "" || $q2 == "" || $q3 == "" || $q41 == "" || $q42 == "" || $q43 == "" || $q7 == "") {
    $feedbackTotal = "Bitte alle Fragen beantworten.";
} else {
    if ($q1 == $_SESSION['solution_truefalse_1'] ? 'true' : 'false') {
        $correct++;
        $feedback1 = "richtig!";
    } else {
        $feedback1 = "leider falsch!";
    }

    if ($q2 == $_SESSION['solution_multiplechoice_1']) {
        $correct++;
        $feedback2 = "richtig!";
    } else {
        $feedback2 = "leider falsch!";
    }

    if ($q3 == $_SESSION['solution_numerical_1']) {
        $correct++;
        $feedback3 = "richtig!";
    } else {
        $feedback3 = "leider falsch!";
    }
    if ($q41 == $_SESSION['solution_matching_1'][0] && $q42 == $_SESSION['solution_matching_1'][1] && $q43 == $_SESSION['solution_matching_1'][2]) {
        $correct++;
        $feedback4 = "richtig!";
    } else {
        $feedback4 = "leider falsch!";
    }

    if($q7 == $_SESSION['solution_short_text_1']) {
        $correct++;
        $feedback7="richtig!";
    } else {
        $feedback7 = "leider falsch!";
    }

    $feedbackTotal = "Deine erreichte Punktzahl: " . $correct;
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

        <div>
            <p> Aufgabe 1:</p>
            <?php
            if ($_SESSION['newQuestions'] == true) {
                generate_truefalse_1();
            }
            echo $_SESSION['question_truefalse_1'] . "<br>";
            ?>
            <input type="radio" name="ans1" value="true"><label>Richtig</label>
            <input type="radio" name="ans1" value="false"><label>Falsch</label>

            <br>
            <span class="feedback"><?php echo $feedback1 ?></span>
        </div>

        <div>
            <p> Aufgabe 2:</p>
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
            <input type="radio" name="ans2" value="<?= $opt1 ?>"><label><?= $opt1 ?></label>
            <input type="radio" name="ans2" value="<?= $opt2 ?>"><label><?= $opt2 ?></label>
            <input type="radio" name="ans2" value="<?= $opt3 ?>"><label><?= $opt3 ?></label>
            <input type="radio" name="ans2" value="<?= $opt4 ?>"><label><?= $opt4 ?></label>

            <br>
            <span class="feedback"><?php echo $feedback2 ?></span>
        </div>

        <div>
            <p> Aufgabe 3:</p>
            <?php
            if ($_SESSION['newQuestions'] == true) {
                generate_numerical_1();
            }
            echo $_SESSION['question_numerical_1'] . "<br>";
            ?>
            <input type="number" name="ans3">

            <br>
            <span class="feedback"><?php echo $feedback3 ?></span>
        </div>


        <div>
            <p> Aufgabe 4:</p>
            <?php
            if ($_SESSION['newQuestions'] == true) {
                generate_matching_1();
            }
            echo $_SESSION['question_matching_1'] . "<br>"; ?>

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


        <div>
            <p> Aufgabe 7:</p>
            <?php
            if ($_SESSION['newQuestions'] == true) {
                generate_short_text();
            }
            echo $_SESSION['question_short_text_1'] . "<br>";
            ?>
            <input type="text" name="ans7">

            <br>
            <span class="feedback"><?php echo $feedback7 ?></span>
        </div>

            <br>
            <span class="feedback"><?php echo $feedback4 ?></span>
        </div>
        <br>
        <input type="submit" name="check" value="Test abgeben">

    </form>
    <form action="test.php" method="post">
        <input type="submit" name="newTest" value="Neuer Test">
    </form>
</div>