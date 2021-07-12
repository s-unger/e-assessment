<?php
include('questionStrings.php');
$pointsTotal1 = 1;
$pointsTotal2 = 1;
$pointsTotal3 = 1;
$pointsTotal4 = 3;
$pointsTotal5 = 1;
$pointsTotal6 = 1;
$pointsTotal7 = 1;
$pointsTotal8 = 1;
$points1 = 0;
$points2 = 0;
$points3 = 0;
$points4 = 0;
$points5 = 0;
$points6 = 0;
$points7 = 0;
$pointsTotal = $pointsTotal1 + $pointsTotal2 + $pointsTotal3 + $pointsTotal4 + $pointsTotal5 + $pointsTotal6 +
    $pointsTotal7 + $pointsTotal8;
/**
 * Evaluates question 1
 * fullPoints is 0 if the question did not receive full points, and 1 if it did.
 * @return array int points, String feedback and int fullPoints
 */
function evaluateQuestion1(){
    global $q1;
    global $pointsTotal1;
    global $points1;
    $q1bool = $q1 == "true";
    ($q1bool) ? $truefalseGerman = "Wahr" : $truefalseGerman = "Falsch";
    $feedback1 = "Deine Antwort: \"$truefalseGerman\"<br>";
    if ($q1bool == $_SESSION['solution_truefalse_1']) {
        $points1 = $pointsTotal1;
        $fullPoints = 1;
        $feedback1 .= "<b>Richtig!</b>";
    } else {
        $feedback1 .= "<b>Leider falsch!</b> <br>" . $_SESSION['feedback_truefalse_1'];
        $fullPoints = 0;
    }
    return array("feedback"=>$feedback1, "fullPoints"=>$fullPoints);
}

/**
 * Evaluates question 2
 * fullPoints is 0 if the question did not receive full points, and 1 if it did.
 * @return array int points, String feedback and int fullPoints
 */
function evaluateQuestion2(){
    global $q2;
    global $pointsTotal2;
    global $points2;
    $feedback = "Deine Antwort: $q2<br>";
    if ($q2 == $_SESSION['solution_multiplechoice_1']) {
        $fullPoints = 1;
        $points2 = $pointsTotal2;
        $feedback .= "<b>Richtig!</b>";
    } else {
        $fullPoints = 0;
        if ($q2 == $_SESSION['solution_multiplechoice_1'] + 1 || $q2 == $_SESSION['solution_multiplechoice_1'] - 1) {
            $feedback .= "<b>Fast richtig!</b> Die richtige Antwort ist " . $_SESSION['solution_multiplechoice_1'] . ".";
        } else {
            $feedback .= "<b>Leider falsch!</b>  Die richtige Antwort ist " . $_SESSION['solution_multiplechoice_1'] . ".";
        }
    }
    return array("feedback"=>$feedback, "fullPoints"=>$fullPoints);
}

/**
 * Evaluates question 3
 * fullPoints is 0 if the question did not receive full points, and 1 if it did.
 * @return array int points, String feedback and int fullPoints
 */
function evaluateQuestion3(){
    global $q3;
    global $pointsTotal3;
    global $points3;
    $feedback3 = "Deine Antwort: $q3<br>";
    if ($q3 == $_SESSION['solution_numerical_1']) {
        $fullPoints = 1;
        $points3 = $pointsTotal3;
        $feedback3 .= "<b>Richtig!</b>";
    } else {
        $fullPoints = 0;
        if ($q3 == $_SESSION['solution_numerical_1'] + 1 || $q3 == $_SESSION['solution_numerical_1'] - 1) {
            $feedback3 .= "<b>Fast richtig!</b> Die richtige Antwort ist " . $_SESSION['solution_numerical_1'] . ".";
        } else {
            $feedback3 .= "<b>Leider falsch!</b>  Die richtige Antwort ist " . $_SESSION['solution_numerical_1'] . ".";
        }
    }
    return array("feedback"=>$feedback3, "fullPoints"=>$fullPoints);
}

/**
 * Evaluates question 4
 * fullPoints is 0 if the question did not receive full points, and 1 if it did.
 * @return array int points, String feedback and int fullPoints
 */
function evaluateQuestion4(){
    global $q41; global $q42; global $q43;
    global $points4;
    $feedback4 = "Deine Antwort: <br>$q41<br>$q42<br>$q43<br>";
    if ($q41 == $_SESSION['solution_matching_1'][0]) $points4++;
    if ($q42 == $_SESSION['solution_matching_1'][1]) $points4++;
    if ($q43 == $_SESSION['solution_matching_1'][2]) $points4++;
    $feedback4 .= "<b>" . $points4 . " von 3 richtig!</b>";
    if ($points4 <3) {
        $fullPoints = 0;
        $feedback4 .= "<br>Die richtige Reihenfolge ist: <br>" . $_SESSION['solution_matching_1'][0]
            ."<br>" . $_SESSION['solution_matching_1'][1]
            ."<br>" . $_SESSION['solution_matching_1'][2];
    } else $fullPoints = 1;
    return array("feedback"=>$feedback4, "fullPoints"=>$fullPoints);
}

/**
 * Evaluates question 5
 * fullPoints is 0 if the question did not receive full points, and 1 if it did.
 * @return array int points, String feedback, int fullPoints, int misconception
 */
function evaluateQuestion5(){
    global $q5;
    global $pointsTotal5;
    global $points5;
    $feedback5 = "Deine Antwort: $q5<br>";
    if ($q5 == $_SESSION['solution_numerical_2']) {
        $fullPoints = 1;
        $points5 = $pointsTotal5;
        $feedback5 .= "<b>Richtig!</b>";
    }  else {
        $fullPoints = 0;
        $feedback5 .= "<b>Leider falsch!</b> Die richtige Antwort ist " . $_SESSION['solution_numerical_2'] . ".";
    }
    $misconception = null;
    if ($q5 == $_SESSION['misc_carry1_numerical_2']) {
        /* Kein Übertrag: erweitert die Einerziffer des Minuenden korrekt, macht aber nicht den nötigen Übertrag zur Zehnerziffer des Subtrahenden.*/
        $feedback5 .= "<br>Beachte den <b>Zehnerübergang</b>! Nach der Erweiterung der Einerziffer des Minuenden findet 
ein <b>Übertrag</b> zur Zehnerziffer statt.";
        $misconception = 0;
    } else if ($q5 == $_SESSION['misc_carry2_numerical_2']) {
        /* Spaltenweise Unterschiedsbildung: Es wird immer die kleinere von der größeren Ziffer abgezogen */
        $feedback5 .= "<br>An der Einerstelle kann nicht einfach die kleinere von der größeren Ziffer abgezogen werden. 
Um an der Einerstelle Minus zu rechnen, benötigst du einen Übertrag in die Zehnerstelle.";
        $misconception = 1;
    } else if ($q5 == $_SESSION['misc_operator_numerical_2']){
        /* Fehlendes Textverständnis: Addition statt Subtraktion */
        $misconception = 2;
        $feedback5 .= "<br>Lies noch einmal genau den Aufgabentext! Es werden Bücher <b>weggenommen</b>.";
    }
    return array("feedback"=>$feedback5, "fullPoints"=>$fullPoints, "misconception"=>$misconception);
}

/**
 * Evaluates question 6
 * fullPoints is 0 if the question did not receive full points, and 1 if it did.
 * @return array int points, String feedback and int fullPoints
 */
function evaluateQuestion6(){
    global $q6;
    global $pointsTotal6;
    global $points6;
    $feedback6 = "Deine Antwort: $q6<br>";
    if ($q6 == $_SESSION['solution_multiplechoice_2']) {
        $fullPoints = 1;
        $points6 = $pointsTotal6;
        $feedback6 .= "<b>Richtig!</b>";
    }else {
        $fullPoints = 0;
        $feedback6 .= "<b>Leider falsch!</b> Die richtige Antwort ist " . $_SESSION['solution_multiplechoice_2'] . ".";
        for ($i = 0; $i < 4; $i++) {
            if ($q6 == $_SESSION['options_multiplechoice_2'][$i][0]) $feedback6 .= "<br>" . $_SESSION['options_multiplechoice_2'][$i][1];
        }
    }
    return array("feedback"=>$feedback6, "fullPoints"=>$fullPoints);
}

/**
 * Evaluates question 7
 * fullPoints is 0 if the question did not receive full points, and 1 if it did.
 * Ignores whitespace, upper/lower case
 * Accepts correct answer with spelling mistakes, gives feedback.
 * Gives feedback if an incorrect but existing term is entered.
 * @return array int points, String feedback and int fullPoints
 */
function evaluateQuestion7(){
    global $q7;
    global $pointsTotal7;
    global $points7;
    global $questionTerms7;
    global $solutionTerms7;
    global $configTerms7;
    //$percent = 0;
    $lowercaseSolutions = array_map('strtolower', $solutionTerms7);
    $lowercaseCorrectSolution = strtolower($_SESSION['solution_short_text_1']);
    $feedback7 = "Deine Antwort: $q7<br>";
    $ans7 = str_replace(' ', '', $q7);
    $lowercaseAns7 = strtolower($ans7);
    $similar = similar_text($lowercaseAns7, $lowercaseCorrectSolution, $percent);
    if($ans7 == $lowercaseCorrectSolution) {
        $fullPoints = 1;
        $points7 = $pointsTotal7;
        $feedback7 .= "Richtig!";
    } else if($percent > 79) {
        $fullPoints = 1;
        $points7 = $pointsTotal7;
        $feedback7 .= "<b>Richtig!</b> <br>Beachte die korrekte Schreibweise von \"" . $_SESSION['solution_short_text_1'] . "\".";
    } else {
        $fullPoints = 0;
        $feedback7 .= "<b>Leider falsch!</b> Die richtige Antwort ist " . $_SESSION['solution_short_text_1'] . ".";
        if (in_array(strtolower($ans7), $lowercaseSolutions)) {
            $i = array_search (strtolower($ans7), $lowercaseSolutions);
            $feedback7 .= "<br>$solutionTerms7 ist Fachbegriff für die $configTerms7[$i] bei einer $questionTerms7[i]";
        }
    }
    return array("feedback"=>$feedback7, "fullPoints"=>$fullPoints);
}


/** Evaluates question 8
 * fullPoints is 0 if the question did not receive full points, and 1 if it did.
 * @return array int points, String feedback and int fullPoints
 */
function evaluateQuestion8(){
    global $q8;
    global $pointsTotal8;
    global $points8;
    $feedback8 = "Deine Antwort: $q8<br>";
    $ans8 = str_replace(' ', '', $q8);
    if ($ans8 == $_SESSION['solution_text_to_term']) {
        $fullPoints = 1;
        $points8 = $pointsTotal8;
        $feedback8 .= "<b>Richtig!</b>";
    }else {
        $fullPoints = 0;
        $feedback8 .= "<b>Leider falsch!</b>  Die richtige Antwort ist " . $_SESSION['solution_text_to_term'] . ".";
    }
    return array("feedback"=>$feedback8, "fullPoints"=>$fullPoints);
}


/**
 * Evaluates the total points for an exam and calculates the grade
 * @return array int points and String feedback
 */
function evaluateTotal(){
    global $points1; global $points2; global $points3; global $points4; global $points5; global $points6; global $points7;
    global $pointsTotal;
    $correct = $points1 + $points2 + $points3 + $points4 + $points5 + $points6 + $points7;
    $feedbackTotal = "Deine erreichte Punktzahl: $correct von $pointsTotal";
    $grade = calculateGrade($correct);
    if ($_SESSION['isExam']) $feedbackTotal .= "<br>Deine Note: <b>$grade</b>";
    else {
        if ($correct == $pointsTotal) $feedbackTotal .= "<br>Perfekte Leistung. Weiter so!";
        elseif ($grade == 1) $feedbackTotal .= "<br>Sehr gute Leistung. Weiter so!";
        elseif ($grade == 2) $feedbackTotal .= "<br>Gute Leistung. Weiter so!";
        else $feedbackTotal .= "<br>Mach weiter, du schaffst das!";
    }
    return array("points"=>$pointsTotal, "feedback"=>$feedbackTotal);
}

/**
 * Calculate grade from points according to recommended point percentages.
 * Rounded down threshold, because the math tiger is a generous feline.
 * @param int $correct  points received for test
 * @return int  grade
 */
function calculateGrade(int $correct){
    global $pointsTotal;
    switch (true) {
        case ($correct >= floor(0.92 * $pointsTotal)):
            return 1;
        case ($correct >= floor(0.81 * $pointsTotal)):
            return 2;
        case ($correct >= floor(0.67 * $pointsTotal)):
            return 3;
        case ($correct >= floor(0.50 * $pointsTotal)):
            return 4;
        case ($correct >= floor(0.23 * $pointsTotal)):
            return 5;
        default:
            return 6;
    }
}

