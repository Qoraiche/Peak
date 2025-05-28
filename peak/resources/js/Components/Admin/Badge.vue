<script setup>

import {computed} from "vue";

const props = defineProps({
  type: {
    default: 'default',
    type: [String, null],
  }
})

const classes = computed(() => {

  switch (props.type) {
    case 'info':
      return 'text-blue-700 bg-blue-100';

    case 'default':
      return 'text-gray-700 bg-gray-100';

    case 'success':
      return 'text-green-700 bg-green-100';

    case 'danger':
      return 'text-red-700 bg-red-100';

    case 'alert':
      return 'text-orange-700 bg-orange-100';
  }
});

</script>

<template>
    <span :class="classes" class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium capitalize">
        <slot/>
    </span>
</template>

<style scoped>

</style>
