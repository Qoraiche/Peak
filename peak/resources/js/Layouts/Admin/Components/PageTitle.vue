<script setup>
import DynamicLucideIcon from "@/Themes/Breeze/Components/DynamicLucideIcon.vue";

defineProps({
  title: String,
  description: String,
  icon: {
    type: String,
    default: null,
  }
});
</script>

<template>
  <div class="flex flex-col space-y-6 sm:space-y-0 sm:flex-row sm:gap-x-3 sm:justify-between sm:items-center">
    <div class="flex gap-x-6 items-center">
      <div v-if="icon" class="page-title-icon p-4 rounded-md border border-gray-300">
        <DynamicLucideIcon :icon="icon" size="size-6"/>
      </div>
      <div class="flex flex-col space-y-2">
                <span v-if="title" class="text-xl text-gray-900 font-semibold">
                    {{ title }}
                </span>
        <span v-if="description" class="text-sm text-gray-500 font-medium">
                    {{ description }}
                </span>
      </div>
    </div>
    <div class="flex gap-x-3">
      <slot/>
    </div>
  </div>
</template>

<style scoped>
.page-title-icon {
  box-shadow: 0 0.46875rem 2.1875rem rgba(4, 9, 20, .03), 0 0.9375rem 1.40625rem rgba(4, 9, 20, .03), 0 0.25rem 0.53125rem rgba(4, 9, 20, .05), 0 0.125rem 0.1875rem rgba(4, 9, 20, .03);
}
</style>
