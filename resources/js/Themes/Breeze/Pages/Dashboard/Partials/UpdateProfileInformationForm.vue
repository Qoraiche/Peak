<script setup>
import {inject, ref} from 'vue';
import {Link, router, useForm} from '@inertiajs/vue3';
import FormSection from '@/Themes/Breeze/Components/FormSection.vue';
import InputError from '@/Themes/Breeze/Components/InputError.vue';
import InputLabel from '@/Themes/Breeze/Components/InputLabel.vue';
import PrimaryButton from '@/Themes/Breeze/Components/PrimaryButton.vue';
import SecondaryButton from '@/Themes/Breeze/Components/SecondaryButton.vue';
import TextInput from '@/Themes/Breeze/Components/TextInput.vue';
import AlertInfo from "@/Themes/Breeze/Components/Alerts/AlertInfo.vue";
import TextArea from "@/Themes/Breeze/Components/TextArea.vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const route = inject('route');

const props = defineProps({
  user: Object,
});

const form = useForm({
  _method: 'PUT',
  name: props.user.name,
  email: props.user.email,
  username: props.user.username,
  bio: props.user.bio,
  mobile_number: props.user.mobile_number,
  twitter: props.user.twitter,
  facebook: props.user.facebook,
  instagram: props.user.instagram,
  youtube: props.user.youtube,
  discord: props.user.discord,
  tiktok: props.user.tiktok,
  photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);
const toast = useToast();

const updateProfileInformation = () => {
  if (photoInput.value) {
    form.photo = photoInput.value.files[0];
  }

  form.post(route('user-profile-information.update'), {
    errorBag: 'updateProfileInformation',
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('Changes saved'));
      clearPhotoFileInput()
    },
  });
};

const sendEmailVerification = () => {
  verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
  photoInput.value.click();
};

const updatePhotoPreview = () => {
  const photo = photoInput.value.files[0];

  if (!photo) return;

  const reader = new FileReader();

  reader.onload = (e) => {
    photoPreview.value = e.target.result;
  };

  reader.readAsDataURL(photo);
};

const deletePhoto = () => {
  router.delete(route('current-user-photo.destroy'), {
    preserveScroll: true,
    onSuccess: () => {
      photoPreview.value = null;
      clearPhotoFileInput();
    },
  });
};

const clearPhotoFileInput = () => {
  if (photoInput.value?.value) {
    photoInput.value.value = null;
  }
};

const phoneValid = ref(false);

const validatePhone = (status) => {
  phoneValid.value = status.valid;
}
</script>

<template>
  <FormSection @submitted="updateProfileInformation">
    <template #title>
      {{ __('Profile') }}
    </template>

    <template #description>
      {{ __("Update your account's profile information and email address.") }}
    </template>

    <template #form>
      <!-- Profile Photo -->
      <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6">
        <!-- Profile Photo File Input -->
        <input
            id="photo"
            ref="photoInput"
            class="hidden"
            type="file"
            @change="updatePhotoPreview"
        >

        <InputLabel for="photo" value="Photo"/>

        <!-- Current Profile Photo -->
        <div v-show="! photoPreview" class="mt-2">
          <img :alt="user.name" :src="user.profile_photo_url" class="rounded-full size-20 object-cover">
        </div>

        <!-- New Profile Photo Preview -->
        <div v-show="photoPreview" class="mt-2">
                    <span
                        :style="'background-image: url(\'' + photoPreview + '\');'"
                        class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                    />
        </div>

        <SecondaryButton class="mt-2 me-2" type="button" @click.prevent="selectNewPhoto">
          {{ __('Select A New Photo') }}
        </SecondaryButton>

        <SecondaryButton
            v-if="user.profile_photo_path"
            class="mt-2"
            type="button"
            @click.prevent="deletePhoto"
        >
          {{ __('Remove Photo') }}
        </SecondaryButton>

        <InputError :message="form.errors.photo" class="mt-2"/>
      </div>

      <!-- Name -->
      <div class="col-span-6">
        <InputLabel :value="__('Name')" for="name"/>
        <TextInput
            id="name"
            v-model="form.name"
            autocomplete="name"
            class="mt-1 block w-full"
            required
            type="text"
        />
        <InputError :message="form.errors.name" class="mt-2"/>
      </div>

      <!-- Email -->
      <div class="col-span-6">
        <InputLabel :value="__('Email')" for="email"/>
        <TextInput
            id="email"
            v-model="form.email"
            autocomplete="username"
            class="mt-1 block w-full"
            required
            type="email"
        />
        <InputError :message="form.errors.email" class="mt-2"/>

        <div v-if="$page.props.front.hasEmailVerification && user.email_verified_at === null">

          <AlertInfo class="mt-2">
            {{ __('Your email address is unverified.') }}

            <Link :href="route('verification.send')"
                  as="button"
                  class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800"
                  method="post"
                  @click.prevent="sendEmailVerification"
            >
              {{ __('Click here to re-send the verification email.') }}
            </Link>
          </AlertInfo>

          <div v-show="verificationLinkSent"
               class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
            {{ __('A new verification link has been sent to your email address.') }}
          </div>
        </div>
      </div>

      <div class="col-span-6">
        <InputLabel :value="__('Username')" for="username"/>
        <TextInput
            id="username"
            v-model="form.username"
            class="mt-1 block w-full"
            type="text"
        />
        <InputError :message="form.errors.username" class="mt-2"/>
      </div>

      <div class="col-span-6">
        <InputLabel :value="__('Phone Number')" class="mb-1" for="mobile_number"/>

        <vue-tel-input id="mobile_number" v-model="form.mobile_number"
                       :validCharactersOnly="true"
                       mode="international"
                       @validate="validatePhone"/>
        <InputError :message="form.errors.mobile_number" class="mt-2"/>
      </div>

      <div class="col-span-6">
        <InputLabel :value="__('Bio')" for="bio"/>
        <TextArea
            id="bio"
            v-model="form.bio"
            class="mt-1 block w-full"
            type="text"
        />
        <InputError :message="form.errors.bio" class="mt-2"/>
      </div>

      <div class="col-span-3">
        <InputLabel for="twitter" value="X (Twitter)"/>
        <TextInput
            id="twitter"
            v-model="form.twitter"
            class="mt-1 block w-full"
            type="text"
        />
        <InputError :message="form.errors.twitter" class="mt-2"/>
      </div>

      <div class="col-span-3">
        <InputLabel :value="__('Facebook')" for="facebook"/>
        <TextInput
            id="facebook"
            v-model="form.facebook"
            class="mt-1 block w-full"
            type="text"
        />
        <InputError :message="form.errors.facebook" class="mt-2"/>
      </div>

      <div class="col-span-3">
        <InputLabel :value="__('Instagram')" for="instagram"/>
        <TextInput
            id="instagram"
            v-model="form.instagram"
            class="mt-1 block w-full"
            type="text"
        />
        <InputError :message="form.errors.instagram" class="mt-2"/>
      </div>

      <div class="col-span-3">
        <InputLabel :value="__('Youtube')" for="youtube"/>
        <TextInput
            id="youtube"
            v-model="form.youtube"
            class="mt-1 block w-full"
            type="text"
        />
        <InputError :message="form.errors.youtube" class="mt-2"/>
      </div>

      <div class="col-span-3">
        <InputLabel :value="__('Discord')" for="discord"/>
        <TextInput
            id="discord"
            v-model="form.discord"
            class="mt-1 block w-full"
            type="text"
        />
        <InputError :message="form.errors.discord" class="mt-2"/>
      </div>

      <div class="col-span-3">
        <InputLabel :value="__('Tiktok')" for="tiktok"/>
        <TextInput
            id="tiktok"
            v-model="form.tiktok"
            class="mt-1 block w-full"
            type="text"
        />
        <InputError :message="form.errors.tiktok" class="mt-2"/>
      </div>
    </template>

    <template #actions>
      <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        {{ __('Save Changes') }}
      </PrimaryButton>
    </template>
  </FormSection>
</template>
