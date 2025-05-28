<script setup>

import Card from "@peak/Components/Admin/Card.vue";
import TextInput from "@peak/Components/Admin/Input.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import TextArea from "@peak/Components/Admin/TextArea.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');
const toast = useToast();

const props = defineProps({
  google_service_account_credentials_json: {
    type: String,
    default: null
  },

  google_analytics_property_id: {
    type: String,
    default: null
  }
});

const form = useForm({
  google_analytics_property_id: props.google_analytics_property_id,
  google_service_account_credentials_json: props.google_service_account_credentials_json
});

const save = () => {
  form.put(route('admin.settings.website.analytics.update'), {
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
  <AdminLayout :description="__('Add and configure analytics tools')" :title="__('Analytics')">
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

      <div>
        <div class="grid grid-cols-1 gap-5 py-5">
          <div>
            <div class="flex flex-col space-y-2">
              <InputLabel :value="__('Google Service Account Credentials Json')"
                          for="google_service_account_credentials_json"/>
              <TextArea id="google_service_account_credentials_json"
                        v-model="form.google_service_account_credentials_json"
                        class="w-full" name="google_service_account_credentials_json"
                        rows="5"/>

              <InputError :message="form.errors?.google_service_account_credentials_json" class="mt-1"/>
            </div>
          </div>
        </div>
        
        <div class="grid grid-cols-1 gap-5 py-5">
          <div>
            <div class="flex flex-col space-y-2">
              <InputLabel :value="__('Google Analytics Property ID')" for="google_analytics_property_id"/>
              <TextInput id="google_analytics_property_id" v-model="form.google_analytics_property_id" class="w-full"
                         name="google_analytics_property_id"/>

              <InputError :message="form.errors?.google_analytics_property_id" class="mt-1"/>
            </div>
          </div>
        </div>
      </div>
    </Card>
  </AdminLayout>
</template>

<style scoped></style>
