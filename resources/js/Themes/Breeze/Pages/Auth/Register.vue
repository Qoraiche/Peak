<script setup>
import {Link, useForm, usePage} from '@inertiajs/vue3';
import AuthenticationCard from '@/Themes/Breeze/Components/AuthenticationCard.vue';
import Checkbox from '@/Themes/Breeze/Components/Checkbox.vue';
import InputError from '@/Themes/Breeze/Components/InputError.vue';
import InputLabel from '@/Themes/Breeze/Components/InputLabel.vue';
import PrimaryButton from '@/Themes/Breeze/Components/PrimaryButton.vue';
import TextInput from '@/Themes/Breeze/Components/TextInput.vue';
import {inject, ref} from 'vue';
import {__} from '@peak/Composables/useTranslate.js';
import SocialAuth from "@/Themes/Breeze/Includes/SocialAuth.vue";

const route = inject('route');

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
  country: '',
  phone: '',
});

const page = usePage();

const tosUrl = page.props.front.tos_url;
const privacy_policy_url = page.props?.privacy_policy_url;

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};

const phoneValid = ref(true);

const validatePhone = (status) => {
  // phoneValid.value = status.valid;
}

const countryChanged = (countryInfo) => {
  form.country = countryInfo.iso2
};

const agreementText = __(
    'I agree to the :terms_of_service and :privacy_policy',
    {
      terms_of_service: `<a href="${tosUrl}" class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" target="_blank">{{ __('Terms of Service')}}</a>`,
      privacy_policy: `<a href="${privacy_policy_url}" class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" target="_blank">{{__('Privacy Policy')}}</a>`
    }
);

</script>

<template>

  <AuthenticationCard :title="__('Register')">

    <template #footer>
      <div class="flex flex-col items-center w-full mt-6 text-sm"><span>
        {{ __('Already have an account?') }}
      </span>

        <Link :href="route('login')" class="font-bold text-gray-800">
          {{ __('Log in') }}
        </Link>
      </div>
    </template>

    <template #title>
      {{ __('Register') }}
    </template>

    <form @submit.prevent="submit">
      <div>
        <InputLabel :value="__('Name')" for="name"/>
        <TextInput
            id="name"
            v-model="form.name"
            autocomplete="name"
            autofocus
            class="block w-full mt-1"
            required
            type="text"
        />
        <InputError :message="form.errors.name" class="mt-2"/>
      </div>

      <div class="mt-4">
        <InputLabel :value="__('Email')" for="email"/>
        <TextInput
            id="email"
            v-model="form.email"
            autocomplete="username"
            class="block w-full mt-1"
            required
            type="email"
        />
        <InputError :message="form.errors.email" class="mt-2"/>
      </div>

      <div class="mt-4">
        <InputLabel :value="__('Phone')" for="phone"/>

        <vue-tel-input v-model="form.phone"
                       :showSearchBox="true"
                       :validCharactersOnly="true"
                       class="mt-1"
                       mode="international"
                       required
                       v-bind="{required: true,  autocomplete: 'on', inputOptions: {showDialCode: true}}"
                       @validate="validatePhone" @country-changed="countryChanged"/>

        <template v-if="form.phone !== ''">
          <InputError v-if="!phoneValid" :message="__('Invalid phone number')" class="mt-2"/>
        </template>

        <InputError :message="form.errors.phone" class="mt-2"/>
        <InputError :message="form.errors.country" class="mt-2"/>
      </div>

      <div class="mt-4">
        <InputLabel :value="__('Password')" for="password"/>
        <TextInput
            id="password"
            v-model="form.password"
            autocomplete="new-password"
            class="block w-full mt-1"
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
            class="block w-full mt-1"
            required
            type="password"
        />
        <InputError :message="form.errors.password_confirmation" class="mt-2"/>
      </div>

      <div v-if="tosUrl" class="mt-4">
        <InputLabel for="terms">
          <div class="flex items-center">
            <Checkbox id="terms" v-model:checked="form.terms" name="terms" required/>

            <div class="ms-2" v-html="agreementText"></div>
          </div>
          <InputError :message="form.errors.terms" class="mt-2"/>
        </InputLabel>
      </div>

      <div class="flex items-center mt-4">

        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                       class="justify-center w-full">
          {{ __('Register') }}
        </PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
