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
                    <div class="sad emoji d-flex flex-column align-items-center" @click="toggleBorder('sad')">
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
                    <textarea maxlength="100" v-model="mood.moreInfo" class="mt-4" cols="80" rows="4"
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
                selectedItem: "",
                moreInfo: ""
            }
        }
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

        async sendMood() {
            if (this.mood.selectedItem === "") {
                console.error("Please select a mood.");
                return;
            }

            let mood = await moodApi.storeMood(this.mood);
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