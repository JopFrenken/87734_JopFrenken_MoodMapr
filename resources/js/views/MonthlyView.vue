<template>
    <navbar></navbar>
    <div class="outer-container">
        <div class="inner-container mood-inner-container">
            <div class="moodview-page-container d-flex flex-column align-items-center">
                <h1 class="fw-bold">Here is how you felt in {{ month }} {{ year }}</h1>
                <div class="mood-container d-flex flex-column align-items-center w-100 past-moodview-container">
                    <PieChart v-if="piechartShown" :moods="moods"></PieChart>
                    <BarChart v-else :moods="moods"></BarChart>
                </div>
                <div class="buttons-container mt-5 d-flex justify-content-between w-100">
                    <button class="link text-center" @click="toggleChartStatus"><span>View Bar Chart</span></button>
                    <router-link class="link text-center" to="/monthly"><span>Past Months</span></router-link>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import PieChart from '../components/PieChart.vue';
import BarChart from '../components/BarChart.vue';
import navbar from "../components/NavBar.vue";
import moodApi from '../api/mood.js';
export default {
    data() {
        return {
            month: "",
            year: "",
            moods: [],
            piechartShown: true
        }
    },

    components: {
        PieChart,
        BarChart,
        navbar
    },

    mounted() {
        this.month = this.$route.query.month;
        this.year = this.$route.query.year;
        this.getMonthData();
    },

    methods: {
        async getMonthData() {
            let moods = await moodApi.getMoodsByMonth(this.month, this.year);
            this.moods = moods.data.moods;
        },

        toggleChartStatus() {
            this.piechartShown = !this.piechartShown;
        }
    },
}
</script>

<style>
button {
    border: none
}
</style>