<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Mathe Tiger</title>

</head>

<body>
<p>
    Frage 1<br>
    <?php
    session_start();
    include('questionGenerator.php');
    echo generate_question_numerical() . "<br>";
    echo "Solution 1 from session: " . $_SESSION['solution_1'] . "<br>"; ?>

    Frage 2<br>
    <?php
    $question_2 = generate_question_multiplechoice();
    echo $question_2[0] . "<br>";
    echo "distractor 1: " . $question_2[1][0] . "<br>";
    echo "distractor 2: " . $question_2[1][1] . "<br>";
    echo "Solution 2 from session: " . $_SESSION['solution_2'] . "<br>"; ?>
</p>
</body>
</html>
