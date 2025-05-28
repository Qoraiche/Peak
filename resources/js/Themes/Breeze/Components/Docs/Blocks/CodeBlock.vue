<script setup>
const props = defineProps({
  code: String
});
</script>

<template>
  <pre class="bg-neutral-800 text-white rounded-lg p-4 overflow-x-auto">
    <code class="language-php">{{ code }}</code>
  </pre>
</template>

<style scoped>
/* Add any specific styling for CodeBlock if needed */
</style>