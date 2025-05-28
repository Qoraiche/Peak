<script setup>
import {useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Themes/Breeze/Components/AuthenticationCard.vue';
import InputError from '@/Themes/Breeze/Components/InputError.vue';
import InputLabel from '@/Themes/Breeze/Components/InputLabel.vue';
import PrimaryButton from '@/Themes/Breeze/Components/PrimaryButton.vue';
import TextInput from '@/Themes/Breeze/Components/TextInput.vue';
import {inject} from "vue";

const route = inject('route');

const props = defineProps({
  email: String,
  token: String,
});

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post(route('password.update'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <AuthenticationCard :title="__('Reset Password')">

    <template #title>
      {{ __('Reset Password') }}
    </template>

    <form @submit.prevent="submit">
      <div>
        <InputLabel :value="__('Email')" for="email"/>
        <TextInput
            id="email"
            v-model="form.email"
            autocomplete="username"
            autofocus
            class="mt-1 block w-full"
            required
            type="email"
        />
        <InputError :message="form.errors.email" class="mt-2"/>
      </div>

      <div class="mt-4">
        <InputLabel :value="__('Password')" for="password"/>
        <TextInput
            id="password"
            v-model="form.password"
            autocomplete="new-password"
            class="mt-1 block w-full"
            required
            type="password"
        />
        <InputError :message="form.errors.password" class="mt-2"/>
      </div>

      <div class="mt-4">
        <InputLabel :value="__('Confirm Password')" for="password_confirmation"/>
        <TextInput
            id="password_confirmation"
            v-model="form.password_confirmation"
            autocomplete="new-password"
            class="mt-1 block w-full"
            required
            type="password"
        />
        <InputError :message="form.errors.password_confirmation" class="mt-2"/>
      </div>

      <div class="flex items-center justify-end mt-4">
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                       class="w-full justify-center">
          {{ __('Reset Password') }}
        </PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
