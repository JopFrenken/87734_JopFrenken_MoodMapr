<template>
    <div class="outer-container">
        <div class="inner-container">
            <div class="moodview-page-container d-flex flex-column align-items-center">
                <h1 class="fw-bold">Thanks for sharing. Here is how you felt today</h1>
                <div class="mood-container d-flex flex-column align-items-center w-100">
                    <h2 class="your-mood fw-bold">Your mood</h2>
                    <div class="emoji mt-3">
                        <div :style="{ 'background-image': `url(${emojiSvg})` }" class="emoji-image"></div>
                        <p class="emoji-text text-center mt-2">{{ mood.moodText }}</p>
                    </div>
                    <div class="reasoning-container mt-5">
                        <h2 class="fw-bold text-center">Your reasoning</h2>
                        <p class="text-center mt-4" v-if="mood.moodBody == null">Nothing</p>
                        <p class="text-center mt-4" v-else>{{ mood.moodBody }}</p>
                    </div>
                </div>
                <div class="buttons-container mt-5">
                    <router-link class="link text-center" to="/"><span>Edit Mood</span></router-link>
                    <router-link class="link text-center" to="/pastmoods"><span>Past Moods</span></router-link>
                    <router-link class="link text-center" to="/"><span>Home</span></router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moodApi from '../api/mood.js';
import router from '@/router';
// svgs
import sadSvg from '../../assets/emojis/sad.svg';
import angrySvg from '../../assets/emojis/angry.svg';
import mehSvg from '../../assets/emojis/meh.svg';
import contentSvg from '../../assets/emojis/content.svg';
import happySvg from '../../assets/emojis/happy.svg';
export default {
    data() {
        return {
            mood: {
                moodText: "",
                moodBody: ""
            }
        }
    },

    mounted() {
        this.getMood();
    },

    computed: {
        emojiSvg() {
            const moodTextToSvg = {
                sad: sadSvg,
                angry: angrySvg,
                meh: mehSvg,
                content: contentSvg,
                happy: happySvg,
            };

            // Use the mapped SVG URL based on the moodText
            return moodTextToSvg[this.mood.moodText.toLowerCase()] || '';
        },
    },

    methods: {
        async getMood() {
            let mood = await moodApi.getMood(this.$route.params.id);
            this.mood.moodText = mood.data.mood.mood;
            this.mood.moodBody = mood.data.mood.mood_body;
            console.log(this.mood.moodBody);
        }
    }
}
</script>

<style></style>