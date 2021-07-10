//https://www.d3-graph-gallery.com/graph/line_basic.html
// append the svg object

const LineChart = function LineChart(selector, data, allGroup) {
    var svg = d3.select(selector)
        .append("svg")
        .attr("width", width + margin.left + margin.right)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform",
            "translate(" + margin.left + "," + margin.top + ")");

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
    svg
        .append('g')
        .attr("transform", "translate(0," + height + ")")
        .call(xaxis)
        .selectAll(".tick text")
        .attr("y", 10)
        .attr("x", -20)
        .attr("transform", "rotate(-45)");

    svg
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
        svg.append("path")
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

    var lines = svg.selectAll(".line")
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
        lines = svg.selectAll(".line")
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
        svg.append("circle").attr("cx", x).attr("cy", y).attr("r", 6).style("fill", myColor(i));
        svg.append("text").attr("x", x + 15).attr("y", y).text("Aufgabe " + (parseInt(allGroup[i]) + 1)).style("font-size", "15px").attr("alignment-baseline", "middle");
        x += 120;
    }
    return svg;
}