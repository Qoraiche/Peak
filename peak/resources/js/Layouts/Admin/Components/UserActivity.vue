<template>
  <ul class="space-y-6" role="list">
    <li v-for="(activity, index) in activities" :key="activity.id" class="relative flex gap-x-4">
      <!-- Conditionally show the vertical line unless it's the last item -->
      <div v-if="index !== activities.length - 1"
          class="absolute ltr:left-0 rtl:right-0 top-0 flex w-6 justify-center -bottom-6">
        <div class="w-px bg-gray-200"></div>
      </div>

      <!-- Icon or Default Circle -->
      <div class="relative flex size-6 flex-none items-center justify-center bg-white">
        <!-- If the activity has an icon, show it -->
        <span v-if="activity.icon" class="text-white bg-blue-600 rounded-full p-1">
            <DynamicLucideIcon :icon="activity.icon" size="size-4"/>
        </span>

        <!-- Otherwise, show the default circle -->
        <div v-else class="size-1.5 rounded-full bg-gray-100 ring-1 ring-gray-300"></div>
      </div>

      <!-- Activity Text -->
      <p class="flex-auto py-0.5 text-xs/5 text-gray-500">
        <span class="font-medium text-gray-900">{{ activity.user }} 22</span>
        {{ activity.description }}.
      </p>

      <time :datetime="activity.created_at" class="flex-none py-0.5 text-xs/5 text-gray-500">
        {{ activity.created_at }}
      </time>
    </li>
  </ul>
</template>

<script setup>
import DynamicLucideIcon from "@peak/Components/Admin/DynamicLucideIcon.vue";

defineProps({
  activities: Array
});
</script>
