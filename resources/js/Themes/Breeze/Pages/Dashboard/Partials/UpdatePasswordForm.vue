<script setup>
import {inject, ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import ActionMessage from '@/Themes/Breeze/Components/ActionMessage.vue';
import FormSection from '@/Themes/Breeze/Components/FormSection.vue';
import InputError from '@/Themes/Breeze/Components/InputError.vue';
import InputLabel from '@/Themes/Breeze/Components/InputLabel.vue';
import PrimaryButton from '@/Themes/Breeze/Components/PrimaryButton.vue';
import TextInput from '@/Themes/Breeze/Components/TextInput.vue';
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const route = inject('route');

const toast = useToast();

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const updatePassword = () => {
  form.put(route('user-password.update'), {
    errorBag: 'updatePassword',
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('Changes saved'));
      form.reset()
    },
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation');
        passwordInput.value.focus();
      }

      if (form.errors.current_password) {
        form.reset('current_password');
        currentPasswordInput.value.focus();
      }
    },
  });
};
</script>

<template>
  <FormSection @submitted="updatePassword">
    <template #title>
      {{ __('Update Password') }}
    </template>

    <template #description>
      {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </template>

    <template #form>
      <div class="col-span-6">
        <InputLabel :value="__('Current Password')" for="current_password"/>
        <TextInput
            id="current_password"
            ref="currentPasswordInput"
            v-model="form.current_password"
            autocomplete="current-password"
            class="mt-1 block w-full"
            type="password"
        />

        <InputError :message="form.errors.current_password" class="mt-2"/>
      </div>

      <div class="col-span-6">
        <InputLabel :value="__('New Password')" for="password"/>
        <TextInput
            id="password"
            ref="passwordInput"
            v-model="form.password"
            autocomplete="new-password"
            class="mt-1 block w-full"
            type="password"
        />

        <InputError :message="form.errors.password" class="mt-2"/>
      </div>

      <div class="col-span-6">
        <InputLabel :value="__('Confirm Password')" for="password_confirmation"/>
        <TextInput
            id="password_confirmation"
            v-model="form.password_confirmation"
            autocomplete="new-password"
            class="mt-1 block w-full"
            type="password"
        />

        <InputError :message="form.errors.password_confirmation" class="mt-2"/>
      </div>
    </template>

    <template #actions>
      <ActionMessage :on="form.recentlySuccessful" class="me-3">
        {{ __('Saved.') }}
      </ActionMessage>

      <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        {{ __('Save Changes') }}
      </PrimaryButton>
    </template>
  </FormSection>
</template>
