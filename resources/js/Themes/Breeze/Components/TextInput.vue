<script setup>
import {onMounted, ref} from 'vue';

defineProps({
  modelValue: String,
  error: Boolean,
  type: {
    default: 'text',
    type: String,
  }
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus();
  }
});

defineExpose({focus: () => input.value.focus()});
</script>

<template>
  <input
      ref="input"
      :class="{'border-red-500' : error,'appearance-none rounded-sm border border-gray-300 bg-white checked:border-blue-600 checked:bg-blue-600 indeterminate:border-blue-600 indeterminate:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto': type === 'checkbox', 'bg-white border-gray-300 focus:border-blue-300 focus:ring-3 focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200 rounded-md shadow-xs mt-1 block w-full focus:bg-gray-50':type !== 'checkbox' && type !== 'color'}"
      :type="type"
      :value="modelValue"
      autocomplete="off"
      @input="$emit('update:modelValue', $event.target.value)"
  >
</template>
