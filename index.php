<DOCTYPE html>
<html>
<head>
<title>Mathe-Tiger</title>
</head>
<body>
<h1>Werde zum Mathetiger!</h1>
<p>
    Frage 1: Numerical <br>
    <?php
    session_start();
    include('questionGenerator.php');
    // call generate_numerical_1() to generate the first numerical question. Returns the question as a string.
    // !Please call each generate function only once per Session (i.e. don't ask the same question twice per test).
    echo generate_numerical_1() . "<br>";
    // call $_SESSION['solution_numerical_1'] to get the correct solution.
    echo "Solution 1 from session: " . $_SESSION['solution_numerical_1'] . "<br>"; ?>

    Frage 2: Multiple Choice<br>
    <?php
    // call generate_multiplechoice_1() to generate the first multiple choice question.
    // Returns an array with the question string and distractors array
    $question_2 = generate_multiplechoice_1();
    echo $question_2[0] . "<br>";
    echo "distractor 1: " . $question_2[1][0] . "<br>";
    echo "distractor 2: " . $question_2[1][1] . "<br>";
    // call $_SESSION['solution_multiplechoice_1'] to get the correct solution.
    echo "Solution 2 from session: " . $_SESSION['solution_multiplechoice_1'] . "<br>"; ?>
</p>
</body>
</html>
