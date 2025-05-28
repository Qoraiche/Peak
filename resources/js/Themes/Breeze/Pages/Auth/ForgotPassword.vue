<script setup>
import {useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Themes/Breeze/Components/AuthenticationCard.vue';
import InputError from '@/Themes/Breeze/Components/InputError.vue';
import InputLabel from '@/Themes/Breeze/Components/InputLabel.vue';
import PrimaryButton from '@/Themes/Breeze/Components/PrimaryButton.vue';
import TextInput from '@/Themes/Breeze/Components/TextInput.vue';
import SuccessAlert from "@/Themes/Breeze/Components/Alerts/SuccessAlert.vue";
import {inject} from "vue";

defineProps({
  status: String,
});

const route = inject('route');

const form = useForm({
  email: '',
});

const submit = () => {
  form.post(route('password.email'));
};
</script>

<template>

  <AuthenticationCard title="Forgot Password">

    <template #title>
      {{ __('Forgot password') }}
    </template>

    <div class="mb-4 text-sm text-gray-600">
      {{
        __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.')
      }}
    </div>

    <div v-if="status" class="mb-4">
      <SuccessAlert>
        {{ status }}
      </SuccessAlert>
    </div>

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

      <div class="flex items-center justify-end mt-4">
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                       class="w-full justify-center">
          {{ __('Email Password Reset Link') }}
        </PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
