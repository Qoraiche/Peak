<script setup>

import TextInput from "@peak/Components/Admin/Input.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import {inject, ref} from "vue";
import {useToast} from "vue-toastification";
import Card from "@peak/Components/Admin/Card.vue";
import {__} from "@peak/Composables/useTranslate.js";


const page = usePage();

const props = defineProps({
  errors: Object,
  heading_1_text: String | null,
  heading_2_text: String | null,
  register_button_text: String | null,
  login_button_text: String | null,
  image_path: String | null
});

const imagePreview = ref(null);
const imageInput = ref(null);

const updateImagePreview = () => {
  const photo = imageInput.value.files[0];

  if (!photo) return;

  const reader = new FileReader();

  reader.onload = (e) => {
    imagePreview.value = e.target.result;
  };

  reader.readAsDataURL(photo);
}

const toast = useToast();
const emitter = inject('emitter');

const updateHeroTextForm = useForm({
  heading_1_text: props.heading_1_text,
  heading_2_text: props.heading_2_text,
  register_button_text: props.register_button_text,
  login_button_text: props.login_button_text,
  image_path: props.image_path,
  _method: 'put'
});

const save = () => {
  if (typeof updateHeroTextForm.image_path === 'string') {
    updateHeroTextForm.image_path = null;
  }

  updateHeroTextForm.post(route('admin.settings.frontend.hero.update'), {
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('Changes Saved'));
      updateHeroTextForm.reset('image_path');
    },

    onError: (responseErrors) => {
      for (let key in responseErrors) {
        if (responseErrors.hasOwnProperty(key)) {
          toast.error(responseErrors[key])
        }
      }

      updateHeroTextForm.reset('image_path');
    }
  });
};

const deleteImage = () => {
  if (props.image_path !== null) {
    router.delete(route('admin.settings.frontend.hero.image.delete'), {
      preserveScroll: true,
    });
  } else {
    imagePreview.value = null;
  }
};
</script>

<template>
  <AdminLayout :description="__('Modify the homepage hero section (text/image)')" :title="__('Hero')">
    <template #actions>
      <PrimaryButton :class="{ 'opacity-25': !updateHeroTextForm.isDirty || updateHeroTextForm.processing }"
                     :disabled="!updateHeroTextForm.isDirty || updateHeroTextForm.processing"
                     :loading="updateHeroTextForm.processing"
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
              <div class="flex flex-col space-y-2">
                <InputLabel :value="__('Heading Text')" for="heading_1_text"/>
                <TextInput id="heading_1_text" v-model="updateHeroTextForm.heading_1_text" class="w-full"/>
                <InputError :message="errors?.heading_1_text" class="mt-1"/>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('Sub Heading Text')" for="heading_2_text"/>
                  <TextInput id="heading_2_text" v-model="updateHeroTextForm.heading_2_text"
                             class="w-full"/>
                  <InputError :message="errors?.heading_2_text" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('Primary Button Text')" for="register_button_text"/>
                  <TextInput id="register_button_text" v-model="updateHeroTextForm.register_button_text"
                             class="w-full"/>
                  <InputError :message="errors?.register_button_text" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel :value="__('Secondary Button Text')" for="login_button_text"/>
                  <TextInput id="login_button_text" v-model="updateHeroTextForm.login_button_text"
                             class="w-full"/>
                  <InputError :message="errors?.login_button_text" class="mt-1"/>
                </div>
              </div>

              <div class="flex flex-col col-span-2 p-3 space-y-3 rounded-md bg-gray-50">
                <div class="flex items-center font-semibold text-gray-700 gap-x-2">
                  <span>{{ __('Image') }}</span>
                  <span v-if="image_path || imagePreview" class="cursor-pointer" @click="deleteImage">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-400 hover:text-red-600"
                                         data-slot="icon"
                                         fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                            stroke-linecap="round"
                                            stroke-linejoin="round">
                                        </path>
                                    </svg>
                                </span>
                </div>

                <img v-if="image_path && !imagePreview" :src="image_path" alt="main logo"
                     class="max-w-[350px] inline-block"/>

                <img v-if="imagePreview" :src="imagePreview" alt="main logo"
                     class="max-w-[350px] inline-block"/>

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

                      <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span v-if="image_path || imagePreview" class="font-semibold">
                                              {{ __('Click to change Hero image') }}
                                            </span>
                        <span v-else class="font-semibold">
                          {{ __('Click to Upload Hero image') }}
                        </span>
                      </p>

                      <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, or SVG</p>
                    </div>
                    <input id="main-logo" ref="imageInput" accept=".jpg, .jpeg, .png, .svg" class="sr-only"
                           type="file"
                           @change="updateImagePreview"
                           @input="updateHeroTextForm.image_path = $event.target.files[0]"/>
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
