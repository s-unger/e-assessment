<?php
const number_space = 100;


/**
 * NUMERICAL
 * @return String question
 */
function generate_numerical_1(){
    $x = mt_rand(1, number_space - 1);
    $y = mt_rand(1, (number_space - $x));
    $question = 'Der Tiger Yuki hat ' . $x . ' Streifen am Kopf und ' . $y .
        ' Streifen am Körper. Wie viele Streifen hat er insgesamt?';
    $solution = $x + $y;
    $_SESSION['question_numerical_1'] = $question;
    $_SESSION['solution_numerical_1'] = $solution;
    return $question;
}

/**
 * MULTIPLE CHOICE
 * @return array with question string and options array with 4 options
 */
function generate_multiplechoice_1(){
    // Generate subtraction with x in [11, 100], y in [1, 90], solution in [10, 99].
    // Keep solution in double digits to allow for swapping of digits
    $x = mt_rand(11, number_space);
    $y = mt_rand(1, ($x - 10));
    $question = "$x - $y";
    $solution = $x - $y;
    $_SESSION['question_multiplechoice_1'] = $question;
    $_SESSION['solution_multiplechoice_1'] = $solution;

    //Generate distractors and add to options
    $distractors = generate_distractors_mc($x, $y);
    $options = $distractors;
    $options[] = $solution;
    shuffle($options);
    $_SESSION['options_multiplechoice_1'] = $options;
    return array($question, $options);
}

/**
 * Generates distractors for multiplechoice_1. Randomly picked from a larger pool of distractors
 * in order to mitigate test-wiseness.
 * @param $x int the minuend
 * @param $y int the subtrahend
 * @return array of 3 distractors
 */
function generate_distractors_mc($x, $y){
    // Generate distractors. 1. Close to the solution 2. Swapped digits
    // Remove obviously wrong options: number > 100 and (< 10 ?)
    // Save 3 random ones and the solution (shuffled) in the options array
    $solution = $x - $y;
    $distractor_1 = $solution++;
    $distractor_2 = $solution--;
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
 * Callback function for distractor fitering. Filters distractors that are null, > 100, or the solution.
 */
function filter_distractors($dis){
        if ($dis == null || $dis > 100 || $dis == $_SESSION['solution_multiplechoice_1']) {
            return false;
        } else return true;
}

/**
 * Swaps digits for a two digit number. Used to create distractors
 * @param $n int the original number
 * @return int|null the number with swapped digits or null if the number is not 2 digits or if the two digits are the same
 */
function swap_digits($n){
    if ($n < 10 || $n > 99 || $n%10 == 0 || (int)($n/10) == $n%10) return null;
    $first = (int)($n/10);
    $second = $n%10;
    return $second*10+$first;
}

/**
 * TRUE FALSE
 * @return string question
 */
function generate_truefalse_1(){
    $operators = ["Plus", "Minus", "Mal", "Geteilt"];
    $terms = ["Summe", "Differenz", "Produkt", "Quotient"];
    $rand_operator = mt_rand(0, 3);
    $x = $operators[$rand_operator];
    $solution = (mt_rand(0,1) == 0);
    if ($solution) {
        $y = $terms[$rand_operator];
    } else {
        do {$rand_term = mt_rand(0,3);} while ($rand_term == $rand_operator);
        $y = $terms[$rand_term];
    }
    $question = "Das Ergebnis einer $x Aufgabe heißt $y";
    $_SESSION['question_truefalse_1'] = $question;
    $_SESSION['solution_truefalse_1'] = $solution;
    return $question;
}

/**
 * MATCHING (MOCK)
 */
function generate_matching_1()
{
    $question = "Welche Terme haben das selbe Ergebnis?";
    $options = array(array("2 x 8", "15 x 7", "6 x 7"), array("2 x 21", "35 x 3", "6 x 5",));
    $solution = array(" 6 x 5", "35 x 3", "2 x 21");

    $_SESSION['question_matching_1'] = $question;
    $_SESSION['options_matching_1'] = $options;
    $_SESSION['solution_matching_1'] = $solution;
}

/**
 *
 */
function generate_matching_2()
{
    $question = "Welche Terme haben das selbe Ergebnis?";
    $terms1 = array(); // fixed
    $terms2 = array(); // to be rearranged by user
    // while (count($terms1) < 3){ //TODO removed for testing
        $x = mt_rand(8, 100);
        if (count(primefactor($x)) > 2) {   // Result number found with min. 3 prime factors
            $factors = primefactor($x);
            $splitters = unique_random_numbers(1, count($factors)-2, 2);    // 2 unique values used to split the prime factors array in two places for each term
            $primes1_term1 = array_slice($factors, 0, $splitters[0]);   // get primefactors for first factor for term 1
            $factor1_term1 = array_sum($primes1_term1);
            $primes2_term1 = array_slice($factors, $splitters[0]);   // get primefactors for second factor for term 1
            $factor2_term1 = array_sum($primes2_term1);
            $term1 = "$factor1_term1 x $factor2_term1";

            $terms1[] = $term1;

            $primes1_term2 = array_slice($factors, 0, $splitters[1]);   // get primefactors for first factor for term 1
            $factor1_term2 = array_sum($primes1_term2);
            $primes2_term2 = array_slice($factors, $splitters[1]);   // get primefactors for second factor for term 1
            $factor2_term2 = array_sum($primes2_term2);
            $term2 = "$factor1_term2 x $factor2_term2";

            $terms2[] = $term2;

            echo $term1;

        }
    //}
}

function unique_random_numbers($min, $max, $amount){
    $random_numbers = array();
    $n = range($min,$max);
    shuffle($n);
    for ($i=0; $i< $amount; $i++) {
        $random_numbers[] = $n[$i];
    }
    return $random_numbers;
}

function primefactor($num) {
    $sqrt = sqrt($num);
    for ($i = 2; $i <= $sqrt; $i++) {
        if ($num % $i == 0) {
            return array_merge(primefactor($num/$i), array($i));
        }
    }
    return array($num);
}

//TODO remove return values if Session values preferred