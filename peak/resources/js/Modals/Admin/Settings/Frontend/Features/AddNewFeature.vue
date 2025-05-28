<script setup>

import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import DialogModal from "@peak/Components/Admin/DialogModal.vue";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import {inject, onMounted, ref} from "vue";
import TextInput from "@peak/Components/Admin/Input.vue";
import {useForm} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";
import TinymceEditor from "@peak/Components/Admin/TinymceEditor.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import {__} from "@peak/Composables/useTranslate.js";

const addNewFeature = ref(false);

const emitter = inject('emitter');

const closeMe = () => {
  newFeatureForm.reset();
  newFeatureForm.clearErrors();
  addNewFeature.value = false;
  imagePreview.value = null;
};

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

const newFeatureForm = useForm({
  name: '',
  description: '',
  image: null,
});

const toast = useToast();

onMounted(() => {
  emitter.on('feature:add-new', function () {
    addNewFeature.value = true;
  });
});

const save = () => {
  newFeatureForm.post(route('admin.settings.frontend.features.create'), {
    onSuccess: () => {
      closeMe();
      toast.success(__('New Feature added'));
      emitter.emit('pageAdded');
    },
    onError: (responseErrors) => {
      for (let key in responseErrors) {
        if (responseErrors.hasOwnProperty(key)) {
          toast.error(responseErrors[key])
        }
      }
    },
  })
};

const deleteImage = () => {
  imagePreview.value = null;
};

</script>

<template>
  <DialogModal :show="addNewFeature" max-width="xl" @close="closeMe">
    <template #title>
      {{ __('Add New Feature') }}
    </template>

    <template #content>
      <div class="flex flex-col space-y-3">
        <TextInput v-model="newFeatureForm.name" :placeholder="__('Feature name')"
                   class="w-full py-3"/>

        <InputError :message="newFeatureForm.errors?.name" class="mt-1"/>

        <TinymceEditor
            v-model="newFeatureForm.description"
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

        <InputError :message="newFeatureForm.errors?.description" class="mt-1"/>

        <div class="bg-gray-50 p-3 col-span-2 rounded-md flex flex-col space-y-3">
          <div class="font-semibold text-gray-700 flex gap-x-2 items-center">
            <span>{{ __('Image') }}</span>
            <span v-if="imagePreview" class="cursor-pointer" @click="deleteImage">
                                    <svg aria-hidden="true" class="w-5 h-5 text-gray-400 hover:text-red-600"
                                         data-slot="icon"
                                         fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                                         xmlns="http://www.w3.org/2000/svg"><path
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"></path></svg>
                        </span>
          </div>

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
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span
                    class="font-semibold">{{ __('Add feature image') }}</span>
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF, or SVG</p>
              </div>
              <input id="main-logo" ref="imageInput" accept=".jpg, .jpeg, .gif, .png, .svg"
                     class="sr-only"
                     type="file"
                     @change="updateImagePreview" @input="newFeatureForm.image = $event.target.files[0]"/>
            </label>
          </div>

          <InputError :message="newFeatureForm.errors?.image" class="mt-1"/>
        </div>

      </div>
    </template>

    <template #footer>
      <SecondaryButton @click="closeMe">
        {{ __('Cancel') }}
      </SecondaryButton>

      <PrimaryButton :class="{'opacity-25' : newFeatureForm.processing || !newFeatureForm.isDirty}"
                     :disabled="newFeatureForm.processing || !newFeatureForm.isDirty"
                     class="ms-3"
                     @click="save">
        {{ __('Save Changes') }}
      </PrimaryButton>

    </template>
  </DialogModal>
</template>

