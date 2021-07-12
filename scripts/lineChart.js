// https://www.d3-graph-gallery.com/graph/line_basic.html
// https://www.d3-graph-gallery.com/graph/line_filter.html

/**
 * draw line chart
 */
const LineChart = function LineChart(selector) {

    // append the svg object; viewbox & aspect ratio used to make svg responsive
    const svg = d3.select(selector)
        .append("svg")
        .attr("preserveAspectRatio", "xMinYMin meet")
        .attr("viewBox", "-50 -20 550 400")
        .classed("svg-content", true)
        .append("g");

    // add colors
    const myColor = d3.scaleOrdinal()
        .domain(allGroup)
        .range(["#197fa7", "#ffffff", "#d23059", "#130903",
            "#129a48", "#6c0074", "#ec9a42", "#8a3d0e"]);

    const xScale = d3.scaleTime().rangeRound([0, width]);
    const yScale = d3.scaleLinear().rangeRound([height, 0]);
    xScale.domain(d3.extent(lineData, function (d) {
        return new Date(d.solved_at)
    }));
    yScale.domain([0, d3.max(lineData, function (d) {
        return d.correctness
    })]);
    const yAxis = d3.axisLeft().scale(yScale);
    const xAxis = d3.axisBottom().scale(xScale)
        .ticks(
            d3.timeDay.every(1))
        .tickFormat(d3.timeFormat('%d. %m. %Y'));

    //add x and y axis
    svg
        .append('g')
        .attr("transform", "translate(0," + height + ")")
        .call(xAxis)
        .selectAll(".tick text")
        .attr("y", 10)
        .attr("x", -20)
        .attr("transform", "rotate(-45)");

    svg
        .append('g')
        .call(yAxis);

    // get data for updateLines()
    const sumstat = d3.nest() // nest function allows to group the calculation per level of a factor
        .key(function (d) {
            return d.questionId;
        })
        .entries(lineData);

    let lines = null;

    /**
     * Draw one line per group
     */
    function updateLines() {
        // remove old lines
        if (lines != null) {
            lines.remove();
        }
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
            });
    }

    // draw invisible single line -> needed for single line update function
    const line =
        svg.append("path")
            .datum(lineData.filter(function (d) {
                return d.questionId == allGroup[0]
            }))
            .attr("fill", "none")
            .attr("stroke", function (d) {
                return myColor(lineData.questionId)
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

    /**
     *  updates single line of plot
     *  @param selectedGroup exercise number
     */
    function updateLine(selectedGroup) {
        // remove old lines
        lines.remove();

        // Create new data with the selection
        const dataFilter = lineData.filter(function (d) {
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

    // add the options to the select button
    d3.select("#selectButton")
        .selectAll('myOptions')
        .data(allGroup)
        .enter()
        .append('option')
        .text(function (d) {
            return "Aufgabe " + (parseInt(d) + 1);
        }) // text shown in the menu
        .attr("value", function (d) {
            return d;
        }); // corresponding value returned by the button

    // When the select button is changed, run the updateLine function
    d3.select("#selectButton").on("change", function (d) {
        // recover the option that has been chosen
        const selectedOption = d3.select(this).property("value");
        // run the updateLine function with this selected option
        updateLine(selectedOption);
    })

    // When the select button is changed, run the updateLine function
    d3.select("#buttonLine").on("click", function (d) {
        // run the updateLine function with this selected option
        updateLines();
    })

    // add legend
    let x = 10;
    let y = 350;
    for (i = 0; i < allGroup.length; i++) {
        if (i == allGroup.length / 2) {
            x = 10;
            y = 370;
        }
        svg.append("circle").attr("cx", x).attr("cy", y).attr("r", 6).style("fill", myColor(i));
        svg.append("text").attr("x", x + 15).attr("y", y).text("Aufgabe " + (parseInt(allGroup[i]) + 1)).style("font-size", "15px").attr("alignment-baseline", "middle");
        x += 120;
    }

    // initialize plot -> draw lines
    updateLines();

    return svg;
}