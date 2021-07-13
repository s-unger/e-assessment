// https://www.d3-graph-gallery.com/graph/barplot_button_data_hard.html

/**
 * draw bar chart
 */
const BarChart = function BarChart(selector) {

    // append the svg object; viewbox & aspect ratio used to make svg responsive
    let svg = d3.select(selector)
        .append("svg")
        .attr("preserveAspectRatio", "xMinYMin meet")
        .attr("viewBox", "-35 -20 500 400")
        .classed("svg-content", true)
        .append("g");

    // Initialize the X axis
    let xScale = d3.scaleBand()
        .range([0, width])
        .padding(0.2)
    let xAxis = svg.append("g")
        .attr("transform", "translate(0," + height + ")");

    // Initialize the Y axis
    let yScale = d3.scaleLinear()
        .range([height, 0]);
    let yAxis = svg.append("g")
        .attr("class", "myYaxis");

    /**
     * updates the plot
     * @param data user data
     * @param color color for user
     * @param dataGroup group data
     * @param colorGroup color for group
     */
    function update(data, color, dataGroup, colorGroup) {

        // remove old legend
        svg.selectAll("circle.legend").remove();
        svg.selectAll("text.legend").remove();

        // Update the X axis
        xScale.domain(data.map(function (d) {
            return d.date;
        }));
        xAxis.call(d3.axisBottom(xScale));

        // Update the Y axis according to dataset with the highest y value
        if (d3.max(data, function (d) {
            return parseInt(d.y)
        }) > d3.max(dataGroup, function (d) {
            return parseInt(d.y)
        })) {
            yScale.domain([0, d3.max(data, function (d) {
                return parseInt(d.y) + 1
            })]);
        } else {
            yScale.domain([0, d3.max(dataGroup, function (d) {
                return parseInt(d.y) + 1
            }) + 1]);
        }
        yAxis.transition().duration(1000).call(d3.axisLeft(yScale));

        const u = svg.selectAll("rect.user")
            .data(data);

        u
            .enter()
            .append("rect") // Add a new rect for each new element
            .merge(u) // get the already existing elements as well
            .transition() // and apply changes to all of them
            .duration(1000)
            .attr("x", function (d) {
                return xScale(d.date) - 2.5;
            })
            .attr("y", function (d) {
                return yScale(d.y);
            })
            .attr("width", xScale.bandwidth() / 2)
            .attr("height", function (d) {
                return height - yScale(d.y);
            })
            .attr("fill", color)
            .attr("class", "user");

        const v = svg.selectAll("rect.group")
            .data(dataGroup);

        v
            .enter()
            .append("rect") // Add a new rect for each new element
            .merge(v) // get the already existing elements as well
            .transition() // and apply changes to all of them
            .duration(1000)
            .attr("x", function (d) {
                return xScale(d.date) + 32;
            })
            .attr("y", function (d) {
                return yScale(d.y);
            })
            .attr("width", xScale.bandwidth() / 2)
            .attr("height", function (d) {
                return height - yScale(d.y);
            })
            .attr("fill", colorGroup)
            .attr("class", "group");

        // delete bars not in use anymore
        u
            .exit()
            .remove();
        v
            .exit()
            .remove();

        // add legend
        svg.append("circle").attr("cx", 150).attr("cy", 350).attr("r", 6).style("fill", color).attr("class", "legend");
        svg.append("text").attr("x", 165).attr("y", 350).text("Du").style("font-size", "15px").attr("alignment-baseline", "middle").attr("class", "legend");
        svg.append("circle").attr("cx", 270).attr("cy", 350).attr("r", 6).style("fill", colorGroup).attr("class", "legend");
        svg.append("text").attr("x", 285).attr("y", 350).text("Gruppe").style("font-size", "15px").attr("alignment-baseline", "middle").attr("class", "legend");
    }

    // When the select button is changed, run the updateLine function
    d3.select("#buttonBar1").on("click", function (d) {
        // run the updateLine function with this selected option
        update(tests, '#ec9a42', testsAll, '#6c0074');
    })

    // When the select button is changed, run the updateLine function
    d3.select("#buttonBar2").on("click", function (d) {
        // run the updateLine function with this selected option
        update(correct, '#197fa7', correctAll, '#129a48');
    })

    // Initialize the plot with the first dataset
    update(tests, '#ec9a42', testsAll, '#6c0074');
    return svg;
}

