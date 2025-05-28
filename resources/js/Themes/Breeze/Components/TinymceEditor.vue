<template>
  <TinyEditor
      v-model="modelValue"
      v-bind="editorProps"
  />
</template>

<script setup>

import {computed, defineProps, ref, watch} from 'vue';
import {usePage} from "@inertiajs/vue3";

// Props passed from the parent component
const props = defineProps({
  modelValue: String, // This is the value bound to v-model
  init: {
    type: Object,
    default: () => ({}), // Default to an empty object
  },
});

// Define the default API key (can be customized via props if needed)
const apiKey = usePage().props.front.api_keys.tinymce;

console.log(apiKey);

// Define the default init options
const defaultInit = {
  height: 470,
  placeholder: 'Write something...',
  branding: false,
  toolbar_mode: 'sliding',
  plugins: [
    'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount'
  ],
  toolbar: 'code | undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
}

// Merge the provided init options with the default ones
const editorInit = computed(() => ({
  ...defaultInit,
  ...props.init, // Merge external init options
}))

// Bind the editor props
const editorProps = computed(() => ({
  apiKey,
  init: editorInit.value,
}))

// Sync the modelValue with the internal state (two-way binding)
const modelValue = ref(props.modelValue);

const emit = defineEmits();

// Watch for changes and emit updates to parent
watch(modelValue, (newValue) => {
  // Emit the updated value to the parent (this ensures two-way binding)
  emit('update:modelValue', newValue)
});

</script>

<style scoped>
/* Your styles go here */
</style>
