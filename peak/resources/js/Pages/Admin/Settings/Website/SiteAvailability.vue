<script setup>

import Card from "@peak/Components/Admin/Card.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import TinymceEditor from "@peak/Components/Admin/TinymceEditor.vue";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');

const props = defineProps({
  mode: {
    type: String,
    required: true
  },
  content: {
    type: String,
    default: null
  }
});

const modes = [
  {id: 'public', name: 'Public', description: __('Anyone can see the site.')},
  {id: 'maintenance', name: 'Under Maintenance', description: __('Site is under construction or maintenance.')}
];

const form = useForm({
  mode: props.mode,
  content: props.content
});

const toast = useToast();

const save = () => {
  form.put(route('admin.settings.website.site-availability.update'), {
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    },
    onError: () => {
      toast.error(__('Something went wrong.'));
    },
  });
};
</script>

<template>
  <AdminLayout :description="__('Put the site into maintenance or live mode.')" :title="__('Site Availability')">
    <template #actions>
      <PrimaryButton :class="{ 'opacity-25': !form.isDirty || form.processing }"
                     :disabled="!form.isDirty || form.processing" :loading="form.processing"
                     class="self-end"
                     @click="save">
        {{ __('Save Changes') }}
      </PrimaryButton>
    </template>

    <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
      <template #header>
        {{ __('Edit Settings') }}
      </template>

      <div class="py-3">
        <fieldset>
          <legend class="sr-only">
            {{ __('Mode') }}
          </legend>
          <div class="space-y-5">
            <div v-for="mode in modes" :key="mode.id" class="relative flex items-start">
              <div class="flex items-center h-6">
                <input :id="mode.id" v-model="form.mode" :aria-describedby="`${mode.id}-description`"
                       :value="mode.id" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-600" name="mode"
                       type="radio"/>
              </div>
              <div class="text-sm leading-6 ltr:ml-3 rtl:mr-3">
                <label :for="mode.id" class="font-medium text-gray-900">
                  {{ mode.name }}
                </label>
                <p :id="`${mode.id}-description`" class="text-gray-500">
                  {{ mode.description }}
                </p>
              </div>
            </div>

            <div v-if="form.mode === 'maintenance'">
              <TinymceEditor v-model="form.content"/>
            </div>
          </div>
        </fieldset>
      </div>
    </Card>
  </AdminLayout>
</template>

<style scoped></style>
