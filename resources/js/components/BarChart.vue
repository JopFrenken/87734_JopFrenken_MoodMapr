<template>
    <div class="bar-chart-container">
        <div class="barchart">
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
            let width = 450
            let height = 450
            let margin = 40

            var svg = d3.select(".barchart")
                .append("svg")
                .attr("width", width)
                .attr("height", height)
                .append("g")
                .attr("transform", "translate(" + margin + "," + margin + ")");

            var data = {};

            this.moodsCount.forEach((mood) => {
                data[mood.mood] = mood.count
            });

            // Convert data object into an array of objects
            var data_ready = Object.entries(data).map(([key, value]) => ({ key, value }));

            var x = d3.scaleBand()
                .range([0, width - 2 * margin])
                .domain(data_ready.map(d => d.key))
                .padding(0.1);

            var y = d3.scaleLinear()
                .range([height - 2 * margin, 0])
                .domain([0, d3.max(data_ready, d => d.value)]);

            svg.append("g")
                .call(d3.axisLeft(y).tickFormat(d3.format(".0f")))
                .attr('class', "y-axis")

            svg.selectAll("rect")
                .data(data_ready)
                .enter()
                .append("rect")
                .attr("x", d => x(d.key))
                .attr("y", d => y(d.value))
                .attr("width", x.bandwidth())
                .attr("height", d => height - 2 * margin - y(d.value))
                .attr("fill", "#D9B8FF")
                .on("mouseover", function (d) {
                    const tooltip = d3.select('.tooltip');
                    console.log(tooltip);
                    tooltip.style("display", "block");
                })
                .on("mousemove", function (event, d) {
                    const tooltip = d3.select('.tooltip');
                    tooltip.html(`${d.key}: ${d.value}`)
                        .style("left", (event.pageX + 10) + "px")
                        .style("top", (event.pageY - 10) + "px");
                })
                .on("mouseout", function () {
                    d3.select(".tooltip").style("display", "none");
                });

            svg.selectAll(".bar-label")
                .data(data_ready)
                .enter()
                .append("text")
                .attr("class", "bar-label")
                .attr("x", d => x(d.key) + x.bandwidth() / 2)
                .attr("y", d => y(d.value) - 10)
                .attr("text-anchor", "middle")
                .attr("fill", "black")
                .text(d => d.key);

            d3.select(".bar-chart-container")
                .append("div")
                .attr("class", "tooltip");

        },
    },
}
</script>

<style>
.tooltip {
    position: absolute;
    width: 75px;
    height: 30px;
    z-index: 9;
    opacity: 1;
    background-color: #000000;
    border: 1px solid #ccc;
    padding: 5px;
    display: none;
}

.y-axis * {
    color: black
}
</style>
