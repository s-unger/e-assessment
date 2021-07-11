<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=e-assessment_db', 'e-assessment_user', 'topsecretdbpass');
//redirect to basepage if not logged in
if (!isset($_SESSION['userid'])) {
    header("Location: ../index.php");
}
include "../index.php"; ?>
<style>
    <?php include '../style.css'; ?>
</style>
<?php
if ($_POST) {
    if (isset($_POST['newTest'])) {
        newTest();
    }
}

function newTest()
{
    $_SESSION['newQuestions'] = true;
}

// get data for line and pie chart from database
$statement = $pdo->prepare("SELECT questionId, correctness, solved_at FROM answers WHERE userId = ? ORDER BY questionId, solved_at, id ASC ");
$result = $statement->execute(array(
    $_SESSION['userid']
));
while ($row = $statement->fetch()) {
    $dataAnswers[] = array('questionId' => $row ['questionId'], 'solved_at' => $row['solved_at'], 'correctness' => intval($row['correctness']));
}

// index of exercises
$values = [0, 1, 2, 3, 4, 5, 6, 7];
// calculate correctness of exercises over time
// correctness++ if exercise was solved correctly
// correctness-- if exercise was not solved correctly
// always correctness >= 0
function calculateCorrectness($values, $data)
{
    foreach ($values as $value) {
        $new = array_values(array_filter($data, function ($var) use ($value) {
            return ($var['questionId'] == $value);
        }));
        $j = 0;
        $length = count($new);
        $y = $new[0]['correctness'];
        for ($i = 0; $i < $length; $i++) {
            if ($i > 0) {
                if ($new[$i]['correctness'] == 0 && $y != 0) {
                    $y--;
                } else if ($new[$i]['correctness'] == 1) {
                    $y++;
                }
                if ($new[$i]['solved_at'] == $new[$j]['solved_at']) {
                    $new[$j]['correctness'] = $y;
                    unset($new[$i]);
                } else {
                    $new[$i]['correctness'] = $y;
                    $j = $i;
                }
            }
        }
        $dataCalculated[] = array_values($new);
    }
    $dataCalculated = array_merge(...$dataCalculated);
    return $dataCalculated;
}

$dataCorrectnessOverTime = calculateCorrectness($values, $dataAnswers);

// calculate how many times exercise x was solved correctly in percent
$percent = null;
$percent = getPercentage($values, $dataAnswers);

// calculate how many times exercise x was solved correctly in percent last 5 tests
function getEntriesOfLast5Tests($values, $data)
{
    foreach ($values as $value) {
        $new = array_values(array_filter($data, function ($var) use ($value) {
            return ($var['questionId'] == $value);
        }));
        $new = array_slice($new, -5);
        $last5[] = array_values($new);
    }
    $last5 = array_merge(...$last5);
    return $last5;
}

$percentLast5 = getPercentage($values, getEntriesOfLast5Tests($values, $dataAnswers));

// base from https://stackoverflow.com/questions/15542808/finding-average-of-same-key-values-of-an-associate-array
function getPercentage($values, $datax)
{
    foreach ($values as $value) {
        $new = array_values(array_filter($datax, function ($var) use ($value) {
            return ($var['questionId'] == $value);
        }));
        foreach ($new as $array) {
            $values = array_values($array);
            $eachDay[$values[0]][] = $values[2];

            if (isset($abc[$values[0]])) // prevent index warning
            {
                $abc[$values[0]] += $values[2];
            } else {
                $abc[$values[0]] = $values[2];
            }
        }
    }
    foreach ($eachDay as $day => $values) {
        $x = intval(round($abc[$day] / count($values), 2, PHP_ROUND_HALF_UP) * 100);
        $percent[] = array('questionId' => $day, 'percent' => $x, 'correctness' => "richtig");
        $percent[] = array('questionId' => $day, 'percent' => (100 - $x), 'correctness' => "falsch");
    }
    return $percent;
}

// get data about how many tests were done and mean of how much exercises were correct (user and comparison to group) for bar chart
// data for user
$statement2 = $pdo->prepare("SELECT date, amount, correct FROM tests WHERE userId = ? ORDER BY date ASC");
$result2 = $statement2->execute(array($_SESSION['userid']));

while ($row2 = $statement2->fetch()) {
    $dataTests[] = array('date' => $row2['date'], 'y' => $row2['amount']);
    $dataCorrect[] = array('date' => $row2['date'], 'y' => $row2['correct']);
}

// data for group
$statement3 = $pdo->prepare("SELECT date, amount, correct FROM tests WHERE userId <> ? ORDER BY date ASC");
$result3 = $statement3->execute(array($_SESSION['userid']));
while ($row3 = $statement3->fetch()) {
    $dataTestsAll[] = array('date' => $row3['date'], 'y' => $row3['amount']);
    $dataCorrectAll[] = array('date' => $row3['date'], 'y' => $row3['correct']);
}

// calculate mean
// base from https://stackoverflow.com/questions/15542808/finding-average-of-same-key-values-of-an-associate-array
function getMean($data)
{
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

$dataTestsMean = getMean($dataTestsAll);
$new = null;

$dataCorrectMean = getMean($dataCorrectAll);

// calculate abilities

// get all exercises to Notation & Terminologie
$notation[] = array_values(array_filter($percentLast5, function ($var) {
    return ($var['questionId'] == 0);
}));
$notation[] = array_values(array_filter($percentLast5, function ($var) {
    return ($var['questionId'] == 6);
}));
$notation[] = array_values(array_filter($percentLast5, function ($var) {
    return ($var['questionId'] == 7);
}));

// get percentage of all 3 exercises combined
function calculateAbility($data, $abilityName)
{
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

// return percentage of other abilities which only have 1 exercise
function getPercentageValueCorrect($data, $index)
{
    foreach (array_filter($data, function ($var) use ($index) {
        return ($var['questionId'] == $index);
    }) as $array) {
        $values = array_values($array);
        return $values[1];
    }
}

// create new array for abilities
$ability = calculateAbility($notation, "Notation & Terminologie");
$ability[] = array('axis' => "Subtraktion", 'value' => getPercentageValueCorrect($percentLast5, 1));
$ability[] = array('axis' => "Addition", 'value' => getPercentageValueCorrect($percentLast5, 2));
$ability[] = array('axis' => "Multiplikation", 'value' => getPercentageValueCorrect($percentLast5, 3));
$ability[] = array('axis' => "Subtraktion mit Zehnerübergang", 'value' => getPercentageValueCorrect($percentLast5, 4));
$ability[] = array('axis' => "Addition mit Zehnerübergang", 'value' => getPercentageValueCorrect($percentLast5, 5));


//calculate traffic light
function trafficLight($values, $data)
{
    foreach ($values as $value) {
        $result += getPercentageValueCorrect($data, $value);
    }
    return $result / count($values);
}

$trafficLight = trafficLight($values, $percentLast5);


?>
<script src="https://d3js.org/d3.v4.min.js"></script>
<script src="/scripts/radarChart.js"></script>
<script src="/scripts/lineChart.js"></script>
<script src="/scripts/barChart.js"></script>
<script src="/scripts/pieChart.js"></script>


<div class="content">
    <form action="test.php" method="post">
        <input class="btn" type="submit" name="newTest" value="Mach den Test"/>
    </form>

    <div class="chartsBackground">
        <div id="traffic-light">
            <div id="redLight" class="bulb"></div>
            <div id="yellowLight" class="bulb"></div>
            <div id="greenLight" class="bulb"></div>
        </div>
        <div class="charts">
            <div class="line_chart svg-container">
                <!--            <p class="headline">Hier siehst du alle deine Versuche aus dem Testmodus. (Verbesserung/Verschlechterung-->
                <!--                über Zeit)</p>-->
                <!--            <p class="headline">Falsch beantwortete Fragen wurden von der Anzahl richtig beantworteter Fragen-->
                <!--                abgezogen.</p>-->
                <div class="vizButtons">
                    <select id="selectButton"></select>
                    <button id="buttonLine" onclick="updateLines()">Alle Aufgaben</button>
                </div>

            </div>
            <div class="bar_chart svg-container ">
                <div class="vizButtons">
                    <button id="button1" onclick="update(tests, '#ec9a42', testsAll, '#6c0074')">Wie viele Tests pro Tag
                    </button>
                    <button id="button2" onclick="update(correct, '#197fa7', correctAll, '#129a48')">Wie viele Aufgaben
                        im
                        Schnitt richtig
                    </button>
                </div>
            </div>
        </div>
        <div class="charts">
            <div class="pie_chart svg-container">
                <div style="display: flex; justify-content: space-between; margin: 5% 0 0 0;">
                    <div style="margin-left: 10%;">
                        <span>Alle Tests:</span>
                        <select id="selectButtonPie"></select>
                    </div>
                    <div style="margin-right: 10%;">
                        <span>Letzten 5 Tests:</span>
                        <select id="selectButtonPie5"></select>
                    </div>
                </div>
            </div>
            <div class="radar_chart svg-container" style="padding-bottom: 0;"></div>
        </div>

    </div>
</div>

<script type="text/javascript">
    // set the dimensions and margins for the graphs
    var margin = {top: 10, right: 40, bottom: 100, left: 60},
        width = 550 - margin.left - margin.right,
        height = 400 - margin.top - margin.bottom;

    // line chart
    var data = <?php echo json_encode($dataCorrectnessOverTime);?>;
    var allGroup = d3.map(data, function (d) {
        return (d.questionId)
    }).keys();
    let svg_line = LineChart(".line_chart", data, allGroup);


    // bar plot how many tests a day / how many percent correct per day

    var tests = <?php echo json_encode($dataTests);?>;
    var correct = <?php echo json_encode($dataCorrect);?>;
    var testsAll = <?php echo json_encode($dataTestsMean);?>;
    var correctAll = <?php echo json_encode($dataCorrectMean);?>;
    let svg_bar = BarChart(".bar_chart", tests, correct, testsAll, correctAll);

    // pie chart
    var dataPercent = <?php echo json_encode($percent);?>;
    var dataPercentLast5 = <?php echo json_encode($percentLast5);?>;
    let svg_pie = PieChart(".pie_chart", dataPercent, dataPercentLast5, allGroup);


    // spider chart
    var ability = <?php echo json_encode($ability);?>;

    var radarChartOptions = {
        w: 405,
        h: 500,
        margin: {top: 30, right: 70, bottom: 60, left: 70},
        levels: 5,
        maxValue: 100,
        roundStrokes: true,
        color: "#197fa7",
        format: '.0f'
    };

    var data2 = [{name: "Du", axes: ability}];
    let svg_radar1 = RadarChart(".radar_chart", data2, radarChartOptions);

    //traffic light
    // inspiration: https://medium.com/@kikichan513/javascript-interview-create-a-traffic-light-15d4b634067f
    var traffic = <?php echo json_encode($trafficLight) ?>;
    console.log(traffic);

    function makeRed() {
        document.getElementById('redLight').style.backgroundColor = 'red'
    }

    function makeYellow() {
        document.getElementById('yellowLight').style.backgroundColor = 'yellow'
    }

    function makeGreen() {
        document.getElementById('greenLight').style.backgroundColor = 'green'
    }

    if (traffic >= 80) {
        makeGreen();
    } else if (traffic < 80 && traffic >= 60) {
        makeYellow();
    } else makeRed();

</script>