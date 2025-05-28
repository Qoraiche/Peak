<template>
  <div :class="alertClass" class="rounded-md p-4">
    <div class="flex items-start prose-none">
      <div class="shrink-0">{{ alertEmoji }}</div>
      <div class="ml-3">
        <div class="text-sm break-all">{{ message }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    type: String,
    message: String,
  },
  computed: {
    alertClass() {
      switch (this.type) {
        case 'info':
          return 'bg-blue-50 text-blue-800 border-blue-400';
        case 'success':
          return 'bg-green-50 text-green-800 border-green-400';
        case 'warning':
          return 'bg-yellow-50 text-yellow-800 border-yellow-400';
        case 'danger':
          return 'bg-red-50 text-red-800 border-red-400';
        case 'light':
          return 'bg-gray-50 text-gray-800 border-gray-400';
        case 'dark':
          return 'bg-gray-800 text-white border-gray-600';
        default:
          return '';
      }
    },
    alertEmoji() {
      switch (this.type) {
        case 'info':
          return 'ℹ️';
        case 'success':
          return '✅';
        case 'warning':
          return '⚠️';
        case 'danger':
          return '❌';
        default:
          return '';
      }
    },
  },
};
</script>
