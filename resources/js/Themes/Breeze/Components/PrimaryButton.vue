<script setup>

import {PlusIcon} from "@heroicons/vue/24/solid";
import DynamicLucideIcon from "@/Themes/Breeze/Components/DynamicLucideIcon.vue";

defineProps({
  type: {
    type: String,
    default: 'submit',
  },
  loading: {
    type: Boolean,
    default: false
  },
  processing: {
    type: Boolean,
    default: false
  },
  icon: {
    type: String,
    default: null,
  },
  newIcon: {
    type: Boolean,
    default: false
  }
});
</script>

<template>
  <button :class="{'opacity-75': processing}"
          :disabled="processing"
          :type="type"
          class="inline-flex items-center rounded-md bg-blue-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 active:bg-blue-700 focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">

    <slot name="pre"></slot>

    <DynamicLucideIcon :icon="icon" class="-ml-1 ltr:mr-3 rtl:ml-3 h-5 w-5 text-white" size="size-4"/>
    <PlusIcon v-if="newIcon && !(loading || processing) && !icon" class="-ml-1 ltr:mr-3 rtl:ml-3 h-5 w-5 text-white"/>

    <svg v-if="loading || processing" class="animate-spin -ml-1 ltr:mr-3 rtl:ml-3 h-5 w-5 text-white"
         fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            fill="currentColor"></path>
    </svg>

    <slot/>

    <slot name="post"></slot>
  </button>
</template>
