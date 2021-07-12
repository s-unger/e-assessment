<?php if (!isset($_SESSION)) session_start();
$pdo = new PDO('mysql:host=localhost;dbname=e-assessment_db', 'e-assessment_user', 'topsecretdbpass');
//redirect to basepage if not logged in
if (!isset($_SESSION['userid'])) {
    header("Location: ../index.php");
}
include "../index.php";
include 'laDataGenerator.php';

/**
 * Most common misconception reminder to be added to the LA if a certain misconception has been repeated at least 3 times.
 * Reminders for misconception 0, 1 and 2.
 * Prototype only for question 5.
 * Links are mock links (should link to help sites for the specific problem).
 */
$misconceptionReminder5_0 = "Fehlertyp: <b>Keine Überträge</b> 
<br>Beachte den <b>Zehnerübergang</b>! Nach der Erweiterung der Einerziffer des Minuenden findet ein <b>Übertrag</b> zur Zehnerziffer statt. 
<br><a href=home.php>Lern mehr!</a>";
$misconceptionReminder5_1 = "Fehlertyp: <b>Spaltenweise Unterschiedsbildung</b>
<br>An der Einerstelle kann nicht einfach die kleinere von der größeren Ziffer abgezogen werden. 
Um an der Einerstelle Minus zu rechnen, benötigst du einen Übertrag in die Zehnerstelle.
<br><a href=home.php>Lern mehr!</a>";
$misconceptionReminder5_2 = "Fehlertyp: <b>Probleme mit dem Textverständnis</b>
<br>Lies dir genau die Aufgabentexte durch. Um welche Rechentypen handelt es sich?
<br><a href=home.php>Lern mehr!</a>";
?>
<style>
    <?php include '../style.css'; ?>
</style>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"/>
<script src="https://d3js.org/d3.v4.min.js"></script>
<script src="/scripts/radarChart.js"></script>
<script src="/scripts/lineChart.js"></script>
<script src="/scripts/barChart.js"></script>
<script src="/scripts/pieChart.js"></script>

<div class="content">
    <form action="test.php" method="post">
        <input class="btn" type="submit" name="newTest" value="Übungsmodus"/>
    </form>
    <form action="test.php" method="post">
        <input class="btn" type="submit" name="newExam" value="Prüfungsmodus"/>
    </form>

    <div class="chartsBackground">
        <div class="charts">
            <div class="trafficLight-container">
                <div class="icon" id="icon0">
                    <i class="fas fa-info-circle fa-2x" style="color: #ffffff; margin-right: 2vw;"> </i>
                </div>
                <div class="overlay" id="overlay0">
                    <div class="text" style="top: 35%;">Hier siehst du, ob du bereit bist für die Prüfung.
                    </div>
                </div>
                <div id="traffic-light">
                    <div id="redLight" class="bulb"></div>
                    <div id="yellowLight" class="bulb"></div>
                    <div id="greenLight" class="bulb"></div>
                </div>
            </div>
            <div class="line_chart svg-container">
                <div class="vizButtons">
                    <div>
                        <select id="selectButton"></select>
                        <button id="buttonLine">Alle Aufgaben</button>
                    </div>
                    <div class="icon" id="icon1">
                        <i class="fas fa-info-circle fa-2x" style="color: #ffffff; margin-right: 2vw;"> </i>
                    </div>
                </div>
                <div class="overlay" id="overlay1">
                    <div class="text">Hier kannst du sehen wie du dich über die Zeit verbessert oder verschlechtert hast
                        bei den einzelnen Aufgaben.
                    </div>
                </div>
            </div>
            <div class="bar_chart svg-container">
                <div class="vizButtons">
                    <div>
                        <button id="buttonBar1">Wie viele Tests pro Tag
                        </button>
                        <button id="buttonBar2">Wie viele Aufgaben
                            im
                            Schnitt richtig
                        </button>
                    </div>
                    <div class="icon" id="icon2">
                        <i class="fas fa-info-circle fa-2x" style="color: #ffffff; margin-right: 2vw;"> </i>
                    </div>
                </div>
                <div class="overlay" id="overlay2">
                    <div class="text">Hier kannst du dich mit allen anderen Schülern vergleichen! Du kannst sehen wie
                        viele Tests du am Tag absolviert und wie viele Aufgaben du im Schnitt am Tag richtig hattest im
                        Vergleich zu den anderen.
                    </div>
                </div>
            </div>
        </div>
        <div class="charts">
            <div class="trafficLight-container"></div>
            <div class="pie_chart svg-container">
                <div class="vizButtons">
                    <div style="margin-left: 18%;">
                        <span>Alle Tests:</span>
                        <select id="selectButtonPie"></select>
                    </div>
                    <div>
                        <span>Letzten 5 Tests:</span>
                        <select id="selectButtonPie5"></select>
                    </div>
                    <div class="icon" id="icon3">
                        <i class="fas fa-info-circle fa-2x" style="color: #ffffff; margin-right: 2vw;"> </i>
                    </div>
                </div>
                <div id="misconceptionAll"
                     style="display: none; margin: 5% 5% 0 5%;">
                    <p>Du machst häufig diesen Fehler:</p>
                    <?php if ($_SESSION['misconceptionAll'] === 0) echo $misconceptionReminder5_0;
                    elseif ($_SESSION['misconceptionAll'] === 1) echo $misconceptionReminder5_1;
                    elseif ($_SESSION['misconceptionAll'] === 2) echo $misconceptionReminder5_2;
                    ?></div>
                <div id="misconception5"
                     style="display: none; margin: 5% 5% 0 5%;">
                    <p>Du machst häufig diesen Fehler:</p>
                    <?php if ($_SESSION['misconception5'] === 0) echo $misconceptionReminder5_0;
                    elseif ($_SESSION['misconception5'] === 1) echo $misconceptionReminder5_1;
                    elseif ($_SESSION['misconception5'] === 2) echo $misconceptionReminder5_2;
                    ?></div>
                <div class="overlay" id="overlay3">
                    <div class="text">Hier kannst du sehen wie viel Prozent der einzelnen Aufgaben du richtig bzw.
                        falsch gelöst hast. Du kannst auch nur die letzten 5 Tests auswählen.
                    </div>
                </div>
            </div>
            <div class="radar_chart svg-container" style="padding-bottom: 0;">
                <div class="icon" id="icon4" style="float: right; margin-right: 1vw; margin-top: 3vh;">
                    <i class="fas fa-info-circle fa-2x" style="color: #ffffff; margin-right: 2vw;"> </i>
                </div>
                <div class="overlay" id="overlay4">
                    <div class="text">Hier siehst du welche Fähigkeiten du durch das Üben bereits erworben hast.</div>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    // set the dimensions and margins for the graphs
    var margin = {top: 10, right: 40, bottom: 100, left: 60},
        width = 550 - margin.left - margin.right,
        height = 400 - margin.top - margin.bottom;

    // line chart
    var lineData = <?php echo json_encode($_SESSION['lineChart']);?>;
    var allGroup = d3.map(lineData, function (d) {
        return (d.questionId)
    }).keys();
    let svg_line = LineChart(".line_chart");


    // bar plot
    var tests = <?php echo json_encode($_SESSION['dataTests']);?>;
    var correct = <?php echo json_encode($_SESSION['dataCorrect']);?>;
    var testsAll = <?php echo json_encode($_SESSION['dataTestsMean']);?>;
    var correctAll = <?php echo json_encode($_SESSION['dataCorrectMean']);?>;
    let svg_bar = BarChart(".bar_chart");

    // pie chart
    var dataPercent = <?php echo json_encode($_SESSION['dataPieChartAllTests']);?>;
    var dataPercentLast5 = <?php echo json_encode($_SESSION['dataPieChartLast5Tests']);?>;
    let svg_pie = PieChart(".pie_chart");


    // spider chart
    var ability = <?php echo json_encode($_SESSION['ability']);?>;

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
    let svg_radar = RadarChart(".radar_chart", data2, radarChartOptions);

    //traffic light
    // inspiration: https://medium.com/@kikichan513/javascript-interview-create-a-traffic-light-15d4b634067f
    var traffic = <?php echo json_encode($_SESSION['trafficLight']) ?>;

    function makeRed() {
        document.getElementById('redLight').style.backgroundColor = 'red'
    }

    function makeYellow() {
        document.getElementById('yellowLight').style.backgroundColor = 'yellow'
    }

    function makeGreen() {
        document.getElementById('greenLight').style.backgroundColor = 'green'
    }

    /**
     * calculate color of traffic light
     * green if => 80% correct
     * yellow if >=50% and <80% correct
     * red if <49% correct
     */
    function calculateColor() {
        if (traffic >= 80) {
            makeGreen();
        } else if (traffic < 80 && traffic >= 50) {
            makeYellow();
        } else makeRed();
    }

    calculateColor();

    // click behaviour for info icons
    icons = document.getElementsByClassName('icon');

    for (i = 0; i < icons.length; i++) {
        let id = "overlay" + i.toString();
        icons[i].onclick = function () {
            document.getElementById(id).style.visibility = "visible";
            document.getElementById(id).style.zIndex = "2";
            document.getElementById(id).style.opacity = "0.9";
        }
        icons[i].onmouseout = function () {
            document.getElementById(id).style.visibility = "hidden";
        }
    }
</script>