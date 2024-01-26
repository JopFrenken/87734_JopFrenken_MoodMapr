<template>
    <navbar></navbar>
    <div class="past-moods-container d-flex flex-column align-items-center">
        <h1 class="text-center fw-bold">Past Moods</h1>
        <div class="moods-list w-100 d-flex flex-column align-items-center mt-4">
            <router-link :to="getMoodRoute(mood)" class="my-4 past-mood d-flex justify-content-between"
                v-for="mood in moods" :key="mood.id">
                <span class="mood-text fw-bold">{{ mood.mood }}</span>
                <span class="mood-date d-flex align-items-center fw-bold">{{ transformedDate(mood.updated_at) }}</span>
            </router-link>
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
import navbar from "../components/NavBar.vue";
export default {
    components: {
        navbar
    },

    data() {
        return {
            moods: [],
            currentPage: 1,
            totalPages: 1,
            lastPage: 1,
        }
    },

    mounted() {
        this.getPastMoods();
    },

    methods: {
        async getPastMoods(page = 1) {
            try {
                const response = await moodApi.getAllMoods(page);
                this.moods = response.data.moods;
                this.currentPage = response.data.current_page;
                this.totalPages = response.data.last_page;


            } catch (error) {
                console.error(error);
            }
        },

        getMoodRoute(mood) {
            const today = new Date().toISOString().split('T')[0];
            const moodDate = new Date(mood.updated_at).toISOString().split('T')[0];

            return today === moodDate ? `/mood/${mood.id}` : `/pastmood/${mood.id}`;
        },
    },

    computed: {
        transformedDate() {
            return function (updatedAt) {
                const dateObject = new Date(updatedAt);
                const formattedDate =
                    ("0" + (dateObject.getMonth() + 1)).slice(-2) +
                    "/" +
                    ("0" + dateObject.getDate()).slice(-2) +
                    "/" +
                    dateObject.getFullYear();

                return formattedDate;
            };
        },

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
    }
}
</script>

<style></style>