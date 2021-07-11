//https://www.d3-graph-gallery.com/graph/barplot_button_data_hard.html
var svg;
var xScl;
var xAxis
var yScl;
var yAxis
const BarChart = function BarChart(selector, tests, correct, testsAll, correctAll) {


// append the svg object to the body of the page
    svg = d3.select(selector)
        .append("svg")
        .attr("preserveAspectRatio", "xMinYMin meet")
        .attr("viewBox", "-35 -20 500 400")
        .classed("svg-content", true)
        .append("g");

// Initialize the X axis
    xScl = d3.scaleBand()
        .range([0, width])
        .padding(0.2);
    xAxis = svg.append("g")
        .attr("transform", "translate(0," + height + ")")

// Initialize the Y axis
    yScl = d3.scaleLinear()
        .range([height, 0]);
    yAxis = svg.append("g")
        .attr("class", "myYaxis")


    // Initialize the plot with the first dataset
    update(tests, '#ec9a42', testsAll, '#6c0074');
    return svg;
}

// A function that creates/updates the plot for a given variable:
function update(data, color, dataGroup, colorGroup) {

    svg.selectAll("circle.legend").remove();
    svg.selectAll("text.legend").remove();


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
            return xScl(d.date) + 40;
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
    svg.append("circle").attr("cx", 150).attr("cy", 350).attr("r", 6).style("fill", color).attr("class", "legend");
    svg.append("text").attr("x", 165).attr("y", 350).text("Du").style("font-size", "15px").attr("alignment-baseline", "middle").attr("class", "legend");
    svg.append("circle").attr("cx", 270).attr("cy", 350).attr("r", 6).style("fill", colorGroup).attr("class", "legend");
    svg.append("text").attr("x", 285).attr("y", 350).text("Gruppe").style("font-size", "15px").attr("alignment-baseline", "middle").attr("class", "legend");


}
