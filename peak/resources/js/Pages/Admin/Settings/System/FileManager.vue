<script setup>

import DangerButton from "@peak/Components/Admin/DangerButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import Card from "@peak/Components/Admin/Card.vue";
import {__} from "@peak/Composables/useTranslate.js";
import {CircleCheckBig} from "lucide-vue-next";
import AlertInfo from "@peak/Components/Admin/Alerts/AlertInfo.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";

const emitter = inject('emitter');
const toast = useToast();

const form = useForm({});

const openFileManager = (fileManagerRoute) => {
  window.open(route(fileManagerRoute), '_blank');
};

</script>

<template>
  <AdminLayout :description="__('Manage Your Application Files')" :title="__('File Manager')"
               custom-width="max-w-[780px] lg:min-w-[780px]">
    <div class="flex flex-col space-y-1">
      <div id="general" class="rounded-md">
        <div class="flex flex-col">
          <div class="py-3">
            <iframe :src="route('admin.settings.system.filemanager.unisharp.lfm.show')" class="w-full min-h-[500px]"/>
          </div>
        </div>
      </div>
    </div>

    <template #pageActions>
      <PrimaryButton @click="openFileManager('admin.settings.system.filemanager.unisharp.lfm.show')">
        {{ __('Open File manager') }}
      </PrimaryButton>
    </template>

  </AdminLayout>
</template>

<style scoped></style>
