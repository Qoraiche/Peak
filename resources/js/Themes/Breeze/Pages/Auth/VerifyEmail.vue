<script setup>
import {computed, inject} from 'vue';
import {Link, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Themes/Breeze/Components/AuthenticationCard.vue';
import PrimaryButton from '@/Themes/Breeze/Components/PrimaryButton.vue';

const route = inject('route');

const props = defineProps({
  status: String,
});

const form = useForm({});

const submit = () => {
  form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
  <AuthenticationCard :title="__('Email Verification')">

    <template #title>
      {{ __('Email Verification') }}
    </template>

    <div class="mb-4 text-sm text-gray-600">
      {{
        __("Before continuing, could you verify your email address by clicking on the link we just emailed to you? Ifyou didn't receive the email, we will gladly send you another.")
      }}
    </div>

    <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-green-600">
      {{ __("A new verification link has been sent to the email address you provided in your profile settings.") }}
    </div>

    <form @submit.prevent="submit">
      <div class="mt-4 flex items-center justify-between">
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          {{ __("Resend Verification Email") }}
        </PrimaryButton>

        <div>
          <Link
              :href="route('logout')"
              as="button"
              class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 ms-2"
              method="post"
          >
            {{ __("Log Out") }}
          </Link>
        </div>
      </div>
    </form>
  </AuthenticationCard>
</template>
