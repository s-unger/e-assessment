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

// get data from database
$datax = array();
$datay = array();
$statement = $pdo->prepare("SELECT questionId, correctness, solved_at FROM answers WHERE userId = ? ORDER BY solved_at, questionId ASC ");
$result = $statement->execute(array(
    $_SESSION['userid']
));
while ($row = $statement->fetch()) {
    $i = $row['questionId'];
    $datax[$i][] = $row['solved_at'];
    $datay[$i][] = $row['correctness'];
};

$statement2 = $pdo->prepare("SELECT date, amount, correct FROM tests WHERE userId = ? ORDER BY date ASC");
$result2 = $statement2->execute(array($_SESSION['userid']));
$i = 0;
$datax2 = array();
$dataTests = array();
$dataCorrect = array();
while ($row2 = $statement2->fetch()) {
    $datax2[$i][] = $row2['date'];
    $dataTests[$i][] = $row2['amount'];
    $dataCorrect[$i][] = $row2['correct'];
    $i++;

}


?>
<script src="https://d3js.org/d3.v4.min.js"></script>

<div class="content">
    <form action="test.php" method="post">
        <input type="submit" name="newTest" value="Mach den Test"/>
    </form>
    <div style="display:flex;">
        <div class="line_chart1">
            <p class="headline">Hier siehst du alle deine Versuche aus dem Testmodus. (Verbesserung/Verschlechterung
                über Zeit)</p>
            <p class="headline">Falsch beantwortete Fragen wurden von der Anzahl richtig beantworteter Fragen
                abgezogen.</p>
        </div>
        <div class="line_chart2">
            <p class="headline">Hier siehst du alle deine Versuche aus dem Testmodus. (Wie oft Aufgabe richtig
                beantwortet wurde)</p>
            <p class="headline">Falsch beantwortete Fragen wurden nicht von der Anzahl richtig beantworteter Fragen
                abgezogen.</p>
        </div>


    </div>
    <!-- Add 2 buttons -->
    <button id="button1" onclick="update(tests, '#197fa7')">Wie viele Tests pro Tag</button>
    <button id="button2" onclick="update(correct, '#129a48')">Wie viele Aufgaben im Schnitt richtig</button>

    <!-- Create a div where the graph will take place -->
    <div class="my_dataviz"></div>
</div>

<script type="text/javascript">

    //https://www.d3-graph-gallery.com/graph/line_basic.html

    // set the dimensions and margins of the graph
    var margin = {top: 10, right: 40, bottom: 100, left: 60},
        width = 550 - margin.left - margin.right,
        height = 400 - margin.top - margin.bottom;

    // append the svg object
    var svG = d3.select(".line_chart1")
        .append("svg")
        .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform",
                "translate(" + margin.left + "," + margin.top + ")");

        // get data
        var datax = <?php echo json_encode($datax);?>;
        var datay = <?php echo json_encode($datay);?>;

        let parseTime = d3.timeParse("%m-%d");
        let y = 0;
        let x;
        let k = 0;
        var xy = [[], [], [], []];
        for (var i = 0; i < datax.length; i++) {
            for (var j = 0; j < datax[0].length; j++) {
                x = new Date(datax[i][j]).setHours(0, 0, 0, 0)
                if (datay[i][j] == 1) {
                    y++;
                } else if (datay[i][j] == 0 && y != 0) {
                    y--;
                }
                if (xy[i][0] == null) {
                    xy[i].push({x: x, y: y});
                    k++;
                } else if (k > 0) {
                    if (xy[i][k - 1].x === x) {
                        xy[i][k - 1].y = y;
                    } else {
                        xy[i].push({x: x, y: y});
                        k++;
                    }
                }
            }
            y = 0;
            k = 0;
        }

    const xScale = d3.scaleTime().rangeRound([0, width]);
    const yScale = d3.scaleLinear().rangeRound([height, 0]);
    xScale.domain(d3.extent(xy[0], function (d) {
        return new Date(d.x)
    }));
    console.log(xy);
    //yScale.domain([0, d3.max(xy[3], function(d) { return d.y }) ]);
    yScale.domain([0, 15]);
    const yaxis = d3.axisLeft().scale(yScale)
    const xaxis = d3.axisBottom().scale(xScale)
        .ticks(
            d3.timeDay.every(1))
        .tickFormat(d3.timeFormat('%b %d'));

    //add x and y axis
    svG
        .append('g')
        .attr("transform", "translate(0," + height + ")")
            .call(xaxis)
            .selectAll(".tick text")
            .attr("y", 10)
            .attr("x", -20)
            .attr("transform", "rotate(-45)");

        svG
            .append('g')
            .call(yaxis);

        // add lines for each exercise
        svG.append("path")
            .datum(xy[0])
            .attr("fill", "none")
            .attr("stroke", "#197fa7")
            .attr("stroke-width", 1.5)
            .attr("d", d3.line()
                .x(function (d) {
                    return xScale((d.x))
                })
                .y(function (d) {
                    return yScale(d.y)
                })
            )
        svG.append("path")
            .datum(xy[1])
            .attr("fill", "none")
            .attr("stroke", "#d23059")
            .attr("stroke-width", 1.5)
            .attr("d", d3.line()
                .x(function (d) {
                    return xScale((d.x))
                })
                .y(function (d) {
                    return yScale(d.y)
                })
            )
        svG.append("path")
            .datum(xy[2])
            .attr("fill", "none")
            .attr("stroke", "#129a48")
            .attr("stroke-width", 1.5)
            .attr("d", d3.line()
                .x(function (d) {
                    return xScale((d.x))
                })
                .y(function (d) {
                    return yScale(d.y)
                })
            )
        svG.append("path")
            .datum(xy[3])
            .attr("fill", "none")
            .attr("stroke", "#6c0074")
            .attr("stroke-width", 1.5)
            .attr("d", d3.line()
                .x(function (d) {
                    return xScale((d.x))
                })
                .y(function (d) {
                    return yScale(d.y)
                })
            )

    // add legend
    svG.append("circle").attr("cx", 10).attr("cy", 350).attr("r", 6).style("fill", "#197fa7")
    svG.append("circle").attr("cx", 130).attr("cy", 350).attr("r", 6).style("fill", "#d23059")
    svG.append("circle").attr("cx", 250).attr("cy", 350).attr("r", 6).style("fill", "#129a48")
    svG.append("circle").attr("cx", 370).attr("cy", 350).attr("r", 6).style("fill", "#6c0074")
    svG.append("text").attr("x", 25).attr("y", 350).text("Aufgabe 1").style("font-size", "15px").attr("alignment-baseline", "middle")
    svG.append("text").attr("x", 145).attr("y", 350).text("Aufgabe 2").style("font-size", "15px").attr("alignment-baseline", "middle")
    svG.append("text").attr("x", 265).attr("y", 350).text("Aufgabe 3").style("font-size", "15px").attr("alignment-baseline", "middle")
    svG.append("text").attr("x", 385).attr("y", 350).text("Aufgabe 4").style("font-size", "15px").attr("alignment-baseline", "middle")


    // append the svg object
    var svG = d3.select(".line_chart2")
        .append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")");

        y = 0;
        var xy = [[], [], [], []];
        for (var i = 0; i < datax.length; i++) {
            for (var j = 0; j < datax[0].length; j++) {
                x = new Date(datax[i][j]).setHours(0, 0, 0, 0)
                if (datay[i][j] == 1) {
                    y++;
                }
                if (xy[i][0] == null) {
                    xy[i].push({x: x, y: y});
                    k++;
                } else if (k > 0) {
                    if (xy[i][k - 1].x === x) {
                        xy[i][k - 1].y = y;
                    } else {
                        xy[i].push({x: x, y: y});
                        k++;
                    }
                }
            }
            y = 0;
            k = 0;
        }

    console.log(xy);
    // add x and y axis
    svG
        .append('g')
        .attr("transform", "translate(0," + height + ")")
        .call(xaxis)
        .selectAll(".tick text")
        .attr("y", 10)
        .attr("x", -20)
        .attr("transform", "rotate(-45)");

        svG
            .append('g')
            .call(yaxis);


        // add lines
        svG.append("path")
            .datum(xy[0])
            .attr("fill", "none")
            .attr("stroke", "#197fa7")
            .attr("stroke-width", 1.5)
            .attr("d", d3.line()
                .x(function (d) {
                    return xScale((d.x))
                })
                .y(function (d) {
                    return yScale(d.y)
                })
            )
        svG.append("path")
            .datum(xy[1])
            .attr("fill", "none")
            .attr("stroke", "#d23059")
            .attr("stroke-width", 1.5)
            .attr("d", d3.line()
                .x(function (d) {
                    return xScale((d.x))
                })
                .y(function (d) {
                    return yScale(d.y)
                })
            )
        svG.append("path")
            .datum(xy[2])
            .attr("fill", "none")
            .attr("stroke", "#129a48")
            .attr("stroke-width", 1.5)
            .attr("d", d3.line()
                .x(function (d) {
                    return xScale((d.x))
                })
                .y(function (d) {
                    return yScale(d.y)
                })
            )
        svG.append("path")
            .datum(xy[3])
            .attr("fill", "none")
            .attr("stroke", "#6c0074")
            .attr("stroke-width", 1.5)
            .attr("d", d3.line()
                .x(function (d) {
                    return xScale((d.x))
                })
                .y(function (d) {
                    return yScale(d.y)
                })
            )


    //add legend
    svG.append("circle").attr("cx", 10).attr("cy", 350).attr("r", 6).style("fill", "#197fa7")
    svG.append("circle").attr("cx", 130).attr("cy", 350).attr("r", 6).style("fill", "#d23059")
    svG.append("circle").attr("cx", 250).attr("cy", 350).attr("r", 6).style("fill", "#129a48")
    svG.append("circle").attr("cx", 370).attr("cy", 350).attr("r", 6).style("fill", "#6c0074")
    svG.append("text").attr("x", 25).attr("y", 350).text("Aufgabe 1").style("font-size", "15px").attr("alignment-baseline", "middle")
    svG.append("text").attr("x", 145).attr("y", 350).text("Aufgabe 2").style("font-size", "15px").attr("alignment-baseline", "middle")
    svG.append("text").attr("x", 265).attr("y", 350).text("Aufgabe 3").style("font-size", "15px").attr("alignment-baseline", "middle")
    svG.append("text").attr("x", 385).attr("y", 350).text("Aufgabe 4").style("font-size", "15px").attr("alignment-baseline", "middle")


    // bar plot how many tests a day / how many percent correct per day

    //https://www.d3-graph-gallery.com/graph/barplot_button_data_hard.html

    var dataTests = <?php echo json_encode($dataTests);?>;
    var dataCorrect = <?php echo json_encode($dataCorrect);?>;
    var datax2 = <?php echo json_encode($datax2);?>;

    var tests = [];
    var correct = [];
    for (var i = 0; i < datax2.length; i++) {
        tests.push({x: datax2[i], y: dataTests[i]});
        correct.push({x: datax2[i], y: dataCorrect[i]});
    }

    console.log(tests);
    console.log(correct);
    // // set the dimensions and margins of the graph
    // var margin = {top: 30, right: 30, bottom: 70, left: 60},
    //     width = 460 - margin.left - margin.right,
    //     height = 400 - margin.top - margin.bottom;

    // append the svg object to the body of the page
    var svg = d3.select(".my_dataviz")
        .append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")");

    // Initialize the X axis
    var xScl = d3.scaleBand()
        .range([0, width])
        .padding(0.2);
    var xAxis = svg.append("g")
        .attr("transform", "translate(0," + height + ")")

    // Initialize the Y axis
    var yScl = d3.scaleLinear()
        .range([height, 0]);
    var yAxis = svg.append("g")
        .attr("class", "myYaxis")


    // A function that create / update the plot for a given variable:
    function update(data, color) {

        // Update the X axis
        xScl.domain(data.map(function (d) {
            return d.x;
        }))
        xAxis.call(d3.axisBottom(xScl))

        // Update the Y axis
        // in order to update y axis -> change tests back to data!
        yScl.domain([0, d3.max(tests, function (d) {
            return d.y
        })]);
        yAxis.transition().duration(1000).call(d3.axisLeft(yScl));

        // Create the u variable
        var u = svg.selectAll("rect")
            .data(data)

        u
            .enter()
            .append("rect") // Add a new rect for each new elements
            .merge(u) // get the already existing elements as well
            .transition() // and apply changes to all of them
            .duration(1000)
            .attr("x", function (d) {
                return xScl(d.x);
            })
            .attr("y", function (d) {
                return yScl(d.y);
            })
            .attr("width", xScl.bandwidth())
            .attr("height", function (d) {
                return height - yScl(d.y);
            })
            .attr("fill", color)

        // If less group in the new dataset, I delete the ones not in use anymore
        u
            .exit()
            .remove()
    }

    // Initialize the plot with the first dataset
    update(tests, "#197fa7")


</script>
