<script setup>

import {Link} from '@inertiajs/vue3';
import {inject, onMounted, ref} from "vue";
import {useAuth} from "@peak/Composables/useAuth.js";
import DynamicLucideIcon from "@/Themes/Breeze/Components/DynamicLucideIcon.vue";

const {user} = useAuth();

const emitter = inject('emitter');

const props = defineProps({
  title: {
    type: String,
    default: null,
  },
  icon: {
    type: String,
    default: null
  },
  url: {
    type: String,
    default: null
  },
  unreadNotificationsCount: {
    type: [Number, String],
    default: 0
  }
});

const newNotificationReceived = ref(false);

onMounted(() => {
  const userId = user.value?.id;

  /*emitter.emit('new-notification', {
    title: 'the title',
    message: 'The message'
  });*/

  window.Echo.private(`notifications.${userId}`)
      .listen('.user.notification', (event) => {
        console.log('Notification received:', event);
        newNotificationReceived.value = true;
      })
      .error((error) => {
        console.error('Echo subscription error:', error);
      });
});
</script>

<template>
  <Link :href="route('admin.notifications.index')" class="relative inline-block">
    <div
        v-tooltip="__(title)"
        class="-m-2.5 w-11 h-11 hidden md:flex justify-center items-center text-gray-400 relative hover:text-gray-500 rounded-full bg-gray-50 hover:bg-gray-100 transition duration-300">
      <span class="sr-only">
        {{ __('View notifications') }}
      </span>
      <DynamicLucideIcon :icon="icon" size="size-5"/>

      <span v-if="unreadNotificationsCount > 0 || newNotificationReceived"
            class="flex absolute h-2 w-2 top-1.5 right-1.5 -mt-1 -mr-1">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
            </span>
    </div>
  </Link>
</template>

<style scoped>

</style>
