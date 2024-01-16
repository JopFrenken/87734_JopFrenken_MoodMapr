// router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Cookies from 'js-cookie';
import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        meta: { requiresAuth: true },
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
        meta: { requiresAuth: false },
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: { requiresAuth: false },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});


// Navigation guard to check authentication status
router.beforeEach((to, from, next) => {
    const isAuthenticated = !!Cookies.get('token');
    // Check if the route requires authentication
    if (to.meta.requiresAuth) {
        if (!isAuthenticated) {
            next('/login');
        } else {
            next();
        }
    } else {
        // If the route does not require authentication, proceed
        if (isAuthenticated) {
            next('/');
        }
        next();
    }
});

export default router;
