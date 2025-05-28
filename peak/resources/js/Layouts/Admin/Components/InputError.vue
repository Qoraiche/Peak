<script setup>
defineProps({
  message: [String, Array, Object],
});
</script>

<template>
  <div v-if="message">
    <p class="text-sm text-red-600 dark:text-red-400">
      <!-- Handle Array -->
      <span v-if="Array.isArray(message)">
                <ul>
                    <li v-for="(msg, index) in message" :key="index">{{ msg }}</li>
                </ul>
            </span>

      <!-- Handle Object -->
      <span v-else-if="typeof message === 'object'">
                <ul>
                    <li v-for="(error, key) in message" :key="key">
                        <strong>{{ key }}:</strong> {{ error }}
                    </li>
                </ul>
            </span>

      <!-- Handle String -->
      <span v-else>
                {{ message }}
            </span>
    </p>
  </div>
</template>
