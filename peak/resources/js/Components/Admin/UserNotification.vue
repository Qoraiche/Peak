<template>
  <!-- Global notification live region -->
  <div aria-live="assertive"
       class="z-[999] w-full pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6">
    <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
      <!-- Loop through notifications -->
      <transition-group name="notification" class="w-full max-w-sm" tag="div">
        <div
            v-for="(notification, index) in notifications"
            :key="notification.id"
            class="pointer-events-auto overflow-hidden mt-2 rounded-lg bg-white/80 backdrop-blur shadow-lg ring-1 ring-black/5">
          <div class="p-4">
            <div class="flex items-start">
              <div class="shrink-0">
                <!--                                <InboxIcon class="size-6 text-gray-400" aria-hidden="true" />-->

                <BellAlertIcon class="size-6 text-gray-400"/>
              </div>
              <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium text-gray-900">{{ notification.title }}</p>
                <p class="mt-1 text-sm text-gray-700">{{ notification.message }}</p>
              </div>
              <div class="ml-4 flex shrink-0">
                <button type="button" @click="removeNotification(index)"
                        class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                  <span class="sr-only">Close</span>
                  <XMarkIcon class="size-5" aria-hidden="true"/>
                </button>
              </div>
            </div>
          </div>
        </div>
      </transition-group>
    </div>
  </div>
</template>

<script setup>
import {ref, inject, onMounted} from 'vue'
import {BellAlertIcon, XMarkIcon} from '@heroicons/vue/24/outline'

const notifications = ref([]);

const emitter = inject('emitter');

// Listen for new notifications
onMounted(() => {
  emitter.on('new-notification', (data) => {

    notifications.value = [];

    notifications.value.push({
      id: Date.now(), // Unique ID for the notification
      title: data.title,
      message: data.message
    });

    // Auto remove after 5 seconds
    setTimeout(() => {
      notifications.value.shift()
    }, 16000)
  })
})

// Function to remove a notification manually
const removeNotification = (index) => {
  notifications.value.splice(index, 1)
}
</script>

<style scoped>
.notification-enter-active,
.notification-leave-active {
  transition: opacity 0.5s, transform 0.3s;
}

.notification-enter-from,
.notification-leave-to {
  opacity: 0;
  transform: translateY(10px);
}
</style>
