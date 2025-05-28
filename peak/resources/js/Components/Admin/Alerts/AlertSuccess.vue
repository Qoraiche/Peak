<script setup>
import {CircleCheck} from 'lucide-vue-next'

const props = defineProps({
  rounded: {
    default: true,
    type: Boolean,
  }
});

</script>

<template>
  <div :class="{'rounded-md' : rounded}" class="bg-green-50 p-4 border">
    <div class="flex gap-x-3">
      <div class="flex-shrink-0">
        <CircleCheck aria-hidden="true" class="h-5 w-5 text-green-400"/>
      </div>
      <div class="text-green-600 text-sm break-all">
        <slot/>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
