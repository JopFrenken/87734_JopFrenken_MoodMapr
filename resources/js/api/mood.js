import axios from 'axios';
import Cookies from 'js-cookie';
const apiUrl = window.apiBaseUrl;

async function storeMood(mood) {
    mood.userId = Cookies.get('id');
    try {
        const response = await axios.post(
            `${apiUrl}/mood`,
            mood,
            {
                headers: {
                    'Content-Type': 'application/json',
                },
            }
        )

        return response;
    } catch (error) {
        console.error(error);
    }
}

export default {
    storeMood
}