<template>
    <div class="pie-chart-container">
        <div class="piechart">
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            moodsCount: []
        }
    },

    props: {
        moods: Array
    },

    watch: {
        moods: {
            immediate: true,
            handler(newMoods) {
                if (newMoods.length > 0) {
                    newMoods.forEach(mood => {
                        if (!this.moodsCount.some(item => item.mood === mood.mood)) {
                            this.moodsCount.push({
                                count: newMoods.filter(item => item.mood === mood.mood).length,
                                mood: mood.mood
                            });
                        }
                    })
                    this.createChart();
                }
            }
        }
    },

    mounted() {
        this.createChart();
    },

    methods: {
        createChart() {
            d3.select('svg').remove();
            let width = 450
            let height = 450
            let margin = 40

            var radius = Math.min(width, height) / 2 - margin;

            var svg = d3.select(".piechart")
                .append("svg")
                .attr("width", width)
                .attr("height", height)
                .append("g")
                .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

            var data = {};

            this.moodsCount.forEach((mood) => {
                data[mood.mood] = mood.count
            });

            // Convert data object into an array of objects
            var data_ready = Object.entries(data).map(([key, value]) => ({ key, value }));

            var color = d3.scaleOrdinal()
                .domain(data_ready.map(d => d.key))
                .range(["#61B3EF", "#6BC754", "#E27171", "#FFC530", "#EF71F1"]);

            var pie = d3.pie()
                .value(function (d) { return d.value; });

            var arc = d3.arc()
                .innerRadius(0)
                .outerRadius(radius);

            svg.selectAll('slice')
                .data(pie(data_ready))
                .enter()
                .append('path')
                .attr('d', arc)
                .attr('fill', function (d) { return (color(d.data.key)); })
                .on("mouseover", function (d) {
                    const tooltip = d3.select('.tooltip');
                    tooltip.style("display", "block");
                })
                .on("mousemove", function (event, d) {
                    const tooltip = d3.select('.tooltip');
                    tooltip.html(`${d.data.key}: ${d.data.value}`)
                        .style("left", (event.pageX + 10) + "px")
                        .style("top", (event.pageY - 10) + "px");
                })
                .on("mouseout", function () {
                    d3.select("#pie-tooltip").style("display", "none");
                });

            d3.select(".pie-chart-container")
                .append("div")
                .attr("id", "pie-tooltip")
                .attr("class", "tooltip");

            var legend = d3.select(".pie-chart-container")
                .append("div")
                .attr("class", "legend")
                .selectAll("div")
                .data(data_ready)
                .enter()
                .append("div")
                .attr("class", "legend-item");

            legend.append("span")
                .attr("class", "legend-color")
                .style("background-color", function (d) {
                    return color(d.key);
                });

            legend.append("span")
                .attr("class", "legend-text")
                .text(function (d) {
                    return d.key;
                });

        },
    },
}
</script>

<style>
.tooltip {
    position: absolute;
    width: 75px;
    height: 30px;
    z-index: 999;
    opacity: 1;
    background-color: #000000;
    border: 1px solid #ccc;
    padding: 5px;
    display: none;
}

.legend {
    display: flex;
    justify-content: center;
    margin-top: 10px;
    gap: 20px;
}

.legend-item {
    display: flex;
    align-items: center;
}

.legend-color {
    width: 20px;
    height: 20px;
    margin-right: 5px;
}

.legend-text {
    color: black;
}
</style>