<template>
    <div class="navbar-container w-100" :class="{ visible: navbarVisible }">
        <i @click="toggleNavbar" v-if="navbarVisible == false" class="fa-solid fa-bars"></i>
        <div v-else class="navbar d-flex justify-content-around">
            <div class="navbar-item"><i @click="toggleNavbar" class="fa-solid fa-x"></i></div>
            <router-link to="/" class="navbar-item">Home</router-link>
            <router-link to="/pastmoods" class="navbar-item">Past Moods</router-link>
            <router-link to="/weekly" class="navbar-item">Weekly Moods</router-link>
            <router-link to="/monthly" class="navbar-item">Monthly Moods</router-link>
            <span class="navbar-item" @click="logout">Logout</span>
        </div>
    </div>
</template>

<script>
import Cookies from 'js-cookie';
import router from '@/router';
export default {
    data() {
        return {
            navbarVisible: false
        }
    },

    methods: {
        toggleNavbar() {
            this.navbarVisible = !this.navbarVisible;
        },

        logout() {
            Cookies.remove('token');
            Cookies.remove('token');
            router.replace('/login')
        }
    }
}
</script>

<style></style>