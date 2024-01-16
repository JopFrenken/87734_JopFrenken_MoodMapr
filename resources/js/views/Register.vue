<template>
    <div class="reg-page-container d-flex align-items-center flex-column">
        <div class="title-container">
            <h1>Welcome to MoodMapr</h1>
        </div>
        <div class="reg-container">
            <h2 class="text-center">Register</h2>
            <form @submit.prevent="register">
                <div class="input-container">
                    <input type="text" name="username" id="username" placeholder="Username" required
                        v-model="user.username">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="input-container">
                    <input type="text" name="email" id="email" placeholder="Email" required v-model="user.email">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="input-container">
                    <input type="password" name="password" id="password" placeholder="Password" required
                        v-model="user.password">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="input-container">
                    <input type="password" name="password" id="con-password" placeholder="Confirm Password" required
                        v-model="confirm">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="submit-container d-flex justify-content-center">
                    <button class="reg-sub-btn"><i class="fa-solid fa-right-to-bracket"></i>Register</button>
                </div>
                <div class="already-container">
                    <span><router-link to="/login">Already have an account? Please login here</router-link></span>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import userApi from "../api/user.js";
import router from '@/router';
export default {
    data() {
        return {
            user: {
                username: "",
                email: "",
                password: "",
            },
            confirm: ""
        }
    },

    methods: {
        async register() {
            const isValidEmail = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/.test(this.user.email);
            const passwordRegex = /^(?=.*[A-Z])(?=.*\d).{6,}$/.test(this.user.password);

            if (!isValidEmail) {
                console.error('Invalid email address');
                return;
            }

            if (!passwordRegex) {
                console.error('Invalid password. The password must be minimum 6 characters with atleast 1 capital and 1 number');
                return;
            }

            if (this.user.password !== this.confirm) {
                console.error("Passwords don't match.");
                return;
            }

            let user = await userApi.register(this.user);
            if (user) {
                console.log(user.data.msg);
                if (user.data.success) router.replace('/login');
            }
        }
    }
}
</script>

<style></style>