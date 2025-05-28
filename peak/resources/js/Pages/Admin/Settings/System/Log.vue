<script setup>

import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {inject} from "vue";
import {useToast} from "vue-toastification";

const emitter = inject('emitter');
const toast = useToast();

const logViewerIndex = 'log-viewer.index';

const openLogViewer = () => {
  window.open(route(logViewerIndex), '_blank');
};
</script>

<template>
  <AdminLayout :description="__('View application logs and debug info')" :title="__('Application Log Viewer')"
               custom-width="max-w-[780px] lg:min-w-[780px]">
    <div class="flex flex-col space-y-1">
      <div id="general" class="rounded-md">
        <div class="flex flex-col">
          <div class="py-3">
            <iframe :src="route(logViewerIndex)" class="w-full min-h-[500px]"/>
          </div>
        </div>
      </div>
    </div>

    <template #pageActions>
      <PrimaryButton @click="openLogViewer">
        {{ __('Open Log Viewer') }}
      </PrimaryButton>
    </template>

  </AdminLayout>
</template>

<style scoped></style>
