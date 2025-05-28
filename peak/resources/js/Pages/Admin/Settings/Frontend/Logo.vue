<script setup>

import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import {ref} from "vue";
import {useToast} from "vue-toastification";
import Card from "@peak/Components/Admin/Card.vue";
import {__} from "@peak/Composables/useTranslate.js";

const updateForm = useForm({
  main_logo: null,
  favicon_image: null,
});

const page = usePage();
const mainLogoImageInput = ref(null);
const favIconImageInput = ref(null);

const mainLogoImagePreview = ref(null);
const favIconImagePreview = ref(null);

const props = defineProps({
  errors: Object,
  mainLogoPath: String | null,
  faviconPath: String | null,
});

const toast = useToast();

const save = () => {
  updateForm.post(route('admin.settings.frontend.logo.update'), {
    preserveScroll: true,

    onSuccess: () => {
      toast.success('Settings updated.');

      router.reload({
        preserveScroll: true
      });
    },

    onError: (requestErrors) => {
      // errors.value = requestErrors;

      for (let key in requestErrors) {
        if (requestErrors.hasOwnProperty(key)) {
          toast.error(requestErrors[key])
        }
      }
    },
  });
};

const updateMainLogoPreview = () => {
  const photo = mainLogoImageInput.value.files[0];

  if (!photo) return;

  const reader = new FileReader();

  reader.onload = (e) => {
    mainLogoImagePreview.value = e.target.result;
  };

  reader.readAsDataURL(photo);
}

const updateFaviconPreview = () => {
  const photo = favIconImageInput.value.files[0];

  if (!photo) return;

  const reader = new FileReader();

  reader.onload = (e) => {
    favIconImagePreview.value = e.target.result;
  };

  reader.readAsDataURL(photo);
}
</script>

<template>
  <AdminLayout :description="__('Upload and update site logos and icons')" :title="__('Logo')">
    <template #actions>
      <PrimaryButton :class="{ 'opacity-25': !updateForm.isDirty || updateForm.processing }"
                     :disabled="!updateForm.isDirty || updateForm.processing"
                     :loading="updateForm.processing"
                     class="self-end"
                     @click="save">
        {{ __('Save Changes') }}
      </PrimaryButton>
    </template>

    <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
      <template #header>
        {{ __('Edit Settings') }}
      </template>

      <div id="logo" class="rounded-md">
        <div class="flex flex-col">
          <div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
              <div class="flex flex-col p-3 space-y-3 rounded-md bg-gray-50">
                    <span class="font-semibold text-gray-700">
                        {{ __('Main Logo') }}
                    </span>

                <img v-if="mainLogoPath && !mainLogoImagePreview" :src="mainLogoPath" alt="main logo"
                     class="max-w-[150px] inline-block"/>

                <img v-if="mainLogoImagePreview" :src="mainLogoImagePreview" alt="main logo"
                     class="max-w-[150px] inline-block"/>

                <div class="flex items-center justify-center w-full">
                  <label
                      class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                      for="main-logo">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                      <svg aria-hidden="true" class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400"
                           fill="none" viewBox="0 0 20 16" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"
                            stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"/>
                      </svg>
                      <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                          class="font-semibold">{{ __('Click to change logo') }}</span>
                      </p>
                      <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ __('PNG, JPG, or SVG') }}
                      </p>
                    </div>
                    <input id="main-logo" ref="mainLogoImageInput" accept=".jpg, .jpeg, .png, .svg" class="sr-only"
                           type="file" @change="updateMainLogoPreview"
                           @input="updateForm.main_logo = $event.target.files[0]"/>
                  </label>
                </div>
              </div>
              <div class="flex flex-col p-3 space-y-3 rounded-md bg-gray-50">

                    <span class="font-semibold text-gray-700">
                        {{ __('Favicon') }}
                    </span>

                <img v-if="faviconPath && !favIconImagePreview" :src="faviconPath" alt="favicon"
                     class="max-w-[35px] object-cover max-h-[35px] inline-block"/>

                <img v-if="favIconImagePreview" :src="favIconImagePreview" alt="favicon"
                     class="max-w-[35px] object-cover max-h-[35px] inline-block"/>

                <div class="flex items-center justify-center w-full">
                  <label
                      class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                      for="favicon-image">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                      <svg aria-hidden="true" class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400"
                           fill="none" viewBox="0 0 20 16" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"
                            stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"/>
                      </svg>
                      <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-semibold">{{ __('Click to change logo') }}</span>
                      </p>
                      <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ __('ICO (16x16px)') }}
                      </p>
                    </div>
                    <input id="favicon-image" ref="favIconImageInput" accept=".ico" class="sr-only"
                           type="file" @change="updateFaviconPreview"
                           @input="updateForm.favicon_image = $event.target.files[0]"/>
                  </label>
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
