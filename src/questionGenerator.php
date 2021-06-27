<?php
const number_space = 100;
const actors = [["Der Mathetiger", "er"], ["Das Mathezebra", "es"], ["Die Mathekatze", "sie"]];
const actors2 = ["Das Musiknilpferd", "Der Deutschaffe", "Die Kunstmaus"];

/**
 * generates NUMERICAL item
 * Topic: Addition
 * Saves to session:    String question
 *                      int solution
 */
function generate_numerical_1(){
    $x = mt_rand(1, number_space - 1);
    $y = mt_rand(1, (number_space - $x));
    $actor = mt_rand(0,count(actors)-1);
    $question = actors[$actor][0] . " hat $x Streifen am Kopf und $y Streifen am Körper. Wie viele Streifen hat " . actors[$actor][1] . " insgesamt?";
    $solution = $x + $y;
    $_SESSION['question_numerical_1'] = $question;
    $_SESSION['solution_numerical_1'] = $solution;
}

/**
 * generates NUMERICAL item 2
 * Topic: Subtraction with decimal carry
 * Saves to session:    String question
 *                      int solution
 *                      int misc_carry, wrong answer from common misconception (digit carry)
 *                      int misc_operator, wrong answer from common misconception (wrong operator chosen form text)
 */
function generate_numerical_2(){
    // Generate subtraction with decimal carry, with x in [21, 98], y in [12, 89], solution in [9, 79].
    // Keep minuend in double digit to keep the digit carry misconception realistic, i.e. smaller than the original subtrahend.
    do {
        $x = mt_rand(21, number_space);
        $y = mt_rand(10, ($x - 1));
    } while ($x%10 >= $y%10 || $x%10 == 0);
    //echo "x modulo 10 " . $x%10;
    //echo "y modulo 10 " . $y%10;
    $actor1 = actors[mt_rand(0,count(actors)-1)][0];
    $actor2 = actors2[mt_rand(0,count(actors)-1)];
    $question = "$actor1 hat $x Bücher zuhause. $actor2 leiht sich $y Bücher aus. Wie viele Bücher hat " . lcfirst($actor1) . " noch zuhause?";
    $solution = $x - $y;
    $misc_carry = $solution + 10;
    $misc_operator = $x + $y;

    $_SESSION['question_numerical_2'] = $question;
    $_SESSION['solution_numerical_2'] = $solution;
    $_SESSION['misc_carry_numerical_2'] = $misc_carry;
    $_SESSION['misc_operator_numerical_2'] = $misc_operator;
}

/**
 * generates MULTIPLE CHOICE item
 * Topic: Subtraction without decimal carry
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
    } while ($x%10 < $y%10 );   // get subtraction without decimal carry
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
 * Swaps digits for a two digit number. Used to create distractors.
 * @param $n int the original number
 * @return int|null the number with swapped digits or null if the number is not 2 digits, the two digits are the same or the second digit is zero
 */
function swap_digits($n){
    if ($n < 10 || $n > 99 || $n%10 == 0 || (int)($n/10) == $n%10) return null;
    $first = (int)($n/10);
    $second = $n%10;
    return $second*10+$first;
}

/**
 * generates TRUE FALSE item
 * Saves to session:    String question
 *                      bool solution
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
}

/**
 * generates MATCHING item
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
    shuffle($terms2);
    $options = array($terms1, $terms2);

    $_SESSION['question_matching_1'] = $question;
    $_SESSION['options_matching_1'] = $options;
    $_SESSION['solution_matching_1'] = $solution;
}

/**
 * Generates a term (such as "3 x 5") for a matching item
 * @param $primefactors array containing the prime factors of the result number
 * @param $splitter int where the primefactor array will be split to generate the two factors for the term
 * @return string the term
 */
function generate_matching_1_term($primefactors, $splitter){
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
function unique_random_numbers($min, $max, $amount){
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
function primefactor($num) {
    $sqrt = sqrt($num);
    for ($i = 2; $i <= $sqrt; $i++) {
        if ($num % $i == 0) {
            return array_merge(primefactor($num/$i), array($i));
        }
    }
    return array($num);
}