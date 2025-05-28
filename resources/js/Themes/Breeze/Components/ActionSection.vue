<script setup>
import SectionTitle from './SectionTitle.vue';

defineProps({
  noMargin: {
    default: false,
    type: Boolean,
  }
})
</script>

<template>
  <div class="md:grid md:gap-6">
    <SectionTitle v-if="$slots.title">
      <template #title>
        <slot name="title"/>
      </template>
      <template #description>
        <slot name="description"/>
      </template>
    </SectionTitle>

    <div class="md:mt-0" :class="[$slots.title ? 'md:col-span-2':'md:col-span-3', noMargin ? '': 'mt-5']">
      <div class="px-4 py-5 sm:p-6 bg-gray-50/60 shadow-xs border border-gray-200 sm:rounded-lg">
        <slot name="content"/>
      </div>
    </div>
  </div>
</template>
