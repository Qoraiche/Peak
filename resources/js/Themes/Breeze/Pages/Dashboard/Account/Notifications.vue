<script setup>
import DashboardLayout from "@/Themes/Breeze/Layouts/Dashboard/DashboardLayout.vue";
import {Link, router} from "@inertiajs/vue3";
import {BellSlashIcon} from "@heroicons/vue/24/outline/index.js";
import {inject, ref} from "vue";
import PrimaryButton from "@/Themes/Breeze/Components/PrimaryButton.vue";
import {useToast} from "vue-toastification";
import {__} from '@peak/Composables/useTranslate.js';

const route = inject('route');

const props = defineProps({
  notifications: Object,
  isUnread: Boolean,
  unreadNotificationsCount: Number | String | null,
});

const dayjs = inject('dayJS');
const toast = useToast();
const markingAllAsRead = ref(false);

const markAllAsRead = () => {
  markingAllAsRead.value = true;

  router.patch(route('dashboard.account.notifications.read'), {}, {
    onSuccess: () => {
      toast.success(__('All Marked as Read'));
    },
    onFinish: () => {
      markingAllAsRead.value = false;
    }
  });
}

</script>

<template>
  <DashboardLayout :description="__('Browse your notifications')"
                   :title="__('Notifications')">

    <template #actions>
      <PrimaryButton :class="{'opacity-75':unreadNotificationsCount < 1 || markingAllAsRead}"
                     :disabled="unreadNotificationsCount < 1 || markingAllAsRead"
                     :loading="markingAllAsRead"
                     @click="markAllAsRead">
        {{ __('Mark All as Read') }}
      </PrimaryButton>
    </template>

    <div class="mb-4">
      <nav aria-label="Tabs" class="flex gap-x-4">
        <Link :class="{'bg-zinc-50 border border-zinc-200' : !isUnread}"
              :href="route('dashboard.account.notifications.index')"
              aria-current="page"
              class="rounded-md px-3 py-2 text-sm font-medium text-gray-700">
          {{ __('All Notifications') }}
        </Link>

        <Link :class="{'bg-zinc-50 border border-zinc-200' : isUnread}"
              :href="route('dashboard.account.notifications.index', {type: 'unread'})"
              class="rounded-md px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
          {{ __('Notifications - Unread (:total)', {total: unreadNotificationsCount}) }}
        </Link>
      </nav>
    </div>

    <div class="relative flex flex-col divide-gray-100 divide-y">
      <a v-for="notification in notifications" v-if="notifications.length > 0"
         :id="'notification-'+ notification.id"
         :key="notification.id"
         :href="route('admin.notifications.show', notification.id)"
         class="group relative rounded-md py-4 pl-6 pr-4 border-zinc-200 bg-gray-50 hover:bg-gray-50/50">
        <div class="flex justify-between items-start">
          <div class="flex flex-row">
                    <span class="h-fit overflow-hidden rounded-full bg-gray-100 p-1 typo-callout">
                        <img :src="notification.data?.icon" alt="" class="w-10 h-10 rounded-full object-cover">
                    </span>
            <div
                class="ltr:ml-4 rtl:mr-4 flex w-full flex-1 flex-col ltr:text-left rtl:text-right typo-callout"><span
                class="break-words font-medium text-sm">
                        {{ notification.data.user.name }}
                    </span>
              <p class="mt-2 w-full leading-5 break-words text-gray-600 text-sm">
                {{ notification.data.body }}
              </p>

              <span v-if="notification.read_at" class="text-xs text-gray-500 pt-1">
                            {{
                  __('Opened at :date', {date: dayjs(notification.read_at).format('MMM DD, YYYY, h:mm A')})
                }}
                        </span>
            </div>
          </div>

          <span class="text-gray-500 text-xs">{{
              dayjs(notification.created_at).format('MMM DD, YYYY, h:mm A')
            }}</span>
        </div>
      </a>

      <div
          v-else
          class="w-full p-3 py-12 flex flex-col space-y-3 justify-center items-center bg-zinc-50 border border-zinc-200">
        <span><BellSlashIcon class="h-12 w-12 text-zinc-200"/></span>
        <span class="text-sm text-gray-600">{{ __('You have no notifications.') }}</span>
      </div>
    </div>
  </DashboardLayout>
</template>
