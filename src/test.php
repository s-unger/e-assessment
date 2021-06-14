<?php session_start();
include('questionGenerator.php'); ?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php
    /*$q1 = $_POST['ans1']; TODO test evaluation
    $q2 = $_POST['ans2'];
    $q3 = $_POST['ans3'][0];
    $correct = 0;

    if ($q1 == "" || $q2 == "" || $q3 == "") {
        echo '<h2>Please answer all questions!</h2>';
    } else {
        if ($q1 == $_SESSION['solution_truefalse_1']) {
            $correct++;
        } else {
            echo '<p>Wrong answer!</p>';
        }

        if ($q2 == $_SESSION['solution_multiplechoice_1']) {
            $correct++;
        } else {
            echo '<p>Wrong answer!</p>';
        }

        if ($q3 == $_SESSION['solution_numerical_1']) {
            $correct++;
        } else {
            echo '<p>Wrong answer!</p>';
        }
        echo $correct;
    }*/
    ?>
</head>
<body>
<form action="test.php" method="post">
    <div class="q-group">
        <p class="question"> Aufgabe 1</p>
        <?php
        generate_truefalse_1();
        echo $_SESSION['question_truefalse_1'] . "<br>";
        ?>
        <input type="radio" name="ans1" value="true"><label>Richtig</label>
        <input type="radio" name="ans1" value="false"><label>Falsch</label>
    </div>

    <div class="q-group">
        <p class="question"> Aufgabe 2</p>
        <?php
        generate_multiplechoice_1();
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
    </div>

    <div class="q-group">
        <p class="question"> Aufgabe 3</p>
        <?php
        generate_numerical_1();
        echo $_SESSION['question_numerical_1'] . "<br>";
        ?>
        <input type="number" name="ans3">
    </div>

    <div class="q-group">
        <p class="question"> Aufgabe 4</p>
        <?php
        $question_4 = generate_matching_1();
        echo $_SESSION['question_matching_1'] . "<br>";
        echo "Options column 1 (fixed order): <br>" . $_SESSION['options_matching_1'][0][0] . "<br>" . $_SESSION['options_matching_1'][0][1] . "<br>" . $_SESSION['options_matching_1'][0][2] . "<br>";
        echo "Options column 2 (user needs to rearrange these to match column 1): <br>" . $_SESSION['options_matching_1'][1][0] . "<br>" . $_SESSION['options_matching_1'][1][1] . "<br>" . $_SESSION['options_matching_1'][1][2] . "<br>";
        echo "Solution 4 (column 2 in the correct order): <br>" . $_SESSION['solution_matching_1'][0] . "<br>" . $_SESSION['solution_matching_1'][1] . "<br>" . $_SESSION['solution_matching_1'][2] . "<br>"; ?>
        <br>
    </div>

    <input type="submit">
</form>
</body>
</html>