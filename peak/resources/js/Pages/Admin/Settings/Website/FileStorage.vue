<script setup>

import Card from "@peak/Components/Admin/Card.vue";
import TextInput from "@peak/Components/Admin/Input.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');
const toast = useToast();

const props = defineProps({
  disk: String,
  s3_key: String | null,
  s3_secret: String | null,
  s3_region: String | null,
  s3_bucket: String | null,
  s3_url: String | null,
  s3_endpoint: String | null
});

const form = useForm({
  disk: props.disk,
  s3_key: props.s3_key,
  s3_secret: props.s3_secret,
  s3_region: props.s3_region,
  s3_bucket: props.s3_bucket,
  s3_url: props.s3_url,
  s3_endpoint: props.s3_endpoint
});

const save = () => {
  form.put(route('admin.settings.website.file-storage.update'), {
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    },
    onError: () => {
      toast.error(__('Something went wrong.'));
    }
  });
};
</script>

<template>
  <AdminLayout :description="__('Configure file storage drivers and paths')" :title="__('File Storage')">
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


      <div class="grid grid-cols-1 gap-5 py-5 md:grid-cols-2">
        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel for="mailer" value="Disk"/>
            <select id="disk" v-model="form.disk"
                    class="flex-1 block w-full min-w-0 mt-2 text-gray-900 border border-gray-300 rounded-md placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm sm:leading-6">
              <option value="local">
                {{ __('Local') }}
              </option>

              <option value="public">
                {{ __('Public') }}
              </option>

              <option value="s3">
                {{ __('Amazon S3') }}
              </option>
            </select>

            <InputError :message="form.errors?.disk" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.disk === 's3'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel :value="__('Access key')" for="host"/>
            </div>
            <TextInput id="host" v-model="form.s3_key" autocomplete="off" class="block w-full"/>
            <InputError :message="form.errors?.s3_key" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.disk === 's3'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel :value="__('Secret Access key')" for="s3_secret"/>
            </div>
            <TextInput id="s3_secret" v-model="form.s3_secret" autocomplete="off"
                       class="block w-full"/>
            <InputError :message="form.errors?.s3_secret" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.disk === 's3'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel :value="__('Default region')" for="s3_region"/>
            </div>
            <TextInput id="host" v-model="form.s3_region" autocomplete="off" class="block w-full"/>
            <InputError :message="form.errors?.s3_region" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.disk === 's3'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel :value="__('Bucket')" for="s3_bucket"/>
            </div>
            <TextInput id="host" v-model="form.s3_bucket" autocomplete="off" class="block w-full"/>
            <InputError :message="form.errors?.s3_bucket" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.disk === 's3'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel :value="__('URL')" for="s3_url"/>
            </div>
            <TextInput id="host" v-model="form.s3_url" autocomplete="off" class="block w-full"/>
            <InputError :message="form.errors?.s3_url" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.disk === 's3'">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel :value="__('Endpoint')" for="s3_endpoint"/>
            </div>
            <TextInput id="host" v-model="form.s3_endpoint" autocomplete="off" class="block w-full"/>
            <InputError :message="form.errors?.s3_endpoint" class="mt-1"/>
          </div>
        </div>

      </div>
    </Card>
  </AdminLayout>
</template>

<style scoped></style>
