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
  },
  url: {
    type: String,
    default: null
  }
});

</script>

<template>
  <a v-tooltip="__(title)"
     :href="url"
     class="-m-2.5 w-11 h-11 hidden md:flex justify-center items-center text-gray-400 hover:text-gray-500 rounded-full bg-blue-50 hover:bg-blue-100 transition duration-300"
     target="_blank">
    <span class="sr-only">
      {{ __('Visit website') }}
    </span>
    <dynamic-lucide-icon :icon="icon" size="size-4"/>
  </a>
</template>

<style scoped>

</style>
