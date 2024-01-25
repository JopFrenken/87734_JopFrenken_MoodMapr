<template>
    <div class="past-months-container d-flex flex-column align-items-center">
        <h1 class="text-center fw-bold">Past Moods</h1>
        <div class="moods-list w-100 d-flex flex-column align-items-center mt-4">
            <div v-for="(groupedMonthsData, year) in  groupedMonths " :key="year" class="moods-list">
                <router-link :to="{ path: '/month', query: { month: month, year: year } }"
                    v-for="(moods, month) in  groupedMonthsData " :key="month + year"
                    class="month-year-div past-mood d-flex justify-content-center">
                    <div class="mood-text fw-bold">{{ month }}</div>
                    <div class="mood-text fw-bold mood-year"> {{ year }}</div>
                </router-link>
            </div>
        </div>
        <div class="pagination-container mt-4">
            <button :disabled="currentPage === 1" @click="getPastMoods(currentPage - 1)">&lt;</button>
            <span v-for=" page  in  displayPages " :key="page">
                <template v-if="page === '...'">{{ page }}</template>
                <button v-else @click="getPastMoods(page)" :class="{ active: page === currentPage }">{{ page }}</button>
            </span>
            <button :disabled="currentPage === totalPages" @click="getPastMoods(currentPage + 1)">&gt;</button>
        </div>
    </div>
</template>


<script>
import moodApi from '../api/mood.js';

export default {
    data() {
        return {
            moods: [],
            currentPage: 1,
            totalPages: 1,
            lastPage: 1,
            groupedMonths: {}
        };
    },

    mounted() {
        this.getPastMoods();
    },

    methods: {
        async getPastMoods(page = 1) {
            try {
                // Assuming that moodApi.getMoods() returns an array of mood objects
                const response = await moodApi.getMoods();
                const moods = response.data.moods;
                const months = await moodApi.getMonth(moods);


                // Grouping moods by year and month
                const groupedMonths = {};
                months.data.months.forEach((mood) => {
                    const { lastMoodMonth, lastMoodYear } = mood;
                    console.log(lastMoodYear);
                    if (!groupedMonths[lastMoodYear]) {
                        groupedMonths[lastMoodYear] = {};
                    }
                    if (!groupedMonths[lastMoodYear][lastMoodMonth]) {
                        groupedMonths[lastMoodYear][lastMoodMonth] = [];
                    }
                    groupedMonths[lastMoodYear][lastMoodMonth].push(mood);
                });
                this.groupedMonths = groupedMonths;

                console.log(this.groupedMonths);

            } catch (error) {
                console.error(error);
            }
        },

    },

    computed: {
        displayPages() {
            const pages = [];
            const totalPages = this.totalPages;
            const currentPage = this.currentPage;
            const maxVisiblePages = 4;

            if (totalPages <= maxVisiblePages) {
                for (let i = 1; i <= totalPages; i++) {
                    pages.push(i);
                }
            } else {
                if (currentPage <= 2) {
                    // Display 1, 2, ..., maxVisiblePages
                    for (let i = 1; i <= maxVisiblePages; i++) {
                        pages.push(i);
                    }
                } else if (currentPage >= totalPages - 1) {
                    // Display totalPages - maxVisiblePages + 1, ..., totalPages
                    for (let i = totalPages - maxVisiblePages + 1; i <= totalPages; i++) {
                        pages.push(i);
                    }
                } else {
                    // Display currentPage - 1, currentPage, currentPage + 1, ..., currentPage + 2
                    for (let i = currentPage - 1; i <= currentPage + 2; i++) {
                        pages.push(i);
                    }
                }
            }

            return pages;
        },

        sortedYears() {
            // Sort years in descending order
            return Object.keys(this.groupedMonths).sort((a, b) => b - a);
        },
    },
};
</script>

<style></style>
