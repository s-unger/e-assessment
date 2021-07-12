<?php if(!isset($_SESSION)) session_start();
$pdo = new PDO('mysql:host=localhost;dbname=e-assessment_db', 'e-assessment_user', 'topsecretdbpass');
//redirect to basepage if not logged in
if (!isset($_SESSION['userid'])) {
    header("Location: ../index.php");
}
include "../index.php";
include 'laDataGenerator.php';
?>
<style>
    <?php include '../style.css'; ?>
</style>

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
                    <button id="buttonLine">Alle Aufgaben</button>
                </div>

            </div>
            <div class="bar_chart svg-container ">
                <div class="vizButtons">
                    <button id="buttonBar1">Wie viele Tests pro Tag
                    </button>
                    <button id="buttonBar2">Wie viele Aufgaben
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
</script>