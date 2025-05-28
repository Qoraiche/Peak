<template>
  <blockquote :class="alignmentClass">
    {{ text }}
    <footer v-if="caption" class="mt-2 text-sm text-gray-500">— {{ caption }}</footer>
  </blockquote>
</template>

<script>
export default {
  props: {
    text: String,
    caption: String,
    alignment: String,
  },
  computed: {
    alignmentClass() {
      return {
        left: 'text-left',
        center: 'text-center',
        right: 'text-right',
      }[this.alignment] || 'text-left';
    },
  },
};
</script>
