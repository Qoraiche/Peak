<script setup>

import {onMounted, ref} from "vue";

const props = defineProps({
  modelValue: String | Number,
  type: 'text'
})

const emit = defineEmits(['update:modelValue'])
const input = ref(null);

const updateValue = (event) => {
  emit('update:modelValue', event.target.value)
}

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus();
  }
});

defineExpose({focus: () => input.value.focus()});
</script>

<template>
  <input ref="input" :type="type" :value="modelValue"
         class="block p-2 py-2 text-sm text-gray-900 border border-gray-300 rounded-md w-80 focus:bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
         @input="updateValue">
</template>

<style scoped>

</style>
