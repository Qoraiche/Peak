<script setup>

const props = defineProps({
  modelValue: String,
  type: 'text'
});

const emit = defineEmits(['update:modelValue']);

const updateValue = (event) => {
  emit('update:modelValue', event.target.value)
};

</script>

<template>
    <textarea :value="modelValue" class="block p-2 text-sm text-gray-900 border border-gray-300 rounded-lg w-full focus:bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
              @input="updateValue">
    </textarea>
</template>

<style scoped>

</style>
