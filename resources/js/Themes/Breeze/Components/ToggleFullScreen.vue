<script setup>

import {ArrowsPointingOutIcon} from "@heroicons/vue/24/outline/index.js";

import { ref } from 'vue';

function toggleFullScreen() {
    const elem = document.documentElement;

    if (!document.fullscreenElement) {
        elem.requestFullscreen().catch(err => {
            alert(`Error attempting to enable full-screen mode: ${err.message}`);
        });
    } else {
        document.exitFullscreen();
    }
}

</script>

<template>
    <button type="button" v-tooltip="'Full screen'" @click="toggleFullScreen" ref="fullscreenElement"
            class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500 rounded-full bg-green-50 hover:bg-green-100 transition duration-300">
        <span class="sr-only">toggle</span>
        <ArrowsPointingOutIcon class="h-6 w-6" aria-hidden="true"/>
    </button>
</template>

<style scoped>

</style>
