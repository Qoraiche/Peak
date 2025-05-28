<template>
  <div v-if="linkData?.link" class="editorjs-link">
    <a :href="linkData.link" class="block p-4 border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition"
       rel="noopener noreferrer"
       target="_blank">
      <div v-if="linkData.meta?.image" class="mb-2">
        <img :alt="linkData.meta.title || 'Link preview'" :src="linkData.meta.image"
             class="rounded-lg w-full max-h-40 object-cover">
      </div>

      <h4 :class="{'mt-0!': !linkData.meta?.image}" class="font-semibold text-base text-blue-600 dark:text-blue-400">
        {{ linkData.meta?.title || linkData.link }}
      </h4>

      <p v-if="linkData.meta?.description" class="text-sm text-gray-600 dark:text-gray-300">
        {{ linkData.meta.description }}
      </p>
      <span class="text-xs text-gray-500 dark:text-gray-400">
        {{ linkData.link }}
      </span>
    </a>
  </div>
  <div v-else class="text-gray-500 dark:text-gray-400">Invalid link</div>
</template>

<script setup>
import {computed, defineProps} from "vue";

const props = defineProps({
  link: String,
  meta: Object,
});

// Ensure linkData is always defined, preventing errors
const linkData = computed(() => props.link ? props : {link: '', meta: {}});
</script>

<style scoped>
.editorjs-link {
  max-width: 600px;
}
</style>
