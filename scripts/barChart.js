//https://www.d3-graph-gallery.com/graph/barplot_button_data_hard.html

const BarChart = function BarChart(selector, tests, correct, testsAll, correctAll) {


// append the svg object to the body of the page
    var svg = d3.select(selector)
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
    return svg;
}
