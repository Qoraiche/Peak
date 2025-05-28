<script setup>
import {computed, useSlots} from 'vue';
import SectionTitle from './SectionTitle.vue';

defineEmits(['submitted']);

const hasActions = computed(() => !!useSlots().actions);
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

    <div :class="[$slots.title ? 'md:col-span-2' : 'md:col-span-3']" class="mt-5 md:mt-0 border border-gray-200">
      <form @submit.prevent="$emit('submitted')">
        <div
            :class="hasActions ? 'sm:rounded-tl-md sm:rounded-tr-md' : 'sm:rounded-md'"
            class="px-4 py-5 bg-gray-50/60 shadow-md sm:p-6"
        >
          <div class="grid grid-cols-6 gap-6">
            <slot name="form"/>
          </div>
        </div>

        <div v-if="hasActions"
             class="flex items-center justify-end px-4 py-3 bg-gray-50 text-end sm:px-6 sm:rounded-bl-md sm:rounded-br-md">
          <slot name="actions"/>
        </div>
      </form>
    </div>
  </div>
</template>
