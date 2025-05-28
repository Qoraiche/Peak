<script setup>

import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import DialogModal from "@peak/Components/Admin/DialogModal.vue";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import {inject, onMounted, ref, watch} from "vue";
import {useForm, usePage} from "@inertiajs/vue3";
import TextInput from "@peak/Components/Admin/Input.vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";
import SelectInput from "@peak/Components/Admin/SelectInput.vue";
import InputError from "@peak/Components/Admin//InputError.vue";
import AlertInfo from "@peak/Components/Admin/Alerts/AlertInfo.vue";

const editNewMenuItem = ref(false);
const emitter = inject('emitter');
const menuId = ref(null);

const closeMe = () => {
  newMenuItem.reset();
  editNewMenuItem.value = false;
};

const page = usePage();
const appPages = page.props.front.sitePages;

const newMenuItem = useForm({
  title: '',
  external_link: '',
  internal_path: '',
  type: 'page',
  app_page_id: appPages?.[0]?.id ?? '',
  open_new_window: false,
  template: 'documentation',
});

const externalLink = ref(false);

watch(externalLink, external => {
  if (external === true) {
    newMenuItem.reset('open_new_window');
  }
});

const toast = useToast();

onMounted(() => {
  emitter.on('menu:edit-item', function (item) {
    externalLink.value = item.external_link !== null;
    newMenuItem.title = item.title;
    newMenuItem.type = item.type;
    newMenuItem.template = item.template_name;
    newMenuItem.external_link = item.external_link;
    newMenuItem.internal_path = item.internal_path;
    newMenuItem.app_page_id = item.page_id;
    newMenuItem.open_new_window = item.open_new_window;
    menuId.value = item.id;
    editNewMenuItem.value = true;
  });
});

const save = () => {
  newMenuItem.put(route('admin.settings.frontend.header.menu.update', {menu: menuId.value}), {
    onSuccess: () => {
      closeMe();
      toast.success(__('Menu saved'));
      emitter.emit('menu:reload');
    },
    onError: (responseErrors) => {
      for (let key in responseErrors) {
        if (responseErrors.hasOwnProperty(key)) {
          toast.error(responseErrors[key])
        }
      }
    },
  });
}
</script>

<template>
  <DialogModal :show="editNewMenuItem" max-width="md" @close="closeMe">
    <template #title>
      {{ __('Edit Menu Item') }}
    </template>

    <template #content>
      <div class="flex flex-col space-y-3">
        <TextInput v-model="newMenuItem.title" :placeholder="__('Title')" class="w-full"/>

        <input-error :message="newMenuItem.errors?.title" class="mt-2"/>

        <fieldset class="border-b border-t border-gray-200">
          <legend class="sr-only">{{ __('Notifications') }}</legend>
          <div class="divide-y divide-gray-200">

            <div class="pb-4 pt-3.5">
              <label class="block text-sm font-medium leading-6 text-gray-900 mb-2" for="type">
                {{ __('Type') }}
              </label>

              <SelectInput :values="['page', 'link', 'template']" v-model="newMenuItem.type"/>

              <input-error :message="newMenuItem.errors?.type" class="mt-2"/>
            </div>

            <div v-if="newMenuItem.type === 'link'" class="relative flex items-start pb-4 pt-3.5">
              <div class="min-w-0 flex-1 text-sm leading-6">
                <label class="font-medium text-gray-900" for="open-new-window">
                  {{ __('Open in a new tab') }}
                </label>
              </div>
              <div class="ml-3 flex h-6 items-center">

                <input id="open-new-window" v-model="newMenuItem.open_new_window"
                       class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600"
                       type="checkbox"/>

                <input-error :message="newMenuItem.errors?.open_new_window" class="mt-2"/>

              </div>
            </div>
          </div>
        </fieldset>

        <div v-if="newMenuItem.type === 'link'">
          <label class="block text-sm font-medium leading-6 text-gray-900 mb-2" for="page">
            {{ __('Link URL') }}
          </label>

          <TextInput v-model="newMenuItem.external_link" class="w-full" :placeholder="__('URL')"/>

          <input-error :message="newMenuItem.errors?.external_link" class="mt-2"/>
        </div>

        <div v-if="newMenuItem.type === 'page'">
          <label class="block text-sm font-medium leading-6 text-gray-900 mb-2" for="page">
            {{ __('Page') }}
          </label>
          <select v-if="appPages?.length > 0" id="page" v-model="newMenuItem.app_page_id"
                  class="block w-full min-w-0 flex-1 rounded-md border border-gray-300 text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm sm:leading-6"
                  name="page">
            <option v-for="item in appPages" :key="item.id" :value="item.id">
              {{ item.title }}
            </option>
          </select>

          <AlertInfo v-else class="text-xs">
            {{ __('No pages found.') }}
          </AlertInfo>

          <input-error :message="newMenuItem.errors?.app_page_id" class="mt-2"/>
        </div>

        <div v-if="newMenuItem.type === 'template'">
          <label class="block text-sm font-medium leading-6 text-gray-900 mb-2" for="template">
            {{ __('Template') }}
          </label>

          <select id="template" v-model="newMenuItem.template"
                  class="block w-full min-w-0 flex-1 rounded-md border border-gray-300 text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm sm:leading-6"
                  name="template">
            <option value="documentation">{{ __('Documentation') }}</option>
            <option value="roadmap">{{ __('Roadmap') }}</option>
            <option value="changelog">{{ __('Changelog') }}</option>
            <option value="about">{{ __('About') }}</option>
            <option value="contact">{{ __('Contact') }}</option>
            <option value="support">{{ __('Support') }}</option>
            <option value="blog">{{ __('Blog') }}</option>
          </select>

          <input-error :message="newMenuItem.errors?.template" class="mt-2"/>
        </div>
      </div>
    </template>

    <template #footer>
      <SecondaryButton @click="closeMe">
        {{ __('Cancel') }}
      </SecondaryButton>

      <PrimaryButton :class="{ 'opacity-25': newMenuItem.processing || newMenuItem.title === '' }"
                     :disabled="newMenuItem.processing || newMenuItem.title === ''" class="ms-3"
                     @click="save">
        {{ __('Save Changes') }}
      </PrimaryButton>
    </template>
  </DialogModal>
</template>

