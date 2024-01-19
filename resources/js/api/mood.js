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

async function getMood(moodId) {
    try {
        const response = await axios.get(
            `${apiUrl}/mood/${moodId}`,
        )

        return response;
    } catch (error) {
        console.error(error);
    }
}

async function getAllMoods(page = 1) {
    try {
        const response = await axios.get(`${apiUrl}/mood`, { params: { page } });
        return response;
    } catch (error) {
        console.error(error);
    }
}

async function getMoods() {
    try {
        const response = await axios.get(`${apiUrl}/getMoods`);
        return response;
    } catch (error) {
        console.error(error);
    }
}

async function getMonth(moods) {
    try {
        const response = await axios.post(
            `${apiUrl}/getMonth`,
            moods,
            {
                headers: {
                    'Content-Type': 'application/json',
                },
            }
        );
        return response;
    } catch (error) {
        console.error(error);
    }
}

async function getWeek(moods) {
    try {
        const response = await axios.post(
            `${apiUrl}/getWeek`,
            moods,
            {
                headers: {
                    'Content-Type': 'application/json',
                },
            }
        );
        return response;
    } catch (error) {
        console.error(error);
    }
}

export default {
    storeMood,
    getMood,
    getAllMoods,
    getMoods,
    getMonth,
    getWeek
}