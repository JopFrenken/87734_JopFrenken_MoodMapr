<template>
    <div class="past-months-container d-flex flex-column align-items-center">
        <h1 class="text-center fw-bold">Past Moods</h1>
        <div class="moods-list w-100 d-flex flex-column align-items-center mt-4">
            <div v-for="(week, year) in weeks" :key="year" class="moods-list">
                <router-link to="/" class="week-link d-flex justify-content-center past-mood">
                    <div class="mood-text fw-bold">{{ week.lastMoodWeek }}</div>
                </router-link>
            </div>
        </div>
        <div class="pagination-container mt-4">
            <button :disabled="currentPage === 1" @click="getPastMoods(currentPage - 1)">&lt;</button>
            <span v-for="page in displayPages" :key="page">
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
            weeks: []
        };
    },

    mounted() {
        this.getPastMoods();
    },

    methods: {
        async getPastMoods(page = 1) {
            try {
                const response = await moodApi.getMoods();
                const moods = response.data.moods;
                const weeks = await moodApi.getWeek(moods);
                const uniqueWeeks = this.getUniqueWeeks(weeks.data.weeks);
                this.weeks = uniqueWeeks;
            } catch (error) {
                console.error(error);
            }
        },

        getUniqueWeeks(weeks) {
            const uniqueWeeks = [];
            const seenWeeks = new Set();

            for (const week of weeks) {
                const weekString = `${week.lastMoodWeek}-${week.lastMoodYear}`;

                if (!seenWeeks.has(weekString)) {
                    seenWeeks.add(weekString);
                    uniqueWeeks.push(week);
                }
            }

            return uniqueWeeks;
        }
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
    },
};
</script>

<style></style>