<template>
  <div>
    <select
        :id="name"
        :name="name"
        :value="modelValue"
        class="capitalize cursor-pointer block w-full rounded-md focus:bg-gray-50 py-1.5 pl-3 pr-10 text-gray-900 border-1 border-gray-200 ring-0 ring-inset ring-gray-300 focus:ring-0 focus:ring-blue-600 sm:text-sm sm:leading-6"
        @change="updateValue">

      <!-- Placeholder option -->
      <option v-if="placeholder && !modelValue" disabled selected value="">
        {{ placeholder }}
      </option>

      <!-- Dynamic options -->
      <option
          v-for="option in normalizedValues"
          :key="option.key"
          :value="option.key"
      >
        {{ option.value }}
      </option>
    </select>
  </div>
</template>

<script setup>
import {computed} from "vue";

const props = defineProps({
  modelValue: [String, Number, null],
  name: String,
  values: {
    type: [Array, Object],
    default: () => [], // Ensures values is always an array or object
  },
  placeholder: {
    type: String,
    default: "Select something...",
  },
});

const emit = defineEmits(["update:modelValue"]);

// Normalize values to always be { key, value }
const normalizedValues = computed(() => {
  if (Array.isArray(props.values)) {
    // Handle case where values is an array of strings or objects
    if (props.values.length > 0 && typeof props.values[0] === "object") {
      // If already an array of objects, return as is
      return props.values;
    }
    // If it's an array of strings, map to objects
    return props.values.map((item) => ({key: item, value: item}));
  } else if (typeof props.values === "object") {
    // Handle case where values is an object (key-value pairs)
    return Object.entries(props.values).map(([key, value]) => ({
      key: key,
      value: value,
    }));
  }
  return []; // Fallback if it's neither array nor object
});

const updateValue = (event) => {
  emit("update:modelValue", event.target.value);
};
</script>
