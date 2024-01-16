import axios from 'axios';
import Cookies from 'js-cookie';
const apiUrl = window.apiBaseUrl;

async function register(user) {
    try {
        const response = await axios.post(
            `${apiUrl}/user`,
            user,
            {
                headers: {
                    'Content-Type': 'application/json',
                },
            }
        );
        return response;
    } catch (error) {
        console.error(error.response.data);
    }
}

async function login(user) {
    try {
        const response = await axios.post(
            `${apiUrl}/login`,
            user,
            {
                headers: {
                    'Content-Type': 'application/json',
                },
            }
        );

        console.log(response.data.token);
        if (!response.data.success) {
            console.error('Something went wrong with the login');
            return;
        }

        Cookies.set('token', response.data.token);
        return response.data

    } catch (error) {
        console.error(error.response.data);
    }
}

export default {
    register,
    login
}