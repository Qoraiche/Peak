<template>
  <p v-html="processedText"></p>
</template>

<script>
export default {
  props: {
    text: String, // This text may contain <mark> tags
  },
  computed: {
    processedText() {
      return this.text || ""; // Ensure text is never undefined
    },
  },
};
</script>
