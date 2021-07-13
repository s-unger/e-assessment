<?php
$pdo = new PDO('mysql:host=localhost;dbname=e-assessment_db', 'e-assessment_user', 'topsecretdbpass');

// index of exercises
$index = [0, 1, 2, 3, 4, 5, 6, 7];

// get data about answers from database
$statementAnswers = $pdo->prepare("SELECT questionId, correctness, solved_at, misconception FROM answers WHERE userId = ? ORDER BY questionId, solved_at, id ASC ");
$resultAnswers = $statementAnswers->execute(array(
    $_SESSION['userid']
));
$dataAnswers = [];
while ($row = $statementAnswers->fetch()) {
    $dataAnswers[] = array('questionId' => $row ['questionId'], 'solved_at' => $row['solved_at'], 'correctness' => intval($row['correctness']), 'misconception' => $row['misconception']);
}

// get data about how many tests were done and mean of how much exercises were correct (user and comparison to group) for bar chart

// data for user
$statementTestsUser = $pdo->prepare("SELECT date, amount, correct FROM tests WHERE userId = ? ORDER BY date ASC");
$resultTestsUser = $statementTestsUser->execute(array($_SESSION['userid']));
$dataTestsCorrect = [];
while ($row = $statementTestsUser->fetch()) {
    $dataTestsCorrect[] = array('date' => $row['date'], 'y' => intval($row['amount']), 'correct' => intval($row['correct']));
}

// data for group
$statementTestsGroup = $pdo->prepare("SELECT date, amount, correct FROM tests WHERE userId <> ? ORDER BY date ASC");
$resultTestsGroup = $statementTestsGroup->execute(array($_SESSION['userid']));
$dataTestsAllCorrect = [];
while ($row = $statementTestsGroup->fetch()) {
    $dataTestsAllCorrect[] = array('date' => $row['date'], 'y' => intval($row['amount']), 'correct' => intval($row['correct']));
}

/**
 *  calculate correctness of exercises over time for line chart
 *  correctness++ if exercise was solved correctly
 *  correctness-- if exercise was not solved correctly
 *  always correctness >= 0
 *
 * @param array $values indexes of exercsises
 * @param array $data dataset of exercises
 * @return array correctness of exercises over time, one entry per day
 */
function calculateCorrectnessOverTime(array $values, array $data): array
{
    $result = [];
    foreach ($values as $value) {
        $new = array_values(array_filter($data, function ($var) use ($value) {
            return ($var['questionId'] == $value);
        }));
        $j = 0;
        $length = count($new);
        if (!empty($new)) {
            $y = $new[0]['correctness'];
        }
        for ($i = 0; $i < $length; $i++) {
            if ($i > 0) {
                if ($new[$i]['correctness'] === 0 && $y != 0) {
                    $y--;
                } else if ($new[$i]['correctness'] === 1) {
                    $y++;
                }
                if ($new[$i]['solved_at'] === $new[$j]['solved_at']) {
                    $new[$j]['correctness'] = $y;
                    unset($new[$i]);
                } else {
                    $new[$i]['correctness'] = $y;
                    $j = $i;
                }
            }
        }
        $result[] = array_values($new);
    }
    return array_merge(...$result);
}

/**
 *  calculate how many tries of exercise x was solved correctly/wrongly in percent
 *  foreach loop base from https://stackoverflow.com/questions/15542808/finding-average-of-same-key-values-of-an-associate-array
 * @param array $values indexes of exercsises
 * @param array $data dataset of exercises
 * @return array percentage of correct and wrong tries per exercise
 */
function getPercentage(array $values, array $data): array
{
    // initialize arrays;
    $abc = [];
    $eachExercise = [];
    $percent = [];
    foreach ($values as $value) {
        $new = array_values(array_filter($data, function ($var) use ($value) {
            return ($var['questionId'] == $value);
        }));
        foreach ($new as $array) {
            $val = array_values($array);
            $eachExercise[$val[0]][] = $val[2];
            if (isset($abc[$val[0]])) // prevent index warning
            {
                $abc[$val[0]] += $val[2];
            } else {
                $abc[$val[0]] = $val[2];
            }
        }
    }
    foreach ($eachExercise as $exercise => $values) {
        $x = intval(round($abc[$exercise] / count($values), 2, PHP_ROUND_HALF_UP) * 100);
        $percent[] = array('questionId' => $exercise, 'percent' => $x, 'correctness' => "richtig");
        $percent[] = array('questionId' => $exercise, 'percent' => (100 - $x), 'correctness' => "falsch");
    }
    return $percent;
}

/**
 *  get exercise entries from last 5 tests
 *
 * @param array $values indexes of exercsises
 * @param array $data dataset of exercises
 * @return array exercise entries of last 5 tests
 */
function getEntriesOfLast5Tests(array $values, array $data): array
{
    $last5 = [];
    foreach ($values as $value) {
        $new = array_values(array_filter($data, function ($var) use ($value) {
            return ($var['questionId'] == $value);
        }));
        $new = array_slice($new, -5);
        $last5[] = array_values($new);
    }
    return array_merge(...$last5);
}

/**
 *  calculate mean of y
 *  foreach loop base from https://stackoverflow.com/questions/15542808/finding-average-of-same-key-values-of-an-associate-array
 *
 * @param array $data dataset with date and amount of tests done per user per day and/or average amount of correct solved exercises per user per day
 * @return array mean value of y (amount of tests or average amount of correctly solved exercises) of whole group for each date
 */
function getMean(array $data): array
{
    $mean = [];
    $eachDay = [];
    $abc = [];
    foreach ($data as $array) {
        $values = array_values($array);
        $eachDay[$values[0]][] = $values[1];
        if (isset($abc[$values[0]])) // prevent index warning
        {
            $abc[$values[0]] += $values[1];
        } else {
            $abc[$values[0]] = $values[1];
        }
    }
    foreach ($eachDay as $day => $values) {
        $mean[] = array('date' => $day, 'y' => $abc[$day] / count($values));
    }
    return $mean;
}


/**  calculate mean of correctly answered exercises per day per user
 *  foreach loop base from https://stackoverflow.com/questions/15542808/finding-average-of-same-key-values-of-an-associate-array
 *
 * @param array $data dataset with date, amount of tests done per user per day and total number of correct solved exercises per user per day
 * @return array mean value of correct for each user of group for each date
 */
function getMeanOfCorrectTestPerDay(array $data): array
{
    $mean = [];
    foreach ($data as $array) {
        $values = array_values($array);
        $mean[] = array('date' => $values[0], 'y' => $values[2] / $values[1]);
    }
    return $mean;
}

/**
 *  get all dates from data without duplicates
 *  (needed for bar chart)
 *
 * @param array $data dataset of tests done / average amount of correctly solved exercises per day
 * @return array all dates from data
 */
function getDates(array $data): array
{
    $dates = [];
    foreach ($data as $array) {
        $values = array_values($array);
        $dates[] = $values[0];
    }
    return $dates;
}

/**
 *  add data entry (y=0) if date doesn't exist in data
 *  (needed for bar chart)
 *
 * @param array $data data to be updated
 * @param array $dates dates that need to be in data array
 * @return array updated data -> entry for each date exists
 */
function addDataForMissingDates(array $dates, array $data): array
{
    $i = 0;
    foreach ($dates as $val) {
        if (array_search($val, array_column($data, 'date')) === false) {
            array_splice($data, $i, 0, array(['date' => $val, 'y' => 0]));
        }
        $i++;
    }
    return $data;
}

/**
 *  get all exercises to Notation & Terminologie
 *
 * @param array $data percentage correct/wrong last 5 tests of all exercises
 * @return array percentage correct/wrong last 5 tests of exercises belonging to Notation & Terminologie
 */
function getAllExercisesToNotation(array $data): array
{
    $result[] = array_values(array_filter($data, function ($var) {
        return ($var['questionId'] == 0);
    }));
    $result[] = array_values(array_filter($data, function ($var) {
        return ($var['questionId'] == 6);
    }));
    $result[] = array_values(array_filter($data, function ($var) {
        return ($var['questionId'] == 7);
    }));
    return $result;
}

/**
 *  calculate percentage of ability Notation & Terminologie
 *  -> percentage of solving exercises belonging to Notation & Terminolgoie correctly
 *  foreach loop base from https://stackoverflow.com/questions/15542808/finding-average-of-same-key-values-of-an-associate-array
 *
 * @param array $data dataset with percentage correct/wrong last 5 tests of exercises belonging to Notation & Terminolgoie
 * @param string $abilityName name of ability
 * @return array dataset with mean of percentage correct of last 5 tests of exercises belonging to Notation & Terminologie
 */
function calculateAbilityNotation(array $data, string $abilityName): array
{
    $abc = [];
    $combined = [];
    $ability = [];
    foreach ($data as $array) {
        foreach ($array as $array2) {
            $values = array_values($array2);
            if ($values[2] == "richtig") {
                $combined[$values[2]][] = $values[1];
                if (isset($abc[$values[2]])) // prevent index warning
                {
                    $abc[$values[2]] += $values[1];
                } else {
                    $abc[$values[2]] = $values[1];
                }
            }
        }
    }
    foreach ($combined as $val => $values) {
        $ability[] = array('axis' => $abilityName, 'value' => $abc[$val] / count($values));
    }
    return $ability;
}

/**
 * get percentage value of exercise being solved correctly
 *
 * @param array $data dataset with percentage correct/wrong
 * @param int $index index of exercise
 * @return int|null percentage correct of exercise
 */
function getPercentageValueCorrect(array $data, int $index): ?int
{
    foreach (array_filter($data, function ($var) use ($index) {
        return ($var['questionId'] == $index);
    }) as $array) {
        $values = array_values($array);
        return $values[1];
    }
    return null;
}

/**
 * calculate traffic light
 *
 * @param array $values index of exercises
 * @param array $data data of tests
 * @return int percentage of all correctly solved exercises
 */
function trafficLight(array $values, array $data): int
{
    $result = 0;
    foreach ($values as $value) {
        $result += getPercentageValueCorrect($data, $value);
    }
    return $result / count($values);
}

/**
 * calculate how often which misconception happened
 * foreach loop base from https://stackoverflow.com/questions/15542808/finding-average-of-same-key-values-of-an-associate-array
 *
 * @param array $data dataset of answers
 * @param int $value index of exercise
 * @return array dataset containing how often which misconception happened for the specific exercise
 */
function calculateMisconceptions(array $data, $value): array
{
    $abc = [];
    $combined = [];
    $result = [];
    $data = array_values(array_filter($data, function ($var) use ($value) {
        return ($var['questionId'] == $value);
    }));
    foreach ($data as $array) {
        $values = array_values($array);
        if ($values[3] != null) {
            $combined[$values[3]] = $values[3];
            if (isset($abc[$values[3]])) // prevent index warning
            {
                $abc[$values[3]] += 1;
            } else {
                $abc[$values[3]] = 1;
            }
        }
    }
    ksort($abc);
    ksort($combined);
    foreach ($combined as $val => $values) {
        $result[] = array('questionId' => $value, 'misconception' => $val, 'value' => $abc[$val]);
    }
    return $result;
}

/**
 * get the most common misconception which happened at least 3 times
 * @param array $data dataset of answers
 * @return int|null index of misconception
 */
function getMostCommonMisconception(array $data): ?int
{
    if (!empty($data)) {
        $max = max(array_column($data, 'value'));
        if ($max >= 3) {
            $key = array_search($max, array_column($data, 'value'));
            return $data[$key]['misconception'];
        }
    }
    return null;
}

/** line chart */
// calculate correctness of exercises over time and save in session
$_SESSION['lineChart'] = calculateCorrectnessOverTime($index, $dataAnswers);


/** bar chart */
// calculate mean of how many tests were done per day by group
$dataTestsMean = getMean($dataTestsAllCorrect);

// calculate mean of how many exercises were done correctly per day by group
$dataCorrectMean = getMean(getMeanOfCorrectTestPerDay($dataTestsAllCorrect));

// get all possible dates from the user's and the group's datasets
$datesTotal = array_unique(array_merge(getDates($dataTestsCorrect), getDates($dataTestsMean)));

// update bar chart data arrays and save in session
$_SESSION['dataTests'] = addDataForMissingDates($datesTotal, $dataTestsCorrect);
$_SESSION['dataTestsMean'] = addDataForMissingDates($datesTotal, $dataTestsMean);
$_SESSION['dataCorrect'] = addDataForMissingDates($datesTotal, getMeanOfCorrectTestPerDay($dataTestsCorrect));
$_SESSION['dataCorrectMean'] = addDataForMissingDates($datesTotal, $dataCorrectMean);


/** pie chart */
// calculate how many times exercise x was solved correctly in percent all tries and save in session
$_SESSION['dataPieChartAllTests'] = getPercentage($index, $dataAnswers);

// calculate how many times exercise x was solved correctly in percent last 5 tries and save in session
$_SESSION['dataPieChartLast5Tests'] = $percentLast5Tests = getPercentage($index, getEntriesOfLast5Tests($index, $dataAnswers));

// get most common misconception for all tests and save in session
$_SESSION['misconceptionAll'] = getMostCommonMisconception(calculateMisconceptions($dataAnswers, 4));

// get most common misconception for last 5 tests and save in session
$_SESSION['misconception5'] = getMostCommonMisconception(calculateMisconceptions(getEntriesOfLast5Tests($index, $dataAnswers), 4));


/** abilities */
// get all exercises belonging to Notation & Terminologie
$notation = getAllExercisesToNotation($percentLast5Tests);

// create new array for abilities
$ability = calculateAbilityNotation($notation, "Notation & Terminologie");
$ability[] = array('axis' => "Subtraktion", 'value' => getPercentageValueCorrect($percentLast5Tests, 1));
$ability[] = array('axis' => "Addition", 'value' => getPercentageValueCorrect($percentLast5Tests, 2));
$ability[] = array('axis' => "Multiplikation", 'value' => getPercentageValueCorrect($percentLast5Tests, 3));
$ability[] = array('axis' => "Subtraktion mit Zehnerübergang", 'value' => getPercentageValueCorrect($percentLast5Tests, 4));
$ability[] = array('axis' => "Addition mit Zehnerübergang", 'value' => getPercentageValueCorrect($percentLast5Tests, 5));

// save in session
$_SESSION['ability'] = $ability;


/** traffic light */
// get percentage of all correctly solved exercises from last 5 tests and save in session
$_SESSION['trafficLight'] = trafficLight($index, $percentLast5Tests);

