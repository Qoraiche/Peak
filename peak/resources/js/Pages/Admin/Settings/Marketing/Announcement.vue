<script setup>

import TextInput from "@peak/Components/Admin/Input.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {Switch, SwitchDescription, SwitchGroup, SwitchLabel} from "@headlessui/vue";
import {useForm} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import Card from "@peak/Components/Admin/Card.vue";
import TinymceEditor from "@peak/Components/Admin/TinymceEditor.vue";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');
const toast = useToast();

const props = defineProps({
  announcement_enabled: Boolean | null,
  announcement_text: String | null,
  announcement_cta_text: String | null,
  announcement_cta_link: String | null
});

const form = useForm({
  announcement_enabled: props.announcement_enabled,
  announcement_text: props.announcement_text,
  announcement_cta_text: props.announcement_cta_text,
  announcement_cta_link: props.announcement_cta_link
});

const save = () => {
  form.put(route('admin.settings.marketing.announcement.update'), {
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
  <AdminLayout :description="__('Set up site-wide banners or announcements.')" :title="__('Announcement')">

    <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
      <template #header>
        {{ __('Edit Settings') }}
      </template>

      <template #actions>
        <PrimaryButton :class="{ 'opacity-25': !form.isDirty || form.processing }"
                       :disabled="!form.isDirty || form.processing" :loading="form.processing"
                       class="self-end"
                       @click="save">
          {{ __('Save Changes') }}
        </PrimaryButton>
      </template>


      <div class="grid grid-cols-1 gap-5 md:grid-cols-4">

        <div class="col-span-4">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                                        <span class="flex flex-col flex-grow">
                                            <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900"
                                                         passive>
                                                {{ __('Announcement Enabled') }}
                                            </SwitchLabel>
                                            <SwitchDescription as="span" class="text-sm text-muted-foreground">
                                              {{
                                                __('Enable this to show site-wide announcements or important messages to all users.')
                                              }}
                                            </SwitchDescription>
                                        </span>
              <Switch v-model="form.announcement_enabled"
                      :class="[form.announcement_enabled ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                            <span
                                                :class="[form.announcement_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                                aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.announcement_enabled" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2">
          <div class="flex flex-col space-y-2">
            <div class="flex items-center gap-x-2">
              <InputLabel :value="__('CTA Text')" for="announcement_text"/>
            </div>
            <TextInput id="announcement_text" v-model="form.announcement_cta_text"
                       class="block w-full"/>
            <InputError :message="form.errors?.announcement_cta_text" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2">
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('CTA Link')" for="announcement_cta_link"/>
            <TextInput id="announcement_cta_link" v-model="form.announcement_cta_link"
                       class="block w-full"/>
            <InputError :message="form.errors?.announcement_cta_link" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-4">
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Announce text')" for="announcement_cta_text"/>

            <TinymceEditor
                v-model="form.announcement_text"
                :init="{
                                    height: 280,
                                      branding: false,
                                      toolbar_mode: 'sliding',
                                      plugins: [
                                        // Core editing features
                                        'anchor', 'code', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount'
                                      ],
                                      toolbar: 'code | undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                                    }"
            />

            <InputError :message="form.errors?.announcement_text" class="mt-1"/>
          </div>
        </div>
      </div>

    </Card>
  </AdminLayout>
</template>

<style scoped></style>
