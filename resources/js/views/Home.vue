<template>
    <div class="home-page-container d-flex flex-column align-items-center h-100">
        <h1 class="text-white fw-bold">Welcome!</h1>
        <div class="home-center-container h-100 d-flex justify-content-center flex-column">
            <router-link to="/express" class="log-link d-flex justify-content-center">
                <span>Log Your Daily Mood</span>
            </router-link>
            <div class="bottom-center-container d-flex justify-content-between mt-5">
                <router-link to="/" class="link text-center">
                    <span>Past Moods</span>
                </router-link>
                <router-link to="/" class="link text-center">
                    <span>Weekly Moods</span>
                </router-link>
                <router-link to="/" class="link text-center">
                    <span>Monthly Moods</span>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {

}
</script>

<style>
body,
#app {
    height: 100vh;
}
</style>