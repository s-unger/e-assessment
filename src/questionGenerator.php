<?php
const number_space = 100;
include('questionStrings.php');

/**
 * Question 1
 * generates TRUE FALSE item
 * Topic: Terminology
 * Saves to session:    String question
 *                      bool solution
 */
function generate_truefalse_1(){
    $operators = ["Plus", "Minus", "Mal", "Geteilt"];
    $terms = ["Summe", "Differenz", "Produkt", "Quotient"];
    $rand_operator = mt_rand(0, 3);
    $x = $operators[$rand_operator];
    $solution = (mt_rand(0,1) == 0);
    $y = $terms[$rand_operator];
    if ($solution) {
        $feedback = "$y ist der korrekte Begriff für das Ergebnis einer $x Aufgabe.";
    } else {
        $feedback = "Das Ergebnis einer $x Aufgabe heißt $y.";
        do {$rand_term = mt_rand(0,3);} while ($rand_term == $rand_operator);
        $y = $terms[$rand_term];    //change term to incorrect term for false statement
    }
    $question = "Das Ergebnis einer $x Aufgabe heißt $y.";
    $_SESSION['question_truefalse_1'] = $question;
    $_SESSION['solution_truefalse_1'] = $solution;
    $_SESSION['feedback_truefalse_1'] = $feedback;
}


/**
 * Question 2
 * generates MULTIPLE CHOICE item
 * Topic: Subtraction without decimal carry
 * Prototype multiple choice question to mitigate testwiseness, but with less relevant distractors
 * Saves to session:    String question
 *                      int solution
 *                      array with 4 options
 */
function generate_multiplechoice_1(){
    // Generate subtraction with x in [11, 100], y in [1, 90], solution in [10, 99].
    // Keep solution in double digits to allow for swapping of digits
    do {
        $x = mt_rand(11, number_space);
        $y = mt_rand(1, ($x - 10));
    } while ($x%10 < $y%10);   // get subtraction without decimal carry
    $question = "$x - $y =";
    $solution = $x - $y;
    $_SESSION['question_multiplechoice_1'] = $question;
    $_SESSION['solution_multiplechoice_1'] = $solution;

    //Generate distractors and add to options
    $distractors = generate_distractors_1($x, $y);
    $options = $distractors;
    $options[] = $solution;
    shuffle($options);
    $_SESSION['options_multiplechoice_1'] = $options;
}


/**
 * Question 3
 * generates NUMERICAL item
 * Topic: Addition without digit carry
 * Saves to session:    String question
 *                      int solution
 */
function generate_numerical_1(){
    do {    // Keep the numbers in double digits
        $x = mt_rand(10, number_space - 10);
        $y = mt_rand(10, (number_space - $x));
    } while (($x%10 + $y%10) > 9);

    $question = "$x + $y =";
    $solution = $x + $y;
    $_SESSION['question_numerical_1'] = $question;
    $_SESSION['solution_numerical_1'] = $solution;
}


/**
 * Question 4
 * generates MATCHING item
 * Topic: Multiplication
 * Saves to session:    String question
 *                      array solution: the right column in the correct order
 *                      array options: contains two arrays, the fixed left column and the shuffled right column
 */
function generate_matching_1()
{
    $question = "Welche Terme haben das selbe Ergebnis?";
    $terms1 = array(); // fix
    $terms2 = array(); // to be rearranged by user
    $result_numbers = array();
    while (count($terms1) < 3){
        $x = mt_rand(8, 100);
        if (count(primefactor($x)) > 2 && !in_array($x, $result_numbers)) {   // Result number found with min. 3 prime factors, and hasn't been used yet
            $primefactors = primefactor($x);
            //shuffle($primefactors);
            $splitters = unique_random_numbers(1, count($primefactors)-1, 2);    // 2 unique values used to split the prime factors array in two places for each term
            $term1 = generate_matching_1_term($primefactors, $splitters[0]);
            $term2 = generate_matching_1_term($primefactors, $splitters[1]);
            if ($term1 != $term2){
                $terms1[] = $term1;
                $terms2[] = $term2;
                $result_numbers[] = $x;
            }
        }
    }
    $solution = $terms2;
    $_SESSION['solution_matching_1'] = $solution;

    //generate distractor terms
    $distractorTerms = generate_matching_1_distractor($result_numbers);
    $secondColumn = array_merge($terms2, $distractorTerms);

    shuffle($secondColumn);
    $options = array($terms1, $secondColumn);

    $_SESSION['question_matching_1'] = $question;
    $_SESSION['options_matching_1'] = $options;
}


/**
 * Question 5
 * generates NUMERICAL item 2 (text problem)
 * Topic: Subtraction without digit carry
 * Saves to session:    String question
 *                      int solution
 *                      int misc_carry1, wrong answer from common misconception (digit carry, +10)
 *                      int misc_carry2, wrong answer from common misconception (digit carry, swapped second digit)
 *                      int misc_operator, wrong answer from common misconception (wrong operator chosen from text)
 */
function generate_numerical_2(){
    global $actors;
    global $actors2;
    // Generate subtraction with decimal carry, with x in [21, 98], y in [12, 89], solution in [9, 79].
    // Keep minuend in double digit to keep the digit carry misconception realistic, i.e. smaller than the original subtrahend.
    do {
        $x = mt_rand(21, number_space);
        $y = mt_rand(10, ($x - 1));
    } while ($x%10 >= $y%10 || $x%10 == 0);
    //echo "x modulo 10 " . $x%10;
    //echo "y modulo 10 " . $y%10;
    $actor1 = $actors[mt_rand(0,count($actors)-1)][0];
    $actor2 = $actors2[mt_rand(0,count($actors)-1)];
    $question = "$actor1 hat $x Bücher zuhause. $actor2 leiht sich $y Bücher aus. Wie viele Bücher hat " . lcfirst($actor1) . " noch zuhause?";
    $solution = $x - $y;
    $misc_carry = $solution + 10;

    $first_x = (int)($x/10);
    $second_x = $x%10;
    $first_y = (int)($y/10);
    $second_y = $y%10;
    $misc_carry2 = 10*($first_x - $first_y) + $second_y - $second_x;

    $misc_operator = $x + $y;

    $_SESSION['question_numerical_2'] = $question;
    $_SESSION['solution_numerical_2'] = $solution;
    $_SESSION['misc_carry1_numerical_2'] = $misc_carry;         // misconception: 43 - 27 = 26
    $_SESSION['misc_carry2_numerical_2'] = $misc_carry2;        // misconception: 43 - 27 = 24
    $_SESSION['misc_operator_numerical_2'] = $misc_operator;    // misconception: 43 + 27 = 70, addition instead of subtraction
}


/**
 * Question 6
 * generates MULTIPLE CHOICE item (text problem)
 * Topic: Addition with decimal carry
 * Prototype multiple choice question with relevant distractors (common misconceptions) but prone to testwiseness
 * Saves to session:    String question
 *                      int solution
 *                      array options: 3 options made of tuples: int answer option and String feedback to be given if option is chosen
 */
function generate_multiplechoice_2(){
    global $actors;
    global $actors2;
    do {
        $x = mt_rand(10, number_space - 10);
        $y = mt_rand(10, (number_space - $x));
    } while  (($x%10 + $y%10) < 11);     //generate addition with digit carry
    $actor = mt_rand(0,count($actors)-1);
    $question = $actors[$actor][0] . " hat $x Streifen am Kopf und $y Streifen am Körper. Wie viele Streifen hat " . $actors[$actor][1] . " insgesamt?";
    $solution = $x + $y;
    $_SESSION['question_multiplechoice_2'] = $question;
    $_SESSION['solution_multiplechoice_2'] = $solution;

    //Generate distractors and add to options
    $distractors = generate_distractors_2($x, $y);
    $options = $distractors;
    $options[] = array($solution, "");
    shuffle($options);
    $_SESSION['options_multiplechoice_2'] = $options;
}


/**
 * Question 7
 * generates Short Text item
 * Topic: Terminology
 * Saves to session:    String question
 *                      String solution
 */
function generate_short_text() {
    global $questionTerms7;
    global $solutionTerms7;
    global $configTerms7;
    $rand = mt_rand(0, count($questionTerms7)-1);
    $x = $questionTerms7[$rand];
    $z = $configTerms7[$rand];
    $solution = $solutionTerms7[$rand];
    $question = "Nenne den Fachbegriff für $z in einer $x.";
    $_SESSION['question_short_text_1'] = $question;
    $_SESSION['solution_short_text_1'] = $solution;
}


/**
 * Question 8
 * generates Short Text item
 * Saves to session:    String question
 *                      String solution
 */
function generate_text_to_term(){
    $x = mt_rand(0, 50);
    $y = mt_rand(51, 100);

    $question = "Ziehe $x von $y ab.";
    $solution = "$y-$x";
    $misconception1 = "$x-$y";
    $misconception2 = array("$x+$y", "$y+$x", "$x:$y", "$y:$x", "$x*$y", "$y*$y");
    $_SESSION['misc1_text_to_term'] = $misconception1;
    $_SESSION['misc2_text_to_term'] = $misconception2;
    $_SESSION['question_text_to_term'] = $question;
    $_SESSION['solution_text_to_term'] = $solution;
}


/**
 * Generates distractors for multiplechoice_1. Randomly picked from a larger pool of distractors
 * in order to mitigate test-wiseness.
 * @param $x int the minuend
 * @param $y int the subtrahend
 * @return array of 3 distractors
 */
function generate_distractors_1(int $x, int $y): array{
    // Generate distractors. 1. Close to the solution 2. Swapped digits
    // Remove obviously wrong options: number > 100 and (< 10 ?)
    // Save 3 random ones and the solution (shuffled) in the options array
    $solution = $x - $y;
    $distractor_1 = $solution + 1;
    $distractor_2 = $solution - 1;
    $distractor_3 = $solution - 2;
    $distractor_4 = $solution + 2;
    $distractor_5 = $solution - 3;
    $distractor_6 = $solution + 3;
    $distractors_close = array ($distractor_1, $distractor_2, $distractor_3, $distractor_4, $distractor_5, $distractor_6);

    // Create distractors from close distractors by swapping digits
    $distractors_swapped = array();
    foreach ($distractors_close as $dis){
            $distractors_swapped[] = swap_digits($dis);
    }
    $distractors = array_unique(array_merge($distractors_close, $distractors_swapped), SORT_REGULAR);
    $distractors = array_values(array_filter($distractors, "filter_distractors"));
    return array_rand(array_flip($distractors), 3);
    /*
     * shuffle($distractors);
    $options = array_slice($distractors, 0, 3);
    }*/
}


/**
 * Generates three distractors for the MC question 2, including feedback to be given if the distractor is chosen.
 * Distractors are common misconceptions:
 * - Off by one error: (solution +-1). Only + or - to mitigate testwiseness
 * - Wrong operator used because they misunderstood the text problem: Minus instead of plus
 * - Digit carry isn't applied to tens digit (solution - 10)
 * Problem: Distractor pattern can be analyzed and understood by students.
 * @param $x int addend 1
 * @param $y int addend 2
 * @return array of 3 distractors made of tuples: distractor and feedback
 */
function generate_distractors_2(int $x, int $y): array{
    $solution = $x + $y;
    (mt_rand(0,1) == 0) ? $distractor_1 = $solution+1 : $distractor_1 = $solution-1;            //Off by one error. Only + or - to mitigate testwiseness
    $distractor_2 = abs($x - $y);     //Wrong operator. Check if this can be == digit carry distractor
    $distractor_3 = $solution - 10;         //Digit carry isn't applied to tens digit
    //$distractor_4 = $solution - 9;          //Digit carry is applied to the wrong digit (Ones digit)
    $feedback_1 = "Fast richtig!";
    $feedback_2 = "Lies noch einmal genau den Aufgabentext! Die Streifen werden <b>zusammengezählt</b>";
    $feedback_3 = "Beachte den <b>Zehnerübergang</b>! Nach der Erweiterung der Einerstelle findet ein <b>Übertrag</b> in die Zehnerstelle statt.";

    $distractors = array(array($distractor_1, $feedback_1), array($distractor_2, $feedback_2), array($distractor_3, $feedback_3));
    return $distractors;
}


/**
 * Callback function for distractor fitering. Filters distractors that are null, > 100, or the solution.
 * @param $dis array with all distractors
 * @return bool true if successful
 */
function filter_distractors($dis) : bool{
        if ($dis == null || $dis > 100 || $dis == $_SESSION['solution_multiplechoice_1']) {
            return false;
        } else return true;
}


/**
 * Swaps digits for a two digit number. Used to create distractors.
 * @param $n int the original number
 * @return int|null the number with swapped digits or null if the number is not 2 digits, the two digits are the same or the second digit is zero
 */
function swap_digits(int $n){
    if ($n < 10 || $n > 99 || $n%10 == 0 || (int)($n/10) == $n%10) return null;
    $first = (int)($n/10);
    $second = $n%10;
    return $second*10+$first;
}


/**
 * Generates a distractor term that does not result in a number that the left column terms result in.
 * Used to make guessing the correct matches more difficult.
 * @param $result_numbers array with the already used correct product results
 * @return array 2 distractor terms
 */
function generate_matching_1_distractor(array $result_numbers): array {
    $distractorTerms = array();
    while (count($distractorTerms) < 2){
        $x = mt_rand(18, 100);
        if (!in_array($x, $result_numbers)) {
            $factor1 = mt_rand(2, 9);
            $factor2 = floor($x / $factor1);
            if (mt_rand(0, 1) == 0) {
                $distractor = "$factor1 x $factor2";
            } else $distractor = "$factor2 x $factor1";
            $distractorTerms[] = $distractor;
        }
    }
    return $distractorTerms;
}


/**
 * Generates a term (such as "3 x 5") for a matching item
 * @param $primefactors array containing the prime factors of the result number
 * @param $splitter int where the primefactor array will be split to generate the two factors for the term
 * @return string the term
 */
function generate_matching_1_term(array $primefactors, int $splitter): string{
    $primes1 = array_slice($primefactors, 0, $splitter);   // get primefactors for first factor for term
    $factor1 = array_product($primes1);
    $primes2 = array_slice($primefactors, $splitter);   // get primefactors for second factor for term
    $factor2 = array_product($primes2);
    if (mt_rand(0,1) == 0){
        return "$factor1 x $factor2";
    } else return "$factor2 x $factor1";
}


/**
 * Generates unique random numbers.
 * @param $min int the minimum the numbers should have
 * @param $max int the maximum the numbers should have
 * @param $amount int the number of random numbers to be returned
 * @return array containing the unique random numbers
 */
function unique_random_numbers(int $min, int $max, int $amount): array{
    $random_numbers = array();
    $n = range($min,$max);
    shuffle($n);
    for ($i=0; $i< $amount; $i++) {
        $random_numbers[] = $n[$i];
    }
    return $random_numbers;
}


/**
 * Factorizes a number into its prime numbers
 * @param $num int the number to be factorized
 * @return array|int[] the prime numbers
 */
function primefactor(int $num): array{
    $sqrt = sqrt($num);
    for ($i = 2; $i <= $sqrt; $i++) {
        if ($num % $i == 0) {
            return array_merge(primefactor($num/$i), array($i));
        }
    }
    return array($num);
}