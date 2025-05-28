<script setup>

import Card from "@peak/Components/Admin/Card.vue";
import TextInput from "@peak/Components/Admin/Input.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import TextArea from "@peak/Components/Admin/TextArea.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {Switch, SwitchDescription, SwitchGroup, SwitchLabel} from "@headlessui/vue";
import {useForm} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');
const toast = useToast();

const props = defineProps({
  cookie_consent_enabled: Boolean,
  cookie_consent_text: String | null,
  cookie_consent_name: String | null,
  cookie_consent_lifetime: Number | String | null,
  cookie_consent_policy_page_url: String | null,
});

const form = useForm({
  cookie_consent_enabled: props.cookie_consent_enabled,
  cookie_consent_text: props.cookie_consent_text,
  cookie_consent_name: props.cookie_consent_name,
  cookie_consent_lifetime: props.cookie_consent_lifetime,
  cookie_consent_policy_page_url: props.cookie_consent_policy_page_url
});

const save = () => {
  form.put(route('admin.settings.website.cookie.update'), {
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
  <AdminLayout :description="__('Manage GDPR cookie consent popups')" :title="__('Cookie Consent')">
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
        <div class="col-span-2">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Enable Cookie Consent') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                  {{
                                    __('Show a cookie consent banner to visitors in compliance with privacy regulations like GDPR and CCPA. When enabled, users will be prompted to accept or manage their cookie preferences before using the website.')
                                  }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.cookie_consent_enabled"
                      :class="[form.cookie_consent_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.cookie_consent_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.cookie_consent_enabled" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2">
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Cookie text')" for="cookie_consent_text"/>
            <TextArea v-model="form.cookie_consent_text" class="block w-full"></TextArea>
            <InputError :message="form.errors?.cookie_consent_text" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Cookie name')" for="cookie_consent_name"/>
            <TextInput id="cookie_consent_name" v-model="form.cookie_consent_name" class="block w-full"/>
            <InputError :message="form.errors?.cookie_consent_name" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Cookie Lifetime (days)')" for="cookie_consent_lifetime"/>
            <TextInput id="cookie_consent_lifetime" v-model="form.cookie_consent_lifetime"
                       class="block w-full"/>
            <InputError :message="form.errors?.cookie_consent_lifetime" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Cookies policy page URL')" for="cookie_consent_policy_page_url"/>
            <TextInput id="cookie_consent_policy_page_url" v-model="form.cookie_consent_policy_page_url"
                       class="block w-full" placeholder="/"/>
            <InputError :message="form.errors?.cookie_consent_policy_page_url" class="mt-1"/>
          </div>
        </div>
      </div>
    </Card>
  </AdminLayout>
</template>

<style scoped></style>
