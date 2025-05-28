<script setup>

import {router, useForm} from "@inertiajs/vue3";
import {CloudArrowUpIcon} from "@heroicons/vue/24/solid/index.js";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import {ref} from "vue";
import {useToast} from "vue-toastification";
import {Trash2} from "lucide-vue-next";
import {__} from "@peak/Composables/useTranslate.js";

const props = defineProps({
  user: Object
});

const photoInput = ref(null);

const selectNewPhoto = () => {
  photoInput.value.click();
};

const form = useForm({
  _method: 'PUT',
  photo: null,
});

const errors = ref({});
const toast = useToast();

const updateProfilePhoto = () => {
  if (photoInput.value) {
    form.photo = photoInput.value.files[0];
  }

  form.post(route('admin.user-management.users.update.photo', {user: props.user.id}), {
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('Profile photo saved'));
      clearPhotoFileInput();
    },
    onError: err => {
      for (let key in err) {
        if (err.hasOwnProperty(key)) {
          toast.error(err[key])
        }
      }
    },
  });
};

const deletePhoto = () => {
  router.delete(route('admin.user-management.users.delete.photo', {user: props.user.id}), {
    preserveScroll: true,
    onSuccess: () => {
      clearPhotoFileInput();
      toast.success(__('Profile photo removed'));
    }
  });
};

const clearPhotoFileInput = () => {
  if (photoInput.value?.value) {
    photoInput.value.value = null;
  }
};
</script>

<template>
  <div class="flex-shrink-0 flex flex-col items-center space-y-2">
    <!-- Profile Photo File Input -->
    <input
        ref="photoInput"
        accept="image/png, image/jpeg, image/jpg"
        class="hidden"
        type="file"
        @change="updateProfilePhoto">

    <div v-tooltip="__('Select a new photo')" class="relative group cursor-pointer" @click.prevent="selectNewPhoto">

      <img :src="user.profile_photo_url" alt="" class="h-24 w-24 rounded-full object-cover"/>

      <span aria-hidden="true" class="absolute inset-0 rounded-full shadow-inner"/>
      <span
          class="absolute inset-0 flex items-center justify-center group-hover:bg-gray-800 group-hover:opacity-25 rounded-full shadow-inner"/>
      <span
          class="absolute inset-0 flex items-center justify-center invisible group-hover:visible transition ease-in-out">
              <CloudArrowUpIcon class="h-8 w-8 fill-current text-white"/>
            </span>
    </div>

    <SecondaryButton v-if="user.profile_photo_path !== null" class="text-sm" @click="deletePhoto">

      <template #pre>
        <Trash2 class="-ml-1 ltr:mr-3 rtl:ml-3 h-5 w-5"/>
      </template>

      {{ __('Remove photo') }}
    </SecondaryButton>

  </div>
</template>

<style scoped>

</style>
