<script setup>

import StatsWidget from "@peak/Components/Admin/StatsWidget.vue";
import DynamicLucideIcon from "@peak/Components/Admin/DynamicLucideIcon.vue";

defineProps({
  totalSubscribersCount: {
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
  <StatsWidget :deferred="lazy" deferred-data="totalSubscribersCount">
    <template #icon>
      <DynamicLucideIcon v-if="icon" :icon="icon" class="stroke-blue-500"/>
    </template>

    <template #label>
      {{ __(title) }}
    </template>

    <template #data>
      {{ totalSubscribersCount }}
    </template>
  </StatsWidget>
</template>

<style scoped>

</style>
