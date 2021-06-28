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

?>
<script src="https://d3js.org/d3.v4.min.js"></script>
<script type="text/javascript">
    $(function () {
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
        yScale.domain([0, 10]);
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

    });
</script>

<div class="content">
    <form action="test.php" method="post">
        <input type="submit" name="newTest" value="Mach den Test"/>
    </form>
    <div style="display:flex;">
        <div class="line_chart1">
            <p class="headline">Hier siehst du alle deine Versuche aus dem Testmodus.</p>
            <p class="headline">Falsch beantwortete Fragen wurden von der Anzahl richtig beantworteter Fragen
                abgezogen.</p>
        </div>
        <div class="line_chart2">
            <p class="headline">Hier siehst du alle deine Versuche aus dem Testmodus.</p>
            <p class="headline">Falsch beantwortete Fragen wurden nicht von der Anzahl richtig beantworteter Fragen
                abgezogen.</p>
        </div>
    </div>
</div>