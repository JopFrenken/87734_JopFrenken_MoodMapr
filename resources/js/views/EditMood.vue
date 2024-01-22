<template>
    <div class="outer-container">
        <div class="inner-container">
            <div class="express-page-container d-flex align-items-center flex-column">
                <h1 class="fw-bold">Hello, how are you feeling today?</h1>
                <div class="emojis-container">
                    <div class="happy emoji d-flex flex-column align-items-center" @click="toggleBorder('happy')">
                        <div :style="{ background: `url(${happySvg})` }" class="emoji-image"></div>
                        <span class="emotion">Happy</span>
                    </div>
                    <div class="content emoji d-flex flex-column align-items-center" @click="toggleBorder('content')">
                        <div :style="{ background: `url(${contentSvg})` }" class="emoji-image"></div>
                        <span class="emotion">Content</span>
                    </div>
                    <div class="meh emoji d-flex flex-column align-items-center" @click="toggleBorder('meh')">
                        <div :style="{ background: `url(${mehSvg})` }" class="emoji-image"></div>
                        <span class="emotion">Meh</span>
                    </div>
                    <div class="sad emoji   d-flex flex-column align-items-center" @click="toggleBorder('sad')">
                        <div :style="{ background: `url(${sadSvg})` }" class="emoji-image"></div>
                        <span class="emotion">Sad</span>
                    </div>
                    <div class="angry emoji d-flex flex-column align-items-center" @click="toggleBorder('angry')">
                        <div :style="{ background: `url(${angrySvg})` }" class="emoji-image"></div>
                        <span class="emotion">Angry</span>
                    </div>
                </div>
                <div class="context-container mt-5 d-flex flex-column align-items-center">
                    <h2 class="text-center mt-5">Want to tell us why?</h2>
                    <textarea v-model="mood.moreInfo" class="mt-4" cols="80" rows="4"
                        placeholder="Start typing here..."></textarea>
                    <button @click="sendMood" class="send-btn mt-5">Send</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moodApi from '../api/mood.js';
import router from '@/router';
import Cookies from 'js-cookie';

// svgs
import sadSvg from '../../assets/emojis/sad.svg';
import angrySvg from '../../assets/emojis/angry.svg';
import mehSvg from '../../assets/emojis/meh.svg';
import contentSvg from '../../assets/emojis/content.svg';
import happySvg from '../../assets/emojis/happy.svg';
export default {
    data() {
        return {
            sadSvg,
            angrySvg,
            mehSvg,
            contentSvg,
            happySvg,
            mood: {
                id: 0,
                selectedItem: "",
                moreInfo: ""
            },
        }
    },

    mounted() {
        this.getMostRecentMood();
    },

    methods: {
        toggleBorder(emotion) {
            const emojisContainer = this.$el.querySelector(".emojis-container");
            Array.from(emojisContainer.children).forEach(element => {
                element.style.border = "3px solid transparent"
            });

            const emojiElement = this.$el.querySelector(`.${emotion}`);

            if (emojiElement) {
                emojiElement.style.border = "3px solid black";
                this.mood.selectedItem = emotion;
            }
        },

        fillPage() {
            this.toggleBorder(this.mood.selectedItem);
        },

        async getMostRecentMood() {
            let moodArr = [];
            let moods = await moodApi.getUserMoods(Cookies.get('id'));

            if (moods.length == 0) {
                router.replace('/');
                return;
            }

            let moodValues = Object.values(moods.data.moods);
            moodValues.map(mood => {
                moodArr.push(mood.mood);
            });

            let mood = moods.data.moods[moodArr.length - 1];
            this.mood.id = mood.id;
            this.mood.selectedItem = mood.mood;
            this.mood.moreInfo = mood.mood_body;
            this.fillPage();
        },

        async sendMood() {
            if (this.mood.selectedItem === "") {
                console.error("Please select a mood.");
                return;
            }

            let mood = await moodApi.updateMood(this.mood);
            if (mood) {
                if (mood.data.success) {
                    router.replace(`/mood/${mood.data.mood.id}`);
                }
            }
        }
    }
}
</script>

<style>
* {
    color: white;
}

body {
    overflow: hidden;
}
</style>