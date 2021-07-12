// https://www.d3-graph-gallery.com/graph/pie_changeData.html

/**
 * draw pie chart
 */
const PieChart = function PieChart(selector) {

    // width, height and margin
    var widthPie = 550;
    heightPie = 500;
    marginPie = 40;

    // The radius of the pieplot is half the width or half the height (smallest one). subtract a bit of margin.
    var radius = Math.min(widthPie, heightPie) / 2 - marginPie;

    // append the svg object; viewbox & aspect ratio used to make svg responsive
    var svg = d3.select(selector)
        .append("svg")
        .attr("preserveAspectRatio", "xMinYMin meet")
        .attr("viewBox", "-275 -250 550 500")
        .classed("svg-content", true)
        .append("g");

    var index = {a: 0, b: 1}

    // set the color scale
    var colorPie = d3.scaleOrdinal()
        .domain(["a", "b"])
        .range(["#129a48", "#d23059"]);

    // A function that updates the pie plot
    function updatePie(selectedGroup, data) {

        // delete old annotations
        svg.selectAll('text').remove();

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
            }) // This makes sure that group order remains the same in the pie chart
        var data_ready = pie(d3.entries(dataFilter));

        var arc = d3.arc()
            .innerRadius(0)
            .outerRadius(radius);

        console.log((data_ready));
        // map to data
        var u = svg.selectAll("path")
            .data(data_ready);

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
            .attr("class", "slice");

        // add annotations to pie slices
        var v = svg.selectAll("path.text")
            .data(data_ready);

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
            .style("opacity", function (d) {
                if (d.data.value.percent == 0) {
                    return 0;
                }
            });

        // remove the slices that are not present anymore
        u
            .exit()
            .remove();
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
        }) // text shown in the menu
        .attr("value", function (d) {
            return d;
        }); // corresponding value returned by the button

    // When the button is changed, run the updatePie function
    d3.select("#selectButtonPie").on("change", function (d) {
        // recover the option that has been chosen
        var selectedOption = d3.select(this).property("value");

        // only show misconception for exercise 5
        if (selectedOption == 4) {
            document.getElementById("misconceptionAll").style.visibility = "visible";
            document.getElementById("misconception5").style.visibility = "hidden";
        } else {
            document.getElementById("misconceptionAll").style.visibility = "hidden";
            document.getElementById("misconception5").style.visibility = "hidden";
        }

        // run the updatePie function with this selected option
        updatePie(selectedOption, dataPercent);
    });

    d3.select("#selectButtonPie5")
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

    // When the button is changed, run the updatePie function
    d3.select("#selectButtonPie5").on("change", function (d) {
        // recover the option that has been chosen
        var selectedOption = d3.select(this).property("value");

        // only show misconception for exercise 5
        if (selectedOption == 4) {
            document.getElementById("misconception5").style.visibility = "visible";
            document.getElementById("misconceptionAll").style.visibility = "hidden";
        } else {
            document.getElementById("misconception5").style.visibility = "hidden";
            document.getElementById("misconceptionAll").style.visibility = "hidden";
        }

        // run the updatePie function with this selected option
        updatePie(selectedOption, dataPercentLast5);
    });

    return svg;
}