<script setup>

import Card from "@peak/Components/Admin/Card.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {inject, ref} from "vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');
const toast = useToast();

const props = defineProps({
  themes: Object
});

const form = useForm({
  theme: null,
});

const activating = ref(false);

const activate = (theme) => {

  form.theme = theme;

  activating.value = true;

  form.put(route('admin.settings.frontend.theme.update'), {
    onSuccess: () => {
      toast.success(__('Theme activated'));

    },
    onError: () => {
      toast.error(__('Something went wrong.'));
    },
    onFinish: () => {
      activating.value = false;
    }
  });
};
</script>

<template>
  <AdminLayout :description="__('Configure your theme settings')" :title="__('Theme')">
    <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
      <template #header>
        {{ __('Theme Settings') }}
      </template>

      <div id="theme" class="rounded-md">
        <div class="flex flex-col">
          <div>
            <div class="grid grid-cols-1 gap-y-5 divide-y divide-zinc-200">
              <div v-for="theme in themes" :key="theme.name"
                   class="flex cursor-default items-start px-2 py-3 gap-x-3">
                <img :alt="theme.name" :src="theme.image_uri" class="object-cover rounded-md w-36 h-28 md:w-48 md:h-32">
                <div class="flex flex-col items-start h-full justify-between space-y-3">
                  <div class="flex items-start justify-between">
                    <div>
                      <div class="text-base font-semibold">
                        {{ theme.name }}
                      </div>
                      <p class="pt-1 prose leading-normal text-[.8em] break-words whitespace-normal">
                        {{ theme.description }}
                      </p>
                    </div>
                  </div>
                  <div class="flex items-center gap-x-4">
                    <span class="text-xs font-medium">
                      {{ theme.author }}
                    </span>

                    <span class="text-xs font-medium text-gray-600">{{ theme.version }}</span>

                    <span v-if="theme.is_active"
                          class="px-2 py-1 text-xs font-medium text-white bg-green-500 rounded-md">
                      {{ __('Active') }}
                    </span>

                    <span v-if="!theme.is_active && !activating"
                          class="text-sm text-blue-500 cursor-pointer hover:underline"
                          @click="activate(theme.name)">
                      {{ __('Activate') }}
                    </span>

                    <span v-if="activating && !theme.is_active" class="text-sm italic">
                      {{ __('Activating') }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Card>
  </AdminLayout>
</template>
