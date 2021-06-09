<?php

/** NUMERICAL
 * Thema: Addition im Zahlenbereich 1 - 20
 * @return question string
 */
function generate_numerical_1(){
    $x = rand(1, 19);
    $y = rand(1, (20 - $x));
    $question = 'Der Tiger Yuki hat ' . $x . ' Streifen am Kopf und ' . $y . ' Streifen am Körper. Wie viele Streifen hat er insgesamt?';
    $solution = $x + $y;
    $_SESSION['question_numerical_1'] = $question;
    $_SESSION['solution_numerical_1'] = $solution;
    return $question;
}

/** MULTIPLE CHOICE
 * Thema: Subtraktion im Zahlenbereich 1 - 20
 * @return array with question string and distractors array
 */
function generate_multiplechoice_1(){
    $x = rand(2, 20);
    $y = rand(1, ($x - 1));
    $question = 'Der Tiger Yuki hat ' . $x . ' Schokoriegel. Er gibt seinem Freund dem Löwen ' . $y . ' Schokoriegel davon.
    Wie viele Schokoriegel hat Yuki noch?';
    $solution = $x - $y;
    $distractor_1 = $solution + 1;
    $distractor_2 = $solution - 1;
    $distractors = array ($distractor_1, $distractor_2);
    $_SESSION['question_multiplechoice_1'] = $question;
    $_SESSION['distractors_multiplechoice_1'] = $distractors;
    $_SESSION['solution_multiplechoice_1'] = $solution;
    return array($question, $distractors);
}

/** TRUE FALSE
 * @return question string
 */
function generate_truefalse_1(){

}

