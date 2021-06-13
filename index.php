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
    // call generate_numerical_1() to generate the first numerical question.
    // !Please call each generate function only once per Session (i.e. don't ask the same question twice per test).
    generate_numerical_1();
    echo $_SESSION['question_numerical_1'] . "<br>";

    // call $_SESSION['solution_numerical_1'] to get the correct solution.
    echo "Solution 1 from session: " . $_SESSION['solution_numerical_1'] . "<br>"; ?>
    <br>

    Frage 2: Multiple Choice<br>
    <?php
    // call generate_multiplechoice_1() to generate the first multiple choice question.
    $question_2 = generate_multiplechoice_1();
    echo $_SESSION['question_multiplechoice_1'] . "<br>";
    echo "option 1: " . $_SESSION['options_multiplechoice_1'][0] . "<br>";
    echo "option 2: " . $_SESSION['options_multiplechoice_1'][1] . "<br>";
    echo "option 3: " . $_SESSION['options_multiplechoice_1'][2] . "<br>";
    echo "option 4: " . $_SESSION['options_multiplechoice_1'][3] . "<br>";

    // call $_SESSION['solution_multiplechoice_1'] to get the correct solution.
    echo "Solution 2 from session: " . $_SESSION['solution_multiplechoice_1'] . "<br>"; ?>
    <br>


    Frage 3: True or False <br>
    <?php
    generate_truefalse_1();
    echo $_SESSION['question_truefalse_1'] . "<br>";
    echo "Solution: " . json_encode($_SESSION['solution_truefalse_1']) . "<br>";?>
    <br>

    Frage 4: Matching (Mock-Version, not randomized yet)<br>
    <?php
    $question_4 = generate_matching_1();
    echo $_SESSION['question_matching_1'] . "<br>";
    echo "Options column 1 (fixed order): <br>" . $_SESSION['options_matching_1'][0][0] . "<br>" . $_SESSION['options_matching_1'][0][1] . "<br>" . $_SESSION['options_matching_1'][0][2] . "<br>";
    echo "Options column 2 (user needs to rearrange these to match column 1): <br>" . $_SESSION['options_matching_1'][1][0] . "<br>". $_SESSION['options_matching_1'][1][1] . "<br>". $_SESSION['options_matching_1'][1][2] . "<br>";

    echo "Solution 4 (column 2 in the correct order): <br>" . $_SESSION['solution_matching_1'][0] . "<br>". $_SESSION['solution_matching_1'][1] . "<br>". $_SESSION['solution_matching_1'][2] . "<br>"; ?>
    <br>

    <?php
    generate_matching_2();
    ?>






</p>
</body>
</html>
