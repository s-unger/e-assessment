<?php
/**
 * Full points achievable for each question
 */

$pointsTotalPerQuestion = array(1,1,1,3,1,1,1,1);
$pointsTotal = array_sum($pointsTotalPerQuestion);

/**
 * Achieved points for each question. Set during evaluation.
 */
$points = array(0,0,0,0,0,0,0,0);

/**
 * Evaluates question 1
 * fullPoints is 0 if the question did not receive full points, and 1 if it did.
 * @return array int points, String feedback and int fullPoints
 */
function evaluateQuestion1(): array{
    global $q1;
    global $pointsTotalPerQuestion;
    global $points;
    $q1bool = $q1 == "true";
    ($q1bool) ? $truefalseGerman = "Wahr" : $truefalseGerman = "Falsch";
    $feedback1 = "Deine Antwort: \"$truefalseGerman\"<br>";
    if ($q1bool == $_SESSION['solution_truefalse_1']) {
        $points[0] = $pointsTotalPerQuestion[0];
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
function evaluateQuestion2(): array{
    global $q2;
    global $pointsTotalPerQuestion;
    global $points;
    $feedback = "Deine Antwort: $q2<br>";
    if ($q2 == $_SESSION['solution_multiplechoice_1']) {
        $fullPoints = 1;
        $points[1] = $pointsTotalPerQuestion[1];
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
function evaluateQuestion3(): array{
    global $q3;
    global $pointsTotalPerQuestion;
    global $points;
    $feedback3 = "Deine Antwort: $q3<br>";
    if ($q3 == $_SESSION['solution_numerical_1']) {
        $fullPoints = 1;
        $points[2] = $pointsTotalPerQuestion[2];
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
function evaluateQuestion4(): array{
    global $q41;
    global $q42;
    global $q43;
    global $points;
    $feedback4 = "Deine Antwort: <br>$q41<br>$q42<br>$q43<br>";
    if ($q41 == $_SESSION['solution_matching_1'][0]) $points[3]++;
    if ($q42 == $_SESSION['solution_matching_1'][1]) $points[3]++;
    if ($q43 == $_SESSION['solution_matching_1'][2]) $points[3]++;
    $feedback4 .= "<b>" . $points[3] . " von 3 richtig!</b>";
    if ($points[3] < 3) {
        $fullPoints = 0;
        $feedback4 .= "<br>Die richtige Reihenfolge ist: <br>" . $_SESSION['solution_matching_1'][0]
            . "<br>" . $_SESSION['solution_matching_1'][1]
            . "<br>" . $_SESSION['solution_matching_1'][2];
    } else $fullPoints = 1;
    return array("feedback" => $feedback4, "fullPoints" => $fullPoints);
}

/**
 * Evaluates question 5
 * fullPoints is 0 if the question did not receive full points, and 1 if it did.
 * @return array int points, String feedback, int fullPoints, int misconception
 */
function evaluateQuestion5(): array{
    global $q5;
    global $pointsTotalPerQuestion;
    global $points;
    $feedback5 = "Deine Antwort: $q5<br>";
    if ($q5 == $_SESSION['solution_numerical_2']) {
        $fullPoints = 1;
        $points[4] = $pointsTotalPerQuestion[4];
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
function evaluateQuestion6(): array{
    global $q6;
    global $pointsTotalPerQuestion;
    global $points;
    $feedback6 = "Deine Antwort: $q6<br>";
    if ($q6 == $_SESSION['solution_multiplechoice_2']) {
        $fullPoints = 1;
        $points[5] = $pointsTotalPerQuestion[5];
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
function evaluateQuestion7() : array{
    global $q7;
    global $pointsTotalPerQuestion;
    global $points;
    global $questionTerms7;
    global $solutionTerms7;
    global $configTerms7;
    $lowercaseSolutions = array_map('strtolower', $solutionTerms7);
    $lowercaseCorrectSolution = strtolower($_SESSION['solution_short_text_1']);
    $feedback7 = "Deine Antwort: $q7<br>";
    $ans7 = str_replace(' ', '', $q7);
    $lowercaseAns7 = strtolower($ans7);
    $similar = similar_text($lowercaseAns7, $lowercaseCorrectSolution, $percent);
    if($lowercaseAns7 == $lowercaseCorrectSolution) {
        $fullPoints = 1;
        $points[6] = $pointsTotalPerQuestion[6];
        $feedback7 .= "<b>Richtig!</b>";
    } else if($percent > 79) {
        $fullPoints = 1;
        $points[6] = $pointsTotalPerQuestion[6];
        $feedback7 .= "<b>Richtig!</b> <br>Beachte die korrekte Schreibweise von \"" . $_SESSION['solution_short_text_1'] . "\".";
    } else {
        $fullPoints = 0;
        $feedback7 .= "<b>Leider falsch!</b> Die richtige Antwort ist " . $_SESSION['solution_short_text_1'] . ".";
        if (in_array(strtolower($ans7), $lowercaseSolutions)) {
            $i = array_search (strtolower($ans7), $lowercaseSolutions);
            $feedback7 .= "<br>$solutionTerms7[$i] ist der Fachbegriff für $configTerms7[$i] bei einer $questionTerms7[$i].";
        }
    }
    return array("feedback"=>$feedback7, "fullPoints"=>$fullPoints);
}


/** Evaluates question 8
 * fullPoints is 0 if the question did not receive full points, and 1 if it did.
 * @return array int points, String feedback and int fullPoints
 */
function evaluateQuestion8(): array{
    global $q8;
    global $pointsTotalPerQuestion;
    global $points;
    $feedback8 = "Deine Antwort: $q8<br>";
    $ans8 = str_replace(' ', '', $q8);
    if ($ans8 == $_SESSION['solution_text_to_term']) {
        $fullPoints = 1;
        $points[7] = $pointsTotalPerQuestion[7];
        $feedback8 .= "<b>Richtig!</b>";
    }else {
        $fullPoints = 0;
        $feedback8 .= "<b>Leider falsch!</b>  Die richtige Antwort ist " . $_SESSION['solution_text_to_term'] . ".";
        if ($ans8 == $_SESSION['misc1_text_to_term']){
            $feedback8 .= "<br>Beachte die Reihenfolge der Zahlen im Term. Welche Zahl wird als erstes geschrieben?";
        } else if(in_array($ans8, $_SESSION['misc2_text_to_term'] )){
            $feedback8 .= "<br>Überlege, welchen Operator du für diesen Term benötigst.";
        }
    }
    return array("feedback"=>$feedback8, "fullPoints"=>$fullPoints);
}


/**
 * Evaluates the total points for an exam and calculates the grade
 * @return array int points and String feedback
 */
function evaluateTotal(): array{
    global $pointsTotal;
    global $pointsTotalPerQuestion;
    global $points;
    $pointsTotalAchieved = array_sum($points);
    $feedbackTotal = "Deine erreichte Punktzahl: $pointsTotalAchieved von $pointsTotal";
    $grade = calculateGrade($pointsTotalAchieved);
    if ($_SESSION['isExam']) $feedbackTotal .= "<br>Deine Note: <b>$grade</b>";
    else {
        if ($pointsTotalAchieved == $pointsTotal) $feedbackTotal .= "<br>Perfekte Leistung. Weiter so!";
        elseif ($grade == 1) $feedbackTotal .= "<br>Sehr gute Leistung. Weiter so!";
        elseif ($grade == 2) $feedbackTotal .= "<br>Gute Leistung. Weiter so!";
        else $feedbackTotal .= "<br>Mach weiter, du schaffst das!";
    }
    $fullPointsTotal = 0; //number of questions that received full points
    for($i=0; $i<count($points); $i++){
        if($points[$i] == $pointsTotalPerQuestion[$i]) $fullPointsTotal++;
    }
    return array("feedback"=>$feedbackTotal, "fullPoints"=>$fullPointsTotal);
}

/**
 * Calculate grade from points according to recommended point percentages.
 * Rounded down threshold, because the math tiger is a generous feline.
 * @param int $correct  points received for test
 * @return int grade
 */
function calculateGrade(int $correct): int{
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
