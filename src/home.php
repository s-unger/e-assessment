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
?>
<script src="https://d3js.org/d3.v4.min.js"></script>
<script src="https://rawgit.com/tpreusse/radar-chart-d3/master/src/radar-chart.js"></script>


<div class="content">
    <form action="test.php" method="post">
        <input class="btn" type="submit" name="newTest" value="Mach den Test"/>
    </form>
    <div style="display:flex; justify-content: space-evenly;">
        <div>
            <select id="selectButton"></select>
            <button id="buttonLine" onclick="updateLines()">Alle Aufgaben</button>
        </div>
        <div>
            <button id="button1" onclick="update(tests, '#f9c596', testsAll, '#ec9a42')">Wie viele Tests pro Tag
            </button>
            <button id="button2" onclick="update(correct, '#197fa7', correctAll, '#129a48')">Wie viele Aufgaben im
                Schnitt richtig
            </button>
        </div>
        <div>
            <span>Alle Tests:</span>
            <select id="selectButtonPie"></select>
            </br>
            <span>Letzten 5 Tests:</span>
            <select id="selectButtonPie5"></select>
        </div>
    </div>
    <div style="display:flex; align-items: flex-end;">
        <div class="line_chart">
            <p class="headline">Hier siehst du alle deine Versuche aus dem Testmodus. (Verbesserung/Verschlechterung
                über Zeit)</p>
            <p class="headline">Falsch beantwortete Fragen wurden von der Anzahl richtig beantworteter Fragen
                abgezogen.</p>

        </div>
        <div class="bar_chart"></div>
        <div id="pie_chart"></div>
    </div>
</div>

<script type="text/javascript">
    // set the dimensions and margins for the graphs
    var margin = {top: 10, right: 40, bottom: 100, left: 60},
        width = 550 - margin.left - margin.right,
        height = 400 - margin.top - margin.bottom;

    // line chart
    //https://www.d3-graph-gallery.com/graph/line_basic.html
    // append the svg object
    var svG = d3.select(".line_chart")
        .append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")");

    // get data
    var data = <?php echo json_encode($dataCorrectnessOverTime);?>;

    var allGroup = d3.map(data, function (d) {
        return (d.questionId)
    }).keys();

    var myColor = d3.scaleOrdinal()
        .domain(allGroup)
        .range(["#197fa7", "#f9c596", "#d23059", "#130903",
            "#129a48", "#6c0074", "#ec9a42", "#8a3d0e"]);

    console.log(data);

    const xScale = d3.scaleTime().rangeRound([0, width]);
    const yScale = d3.scaleLinear().rangeRound([height, 0]);
    xScale.domain(d3.extent(data, function (d) {
        return new Date(d.solved_at)
    }));
    yScale.domain([0, d3.max(data, function (d) {
        return d.correctness
    })]);
    const yaxis = d3.axisLeft().scale(yScale)
    const xaxis = d3.axisBottom().scale(xScale)
        .ticks(
            d3.timeDay.every(1))
        .tickFormat(d3.timeFormat('%d. %m. %Y'));

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

    // add the options to the button
    d3.select("#selectButton")
        .selectAll('myOptions')
        .data(allGroup)
        .enter()
        .append('option')
        .text(function (d) {
            return "Aufgabe " + (parseInt(d) + 1);
        }) // text showed in the menu
        .attr("value", function (d) {
            return d;
        }) // corresponding value returned by the button

    var line =
        svG.append("path")
            .datum(data.filter(function (d) {
                return d.questionId == allGroup[0]
            }))
            .attr("fill", "none")
            .attr("stroke", function (d) {
                return myColor(data.questionId)
            })
            .attr("stroke-width", 1.5)
            .attr("d", d3.line()
                .x(function (d) {
                    return xScale(new Date(d.solved_at))
                })
                .y(function (d) {
                    return yScale(d.correctness)
                })
            )
            .style('opacity', 0);

    var sumstat = d3.nest() // nest function allows to group the calculation per level of a factor
        .key(function (d) {
            return d.questionId;
        })
        .entries(data);
    console.log(sumstat);

    var lines = svG.selectAll(".line")
        .data(sumstat)
        .enter()
        .append("path")
        .attr("fill", "none")
        .attr("stroke", function (d) {
            return myColor(d.key)
        })
        .attr("stroke-width", 1.5)
        .attr("d", function (d) {
            return d3.line()
                .x(function (d) {
                    return xScale(new Date(d.solved_at));
                })
                .y(function (d) {
                    return yScale(d.correctness);
                })
                (d.values)
        })

    // A function that updates the chart
    function updateLine(selectedGroup) {
        lines.remove();

        // Create new data with the selection

        var dataFilter = data.filter(function (d) {
            return d.questionId === selectedGroup
        });


        // Give these new data to update line
        line
            .datum(dataFilter)
            .transition()
            .duration(1000)
            .attr("d", d3.line()
                .x(function (d) {
                    return xScale(new Date(d.solved_at))
                })
                .y(function (d) {
                    return yScale(d.correctness)
                })
            )
            .attr("stroke", function (d) {
                return myColor(selectedGroup)
            })
            .style('opacity', 100);

    }

    var lines;

    // group the data: Draw one line per group
    function updateLines() {
        lines.remove();
        lines = svG.selectAll(".line")
            .data(sumstat)
            .enter()
            .append("path")
            .attr("fill", "none")
            .attr("stroke", function (d) {
                return myColor(d.key)
            })
            .attr("stroke-width", 1.5)
            .attr("d", function (d) {
                return d3.line()
                    .x(function (d) {
                        return xScale(new Date(d.solved_at));
                    })
                    .y(function (d) {
                        return yScale(d.correctness);
                    })
                    (d.values)
            })
    }

    // When the button is changed, run the updateChart function
    d3.select("#selectButton").on("change", function (d) {
        // recover the option that has been chosen
        var selectedOption = d3.select(this).property("value");
        // run the updateChart function with this selected option
        updateLine(selectedOption);
    })

    // add legend
    var x = 10;
    var y = 350;
    for (i = 0; i < allGroup.length; i++) {
        if (i == allGroup.length / 2) {
            x = 10;
            y = 370;
        }
        svG.append("circle").attr("cx", x).attr("cy", y).attr("r", 6).style("fill", myColor(i));
        svG.append("text").attr("x", x + 15).attr("y", y).text("Aufgabe " + (parseInt(allGroup[i]) + 1)).style("font-size", "15px").attr("alignment-baseline", "middle");
        x += 120;
    }

    // bar plot how many tests a day / how many percent correct per day

    //https://www.d3-graph-gallery.com/graph/barplot_button_data_hard.html

    // get data
    var tests = <?php echo json_encode($dataTests);?>;
    var correct = <?php echo json_encode($dataCorrect);?>;
    var testsAll = <?php echo json_encode($dataTestsMean);?>;
    var correctAll = <?php echo json_encode($dataCorrectMean);?>;

    // append the svg object to the body of the page
    var svg = d3.select(".bar_chart")
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


    // A function that creates/updates the plot for a given variable:
    function update(data, color, dataGroup, colorGroup) {

        svg.selectAll("circle").remove();
        svg.selectAll("text").remove();

        // Update the X axis
        if (data.length > dataGroup.length) {
            xScl.domain(data.map(function (d) {
                return d.date;
            }))
        } else {
            xScl.domain(dataGroup.map(function (d) {
                return d.date;
            }))
        }
        xAxis.call(d3.axisBottom(xScl))

        // Update the Y axis
        if (d3.max(data, function (d) {
            return parseInt(d.y)
        }) > d3.max(dataGroup, function (d) {
            return parseInt(d.y)
        })) {
            yScl.domain([0, d3.max(data, function (d) {
                return parseInt(d.y) + 1
            })]);
        } else {
            yScl.domain([0, d3.max(dataGroup, function (d) {
                return parseInt(d.y) + 1
            }) + 1]);
        }
        yAxis.transition().duration(1000).call(d3.axisLeft(yScl));

        // Create the u variable
        var u = svg.selectAll("rect.user")
            .data(data)

        u
            .enter()
            .append("rect") // Add a new rect for each new elements
            .merge(u) // get the already existing elements as well
            .transition() // and apply changes to all of them
            .duration(1000)
            .attr("x", function (d) {
                return xScl(d.date) - 2.5;
            })
            .attr("y", function (d) {
                return yScl(d.y);
            })
            .attr("width", xScl.bandwidth() / 2)
            .attr("height", function (d) {
                return height - yScl(d.y);
            })
            .attr("fill", color)
            .attr("class", "user")

        var v = svg.selectAll("rect.group")
            .data(dataGroup)
        v
            .enter()
            .append("rect") // Add a new rect for each new elements
            .merge(v) // get the already existing elements as well
            .transition() // and apply changes to all of them
            .duration(1000)
            .attr("x", function (d) {
                return xScl(d.date) + 45;
            })
            .attr("y", function (d) {
                return yScl(d.y);
            })
            .attr("width", xScl.bandwidth() / 2)
            .attr("height", function (d) {
                return height - yScl(d.y);
            })
            .attr("fill", colorGroup)
            .attr("class", "group")

        // If less group in the new dataset, I delete the ones not in use anymore
        u
            .exit()
            .remove()
        v
            .exit()
            .remove()

        // add legend
        svg.append("circle").attr("cx", 150).attr("cy", 350).attr("r", 6).style("fill", color);
        svg.append("text").attr("x", 165).attr("y", 350).text("Du").style("font-size", "15px").attr("alignment-baseline", "middle");
        svg.append("circle").attr("cx", 270).attr("cy", 350).attr("r", 6).style("fill", colorGroup);
        svg.append("text").attr("x", 285).attr("y", 350).text("Gruppe").style("font-size", "15px").attr("alignment-baseline", "middle");


    }

    // Initialize the plot with the first dataset
    update(tests, '#f9c596', testsAll, '#ec9a42');


    // pie chart
    // https://www.d3-graph-gallery.com/graph/pie_changeData.html
    // get data
    var dataPercent = <?php echo json_encode($percent);?>;
    var dataPercentLast5 = <?php echo json_encode($percentLast5);?>;

    console.log("dataPercent:");
    console.log(dataPercent);
    console.log("dataAnswers");
    console.log(<?php echo json_encode($dataAnswers);?>);
    console.log("dataPercentLast5:");
    console.log(dataPercentLast5);
    // width, height and margin
    var width2 = 450
    height2 = 450
    margin2 = 40
    // The radius of the pieplot is half the width or half the height (smallest one). I subtract a bit of margin.
    var radius = Math.min(width2, height2) / 2 - margin2

    // append the svg object to the div
    var svg2 = d3.select("#pie_chart")
        .append("svg")
        .attr("width", width2)
        .attr("height", height2)
        .append("g")
        .attr("transform", "translate(" + width2 / 2 + "," + height2 / 2 + ")");


    var index = {a: 0, b: 1}

    // set the color scale
    var colorPie = d3.scaleOrdinal()
        .domain(["a", "b"])
        .range(["#129a48", "#d23059"]);

    // A function that creates/updates the plot for a given variable:
    function updatePie(selectedGroup, data) {

        // delete old annotations
        svg2.selectAll('text').remove();

        var dataFilter = data.filter(function (d) {
            return d.questionId == selectedGroup
        });

        // Compute the position of each group on the pie:
        var pie = d3.pie()
            .value(function (d) {
                return d.value.percent;
            })
            .sort(function (a, b) {
                console.log(a);
                return d3.ascending(a.key, b.key);
            }) // This make sure that group order remains the same in the pie chart
        var data_ready = pie(d3.entries(dataFilter))

        var arc = d3.arc()
            .innerRadius(0)
            .outerRadius(radius);

        console.log((data_ready));
        // map to data
        var u = svg2.selectAll("path")
            .data(data_ready)

        // Build the pie chart: Basically, each part of the pie is a path that we build using the arc function.
        u
            .enter()
            .append('path')
            .merge(u)
            .transition()
            .attr('fill', function (d) {
                return (colorPie(d.data.key))
            })
            .attr("stroke", "white")
            .style("stroke-width", "2px")
            .style("opacity", 1)
            //http://jsfiddle.net/cyril123/k7x3cef6/
            .duration(function (d, i) {
                return i * 800;
            })
            .attrTween('d', function (d) {
                var i = d3.interpolate(d.startAngle + 0.1, d.endAngle);
                return function (t) {
                    d.endAngle = i(t);
                    return arc(d);
                }
            })
            .attr("class", "slice")

        // add annotations to pie slices
        var v = svg2.selectAll("path.text")
            .data(data_ready)
        v
            .enter()
            .append('text')
            .merge(v)
            .text(function (d) {
                return d.data.value.percent + "% " + d.data.value.correctness
            })
            .attr("transform", function (d) {
                return "translate(" + arc.centroid(d) + ")";
            })
            .style("text-anchor", "middle")
            .style("font-size", 17)
            .style("fill", 'white')

        // remove the group that is not present anymore
        u
            .exit()
            .remove()
    }

    // Initialize the plot with the first dataset
    updatePie(0, dataPercent)

    d3.select("#selectButtonPie")
        .selectAll('myOptions')
        .data(allGroup)
        .enter()
        .append('option')
        .text(function (d) {
            return "Aufgabe " + (parseInt(d) + 1);
        }) // text showed in the menu
        .attr("value", function (d) {
            return d;
        }) // corresponding value returned by the button

    // When the button is changed, run the updateChart function
    d3.select("#selectButtonPie").on("change", function (d) {
        // recover the option that has been chosen
        var selectedOption = d3.select(this).property("value");
        // run the updateChart function with this selected option
        updatePie(selectedOption, dataPercent);
    })

    d3.select("#selectButtonPie5")
        .selectAll('myOptions')
        .data(allGroup)
        .enter()
        .append('option')
        .text(function (d) {
            return "Aufgabe " + (parseInt(d) + 1);
        }) // text showed in the menu
        .attr("value", function (d) {
            return d;
        }) // corresponding value returned by the button

    // When the button is changed, run the updateChart function
    d3.select("#selectButtonPie5").on("change", function (d) {
        // recover the option that has been chosen
        var selectedOption = d3.select(this).property("value");
        // run the updateChart function with this selected option
        updatePie(selectedOption, dataPercentLast5);
    })

</script>