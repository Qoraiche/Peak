<script setup>
import {Info} from 'lucide-vue-next'

const props = defineProps({
  rounded: {
    default: true,
    type: Boolean
  }
});
</script>

<template>
  <div :class="{'rounded-md' : rounded}" class="bg-blue-50 p-4">
    <div class="flex gap-x-3">
      <div class="flex-shrink-0">
        <Info aria-hidden="true" class="h-5 w-5 text-blue-400"/>
      </div>
      
      <div class="text-blue-600 text-sm">
        <slot/>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
