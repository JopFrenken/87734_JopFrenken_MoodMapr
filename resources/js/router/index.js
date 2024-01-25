// router/index.js
import { createRouter, createWebHistory } from 'vue-router';
import Cookies from 'js-cookie';
import axios from 'axios';
const apiUrl = window.apiBaseUrl;

import Home from '../views/Home.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import ExpressMood from '../views/ExpressMood.vue';
import ViewMood from '../views/ViewMood.vue';
import PastMood from '../views/PastMood.vue';
import PastMoods from '../views/PastMoodList.vue';
import MonthyList from '../views/MonthlyList.vue';
import WeeklyList from '../views/WeeklyList.vue';
import EditMood from '../views/EditMood.vue';
import Week from '../views/WeeklyView.vue';
import Month from '../views/MonthlyView.vue';

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
    {
        path: '/express',
        name: 'ExpressMood',
        component: ExpressMood,
        meta: { requiresAuth: true },
    },
    {
        path: '/mood/:id',
        name: 'ViewMood',
        component: ViewMood,
        meta: { requiresAuth: true }
    },
    {
        path: '/pastmoods',
        name: 'PastMoods',
        component: PastMoods,
        meta: { requiresAuth: true }
    },
    {
        path: '/pastmood/:id',
        name: 'PastMood',
        component: PastMood,
        meta: { requiresAuth: true }
    },
    {
        path: '/monthly',
        name: 'MonthlyList',
        component: MonthyList,
        meta: { requiresAuth: true }
    },
    {
        path: '/weekly',
        name: 'WeeklyList',
        component: WeeklyList,
        meta: { requiresAuth: true }
    },
    {
        path: '/editmood',
        name: 'EditMood',
        component: EditMood,
        meta: { requiresAuth: true }
    },
    {
        path: '/week',
        name: 'Week',
        component: Week,
        meta: { requiresAuth: true }
    },
    {
        path: '/month',
        name: 'Month',
        component: Month,
        meta: { requiresAuth: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});


// Navigation guard to check authentication status
router.beforeEach((to, from, next) => {
    const isAuthenticated = !!Cookies.get('token');
    const date = new Date();

    // Check if the route requires authentication
    if (to.meta.requiresAuth) {
        if (!isAuthenticated) {
            next('/login');
        } else {
            axios.post(`${apiUrl}/getDate`, { id: Cookies.get('id') })
                .then(response => {
                    const lastMoodDate = new Date(response.data.lastMoodDate);
                    const isToday = lastMoodDate.toISOString().split('T')[0] === date.toISOString().split('T')[0];

                    if (to.meta.requiresMatchingDate && to.params.id) {
                        // If the route requires a matching date, fetch the date for the specific mood
                        axios.get(`${apiUrl}/mood/${to.params.id}`)
                            .then(response => {
                                console.log(response);
                                const moodDate = new Date(response.data.mood.created_at);
                                const isMatchingDate = moodDate.toISOString().split('T')[0] === date.toISOString().split('T')[0];

                                if (isMatchingDate) {
                                    console.log('Today matches mood creation date. Proceeding.');
                                    next();
                                } else {
                                    console.log('Today does not match mood creation date. Redirecting to home.');
                                    next('/');
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching mood date:', error);
                                next('/');
                            });
                    } else if (isToday && to.name == "ExpressMood") {
                        console.log('Today matches last mood date. Redirecting to home.');
                        next('/');
                    } else {
                        next();
                    }
                })
                .catch(error => {
                    console.error('Error fetching date:', error);
                    next('/');
                });
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
