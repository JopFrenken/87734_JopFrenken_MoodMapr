<template>
    <div class="reg-page-container d-flex align-items-center flex-column">
        <div class="title-container">
            <h1>Welcome to MoodMapr</h1>
        </div>
        <div class="reg-container">
            <h2 class="text-center">Login</h2>
            <form @submit.prevent="login">
                <div class="input-container">
                    <input type="text" name="username" id="username" placeholder="Username" required
                        v-model="user.username">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="input-container">
                    <input type="password" name="password" id="password" placeholder="Password" required
                        v-model="user.password">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="submit-container d-flex justify-content-center">
                    <button class="reg-sub-btn"><i class="fa-solid fa-right-to-bracket"></i>Login</button>
                </div>
                <div class="already-container d-flex justify-content-center">
                    <router-link to="/register">No account? Please register here</router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: {
                username: "",
                password: ""
            }
        }
    },

    methods: {
        login() {

        }
    }
}
</script>

<style></style>