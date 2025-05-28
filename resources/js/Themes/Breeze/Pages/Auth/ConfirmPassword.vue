<script setup>
import {inject, ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Themes/Breeze/Components/AuthenticationCard.vue';
import InputError from '@/Themes/Breeze/Components/InputError.vue';
import InputLabel from '@/Themes/Breeze/Components/InputLabel.vue';
import PrimaryButton from '@/Themes/Breeze/Components/PrimaryButton.vue';
import TextInput from '@/Themes/Breeze/Components/TextInput.vue';

const route = inject('route');

const form = useForm({
  password: '',
});

const passwordInput = ref(null);

const submit = () => {
  form.post(route('password.confirm'), {
    onFinish: () => {
      form.reset();

      passwordInput.value.focus();
    },
  });
};
</script>

<template>
  <AuthenticationCard :title="__('Confirm your password')">
    <div class="mb-4 text-sm text-gray-600">
      {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel :value="__('Password')" for="password"/>
        <TextInput
            id="password"
            ref="passwordInput"
            v-model="form.password"
            autocomplete="current-password"
            autofocus
            class="mt-1 block w-full"
            required
            type="password"
        />
        <InputError :message="form.errors?.password" class="mt-2"/>
      </div>

      <div class="flex justify-end mt-4">
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                       class="w-full justify-center">
          {{ __('Confirm') }}
        </PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
