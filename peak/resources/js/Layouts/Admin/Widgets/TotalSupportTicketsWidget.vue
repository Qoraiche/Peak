<script setup>

import StatsWidget from "@peak/Components/Admin/StatsWidget.vue";
import DynamicLucideIcon from "@peak/Components/Admin/DynamicLucideIcon.vue";

defineProps({
  totalSupportTicketsCount: {
    type: [Number, String],
    default: 0
  },
  title: {
    type: String,
    default: null,
  },
  lazy: {
    type: Boolean,
    default: true,
  },
  icon: {
    type: String,
    default: null,
  }
});

</script>

<template>
  <StatsWidget :deferred="lazy" deferred-data="totalSupportTicketsCount">
    <template #icon>
      <DynamicLucideIcon v-if="icon" :icon="icon" class="stroke-blue-500"/>
    </template>

    <template #label>
      {{ __(title) }}
    </template>

    <template #data>
      {{ totalSupportTicketsCount }}
    </template>
  </StatsWidget>
</template>

<style scoped>

</style>
