<script setup>

import DynamicLucideIcon from "@/Themes/Breeze/Components/DynamicLucideIcon.vue";

const props = defineProps({
  title: {
    type: String,
    default: null,
  },
  icon: {
    type: String,
    default: null
  }
});

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
  <button ref="fullscreenElement" v-tooltip="__(title)"
          class="-m-2.5 w-11 h-11 hidden md:flex justify-center items-center text-gray-400 hover:text-gray-500 rounded-full bg-green-50 hover:bg-green-100 transition duration-300"
          type="button"
          @click="toggleFullScreen">
    <span class="sr-only">
      {{ __('View notifications') }}
    </span>
    <dynamic-lucide-icon :icon="icon" size="size-4"/>
  </button>
</template>

<style scoped>

</style>
