<template>
    <navbar></navbar>
    <div class="outer-container">
        <div class="inner-container mood-inner-container">
            <div class="moodview-page-container d-flex flex-column align-items-center">
                <h1 class="fw-bold">Here is how you felt in {{ year }}</h1>
                <div class="mood-container d-flex flex-column align-items-center w-100 past-moodview-container">
                    <PieChart v-if="piechartShown" :moods="moods"></PieChart>
                    <BarChart v-else :moods="moods"></BarChart>
                </div>
                <div class="buttons-container mt-5 d-flex justify-content-between w-100">
                    <button class="link text-center" @click="toggleChartStatus"><span>View Bar Chart</span></button>
                    <router-link class="link text-center" to="/weekly"><span>Past Weeks</span></router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import PieChart from '../components/PieChart.vue';
import BarChart from '../components/BarChart.vue';
import moodApi from '../api/mood.js';
import navbar from "../components/NavBar.vue";
export default {
    data() {
        return {
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
        this.getWeekData();
    },

    methods: {
        async getWeekData() {
            let moods = await moodApi.getMoodsByWeek(this.$route.query.startWeek, this.$route.query.endWeek, this.$route.query.year);
            this.moods = moods.data.moods;
        },

        toggleChartStatus() {
            this.piechartShown = !this.piechartShown;
        }
    }
}
</script>

<style></style>