<script setup>

import Breadcrumbs from "@peak/Components/Admin/Breadcrumbs.vue";
import Header from "@peak/Layouts/Admin/AdminHeader.vue";
import AdminMobileSidebar from "@peak/Layouts/Admin/AdminMobileSidebar.vue";
import AdminSidebar from "@peak/Layouts/Admin/AdminSidebar.vue";
import PageTitle from "@peak/Layouts/Admin/Components/PageTitle.vue";
import SearchResources from "@peak/Layouts/Admin/Components/Search/SearchResources.vue";
import Drawers from "@peak/Layouts/Admin/Drawers.vue";
import {Head, usePage} from '@inertiajs/vue3';
import {inject, onMounted, ref} from 'vue';
import Errors from "@peak/Components/Admin/Errors.vue";
import FlashBanner from "@peak/Components/Admin/FlashBanner.vue";
import Modals from "@peak/Layouts/Admin/Modals.vue";
import UserNotification from "@peak/Components/Admin/UserNotification.vue";

const props = defineProps({
  title: String,
  hasActions: true,
  description: String,
  icon: {
    type: String,
    default: null,
  }
});

const doHasActions = ref(props.hasActions);
const sidebarOpen = ref(false);
const emitter = inject('emitter');
const sidebarMinimized = ref(localStorage.getItem('sidebarMinimized') === 'true');

onMounted(() => {
  emitter.on('openMobileSidebar', () => {
    sidebarOpen.value = true;
  });

  emitter.on('closeMobileSidebar', () => {
    sidebarOpen.value = false;
  });

  emitter.on('sidebarMinimizedActivated', (minimized) => {
    sidebarMinimized.value = minimized;
  });

  const userId = usePage().props.auth.user.id;

  window.Echo.private(`notifications.${userId}`)
      .listen('.user.notification', (event) => {
        console.log('Notification received:', event);
      })
      .error((error) => {
        console.error('Echo error:', error);
      });
});

</script>

<template>
  <div class="min-h-screen">
    <Head :title="title"/>
    <Modals/>
    <UserNotification/>
    <AdminMobileSidebar :sidebar-open="sidebarOpen"/>
    <AdminSidebar/>
    <Drawers/>
    <SearchResources/>

    <div
        :class="[sidebarMinimized ? 'rtl:lg:pr-[75px] ltr:lg:pl-[75px]' : 'ltr:lg:pl-[260px] rtl:lg:pr-[260px]']"
        class="transition-width duration-200 ease-in-out min-h-screen flex flex-col"
    >
      <Header/>

      <main class="my-9">
        <div class="px-4 sm:px-6 lg:px-8">
          <Breadcrumbs/>

          <PageTitle :description="description" :icon="icon" :title="title">
            <slot v-if="$slots.actions" name="actions"/>
          </PageTitle>

          <FlashBanner class="my-4"/>
          <Errors class="my-4"/>

          <div class="mt-12">
            <slot/>
          </div>
        </div>
      </main>

      <!-- Footer pushed to bottom -->
      <div class="flex px-4 sm:px-8 justify-end mt-auto pb-6">
        <span v-if="$page.props.admin.show_made_by_peak" class="text-muted-foreground text-xs">
          Made with &heartsuit; by <a class="text-blue-600 hover:text-blue-700" href="mailto:contact@larapeak.com"
                                      target="_blank">Peak</a> - <b>v{{ $page.props.peak.version }}</b>
        </span>
      </div>
    </div>

  </div>
</template>

<style>
body {
  background-color: #f8f8f8;
}
</style>
