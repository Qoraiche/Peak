<script setup>

import Card from "@peak/Components/Admin/Card.vue";
import Input from "@peak/Components/Admin/Input.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import SelectInput from "@peak/Components/Admin/SelectInput.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');
const toast = useToast();

const props = defineProps({
  settings: Object,
  timezones: Object,
  currencies: Object
});

const form = useForm({
  site_title: props.settings.site_title,
  tagline: props.settings.tagline,
  date_format: props.settings.date_format,
  date_time_format: props.settings.date_time_format,
  title_separator: props.settings.title_separator,
  currency_code: props.settings.currency_code,
  default_language: props.settings.default_language,
  timezone: props.settings.timezone,
  company_name: props.settings.company_name,
  company_address: props.settings.company_address,
  company_phone: props.settings.company_phone,
  contact_email: props.settings.contact_email,
  tos_url: props.settings.tos_url,
  privacy_policy_url: props.settings.privacy_policy_url,
  tiny_mce_api_key: props.settings.tiny_mce_api_key,
  seo_description: props.settings.seo_description,
  seo_keywords: props.settings.seo_keywords
});

const save = () => {
  form.put(route('admin.settings.website.general.update'), {
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
  <AdminLayout :description="__('Set global platform settings like app name and timezone.')" :title="__('General')">
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

      <div id="general" class="rounded-md">
        <div class="flex flex-col">
          <div>
            <div class="grid grid-cols-1 gap-5 py-5 md:grid-cols-2">
              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('Site title')" for="site_title"/>
                  <Input id="site_title" v-model="form.site_title" class="block w-full"
                         placeholder="Site title"/>
                  <InputError :message="form.errors?.site_title" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center gap-x-2">
                    <InputLabel :value="__('Tagline')" for="tagline"/>
                  </div>
                  <Input id="tagline" v-model="form.tagline" class="block w-full"
                         placeholder="Tagline"/>
                  <InputError :message="form.errors?.tagline" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('Title separator')" for="title_separator"/>
                  <Input id="title_separator" v-model="form.title_separator"
                         :placeholder="__('Title separator')" class="block w-full"/>
                  <InputError :message="form.errors?.title_separator" class="mt-1"/>
                </div>
              </div>

              <!--                            <div>
                                              <div class="flex flex-col space-y-2">
                                                  <InputLabel for="default_lang" value="Default Language"/>
                                                  <select v-model="form.default_language" id="default_lang"
                                                          class="flex-1 block w-full min-w-0 mt-2 text-gray-900 border border-gray-300 rounded-md placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm sm:leading-6">
                                                      <option value="en">
                                                          English
                                                      </option>
                                                  </select>

                                                  <InputError class="mt-1" :message="form.errors?.timezone"/>
                                              </div>
                                          </div>-->

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('Timezone')" for="timezone"/>

                  <SelectInput v-model="form.timezone" :values="timezones" name="timezone"/>

                  <!--                                    <select v-model="form.timezone" id="timezone"
                                                              class="flex-1 block w-full min-w-0 mt-2 text-gray-900 border border-gray-300 rounded-md placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm sm:leading-6">
                                                          <option :value="timezone" v-for="timezone in timezones">
                                                              {{ timezone }}
                                                          </option>
                                                      </select>-->

                  <InputError :message="form.errors?.timezone" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('Currency')" for="currency"/>
                  <!--                                    <select v-model="form.currency_code" id="currency"
                                                              class="flex-1 block w-full min-w-0 mt-2 text-gray-900 border border-gray-300 rounded-md placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm sm:leading-6">
                                                          <option :value="currency" v-for="currency in currencies">
                                                              {{ currency }}
                                                          </option>
                                                      </select>-->

                  <SelectInput v-model="form.currency_code" :values="currencies" name="timezone"/>

                  <InputError :message="form.errors?.currency_code" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('Date format')" for="date_format"/>
                  <Input id="date_format" v-model="form.date_format" class="block w-full"
                         placeholder="d/m/Y"/>
                  <InputError :message="form.errors?.date_format" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('Date time format')" for="date_time_format"/>
                  <Input id="date_time_format" v-model="form.date_time_format" class="block w-full"
                         placeholder="d/m/Y H:i"/>
                  <InputError :message="form.errors?.date_time_format" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('Company name')" for="company_name"/>
                  <Input id="company_name" v-model="form.company_name" class="block w-full"
                         placeholder="Company name"/>
                  <InputError :message="form.errors?.company_name" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('Company address')" for="company_address"/>
                  <Input id="company_address" v-model="form.company_address"
                         class="block w-full" placeholder="Company address"/>
                  <InputError :message="form.errors?.company_address" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('Company phone')" for="company_phone"/>
                  <Input id="company_phone" v-model="form.company_phone" class="block w-full"
                         placeholder="Company phone"/>
                  <InputError :message="form.errors?.company_phone" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('Contact email address')" for="email_address"/>
                  <Input id="email_address" v-model="form.contact_email" class="block w-full"
                         placeholder="Contact Email address" type="email"/>
                  <InputError :message="form.errors?.contact_email" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('Terms of service page url:')" for="tos"/>
                  <Input id="tos" v-model="form.tos_url" class="block w-full"
                         placeholder="example: http://example.test/terms-of-use" type="text"/>
                  <InputError :message="form.errors?.tos_url" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('Privacy policy page url')" for="privacy"/>
                  <Input id="privacy" v-model="form.privacy_policy_url"
                         class="block w-full"
                         placeholder="example: http://example.test/privacy-policy" type="text"/>
                  <InputError :message="form.errors?.privacy_policy_url" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('TinyMCE api key')" for="tinymce_editor"/>
                  <Input id="tinymce_editor" v-model="form.tiny_mce_api_key" class="block w-full"
                         type="text"/>
                  <InputError :message="form.errors?.tiny_mce_api_key" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('SEO description')" for="seo_description"/>
                  <Input id="seo_description" v-model="form.seo_description" class="block w-full"
                         type="text"/>
                  <InputError :message="form.errors?.seo_description" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('SEO keywords')" for="seo_keywords"/>
                  <Input id="seo_keywords" v-model="form.seo_keywords" class="block w-full"
                         type="text"/>
                  <InputError :message="form.errors?.seo_keywords" class="mt-1"/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Card>
  </AdminLayout>
</template>

<style scoped></style>
