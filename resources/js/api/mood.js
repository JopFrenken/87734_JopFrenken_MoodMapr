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
        const userId = Cookies.get('id');
        const response = await axios.get(`${apiUrl}/mood`, { params: { page, userId } });
        return response;
    } catch (error) {
        console.error(error);
    }
}

async function getMoods() {
    try {
        const userId = Cookies.get('id');
        const response = await axios.get(`${apiUrl}/getMoods`, { params: { userId } });
        return response;
    } catch (error) {
        console.error(error);
    }
}

async function getUserMoods(id) {
    const obj = {
        id: id
    }
    try {
        const response = await axios.post(
            `${apiUrl}/getUserMoods`,
            obj,
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

async function getMoodsByMonth(month, year) {
    let userId = Cookies.get('id');
    const obj = {
        month: month,
        year: year,
        userId
    };
    try {
        const response = await axios.post(
            `${apiUrl}/getMoodsByMonth`,
            obj,
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

async function getMoodsByWeek(startWeek, endWeek, year) {
    let userId = Cookies.get('id');
    const obj = {
        startWeek: startWeek,
        endWeek: endWeek,
        year: year,
        userId
    };
    try {
        const response = await axios.post(
            `${apiUrl}/getMoodsByWeek`,
            obj,
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

async function updateMood(mood) {
    try {
        const response = await axios.put(
            `${apiUrl}/mood/${mood.id}`,
            mood,
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
    getWeek,
    updateMood,
    getUserMoods,
    getMoodsByMonth,
    getMoodsByWeek
}