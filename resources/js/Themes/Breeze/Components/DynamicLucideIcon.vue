<script setup>
import {shallowRef, watchEffect} from "vue";

const props = defineProps({
  icon: String | null, // Dynamic icon name (e.g., "Camera", "Heart")
  size: {
    type: String,
    default: 'size-6',
  },
  color: {
    type: String,
    default: 'currentColor',
  },
  classes: {
    type: String | null,
  },
});

const LucideIcon = shallowRef(null);

watchEffect(async () => {
  if (props.icon) {
    try {
      const {[props.icon]: Icon} = await import("lucide-vue-next");
      LucideIcon.value = Icon;
    } catch (error) {
      console.error(`Icon "${props.icon}" not found in Lucide Vue`, error);
    }
  }
});
</script>

<template>
  <component :is="LucideIcon" v-if="LucideIcon" :class="[size, classes]" :color="color"/>
</template>
