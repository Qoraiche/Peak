<template>
  <component :is="'h' + level" :id="anchor ? anchor : null" class="font-semibold">{{ text }}</component>
</template>

<script>
export default {
  props: {
    text: String,
    anchor: {
      type: String,
      default: null,

    },
    level: {
      type: Number,
      default: 2,
    },
  },
};
</script>