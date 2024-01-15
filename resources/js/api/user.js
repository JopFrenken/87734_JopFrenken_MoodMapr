import axios from 'axios';
const apiUrl = `${window.apiBaseUrl}/user`;

async function register(user) {
    try {
        const response = await axios.post(
            apiUrl,
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

export default {
    register
}