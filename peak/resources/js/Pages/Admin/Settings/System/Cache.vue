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

const emitter = inject('emitter');
const toast = useToast();

const form = useForm({});

const clear = () => {
  form.delete(route('admin.settings.system.cache.clear'), {
    onSuccess: () => {
      toast.success(__('Cache Cleared'));
    },
    onError: () => {
      toast.error(__('Something went wrong.'));
    }
  });
};

</script>

<template>
  <AdminLayout :description="__('Clear or manage system cache')" :title="__('Cache')">

    <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
      <template #header>
        {{ __('Clear cache') }}
      </template>

      <template #actions>
        <DangerButton :class="{ 'opacity-25': form.processing }" :loading="form.processing" class="self-end"
                      @click="clear">
          {{ __('Clear cache') }}
        </DangerButton>
      </template>

      <div class="flex flex-col">
        <AlertInfo class="mb-4">
          {{
            __('Note: This action does not affect your database or user data. It is safe to perform and can help during development or after updates.')
          }}
        </AlertInfo>

        <p class="text-sm text-gray-600">
          Clearing your application's cache can help resolve unexpected behavior, refresh stale data, or apply recent
          configuration changes. This action will flush:
        </p>

        <ul class="mt-4 text-sm flex flex-col gap-y-2">
          <li class="flex items-center gap-x-2">
            <CircleCheckBig class="size-4"/>
            {{ __('Configuration cache') }}
          </li>
          <li class="flex items-center gap-x-2">
            <CircleCheckBig class="size-4"/>
            {{ __('Route cache') }}
          </li>

          <li class="flex items-center gap-x-2">
            <CircleCheckBig class="size-4"/>
            {{ __('View cache') }}
          </li>

          <li class="flex items-center gap-x-2">
            <CircleCheckBig class="size-4"/>
            {{ __('Application cache') }}
          </li>
        </ul>
      </div>
    </Card>
  </AdminLayout>
</template>

<style scoped></style>
