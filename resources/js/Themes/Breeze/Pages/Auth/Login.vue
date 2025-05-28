<script setup>
import {Link, useForm, usePage} from '@inertiajs/vue3';
import AuthenticationCard from '@/Themes/Breeze/Components/AuthenticationCard.vue';
import {LockClosedIcon} from "@heroicons/vue/24/outline";
import InputError from '@/Themes/Breeze/Components/InputError.vue';
import InputLabel from '@/Themes/Breeze/Components/InputLabel.vue';
import PrimaryButton from '@/Themes/Breeze/Components/PrimaryButton.vue';
import TextInput from '@/Themes/Breeze/Components/TextInput.vue';
import {Switch, SwitchGroup, SwitchLabel} from '@headlessui/vue'
import SuccessAlert from "@/Themes/Breeze/Components/Alerts/SuccessAlert.vue";
import {computed, inject, onMounted, ref, watch} from "vue";
import {useToastification} from '@peak/Composables/useToast.js'
import SocialAuth from "@/Themes/Breeze/Includes/SocialAuth.vue";
import {useRecaptcha} from "@peak/Composables/ReCaptcha.js";
import ErrorAlert from "@/Themes/Breeze/Components/Alerts/ErrorAlert.vue";
import SecondaryButton from "@/Themes/Breeze/Components/SecondaryButton.vue";

defineProps({
  canResetPassword: Boolean,
  status: String
});

const route = inject('route');
const page = usePage();

const loginWithUsername = computed(() => page.props.front.login_with_username);

let toast = null;

onMounted(async () => {
  toast = await useToastification()

  if (page.props.front.recaptcha.enabled) {
    useRecaptcha();
  }
});

const emailInput = ref(null);

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const isMagicLogin = ref(false);
const magicLinkSent = ref(false);

const useMagicLogin = () => {
  isMagicLogin.value = !isMagicLogin.value;

  if (magicLinkSent.value) {
    magicLinkSent.value = false;
  }
};

watch(isMagicLogin, (newVal) => {
  if (emailInput.value && newVal) {
    emailInput.value.focus();
    form.clearErrors();
    form.reset();
  }

  if (!newVal && window.grecaptcha) {
    window.grecaptcha?.reset();
  }
});

const submit = (event) => {
  if (isMagicLogin.value) {
    // send magic link
    form.post(route('login.magic'), {
      onSuccess: () => {
        magicLinkSent.value = true;
      },
      onError: () => {
        toast.error(__('Something went wrong'));
      },
    });

    return;
  }

  form.transform(data => ({
    ...data,
    remember: form.remember ? 'on' : '',
    'g-recaptcha-response': page.props.front.recaptcha.enabled ? event.target['g-recaptcha-response'].value : '',
  })).post(route('login'), {
    onFinish: () => {
      window.grecaptcha?.reset();
      form.reset('password');
    },
  });
};

const logginAsDemoUser = () => {
  form.email = 'demo-user@larapeak.com';
  form.password = 'demouser123';
  submit();
};

const logginAsAdminUser = () => {
  form.email = 'demo-admin@larapeak.com';
  form.password = 'demoadmin123';
  submit();
}

</script>

<template>
  <AuthenticationCard :title="__('Login')">
    <template #title>
      {{ __('Log in') }}
    </template>

    <template #footer>
      <div class="mt-6 flex w-full flex-col items-center text-sm">
        <span>{{ __('Don\'t have an account?') }}</span>
        <Link :href="route('register')" class="text-gray-800 font-bold">
          {{ __('Register') }}
        </Link>
      </div>
    </template>

    <div v-if="$page.props.isDemo" class="grid grid-cols-1 md:grid-cols-2 gap-4 my-6">
      <SecondaryButton @click="logginAsDemoUser" class="justify-center">{{ __('Demo User') }}</SecondaryButton>
      <SecondaryButton @click="logginAsAdminUser" class="justify-center">{{ __('Demo Admin') }}</SecondaryButton>
    </div>

    <div v-if="status" class="mb-4">
      <SuccessAlert>
        {{ status }}
      </SuccessAlert>
    </div>

    <div v-if="magicLinkSent" class="mb-4">
      <SuccessAlert>
        {{
          __('If an account with this email exists, we’ve sent a magic link to your inbox. Please check your email, including your spam folder, and follow the link to sign in securely.')
        }}
      </SuccessAlert>
    </div>

    <form v-else @submit.prevent="submit">
      <div>
        <InputLabel :value="loginWithUsername ? __('Username or Email') : __('Email')" for="email"/>
        <TextInput
            id="email"
            ref="emailInput"
            v-model="form.email"
            :type="loginWithUsername ? 'text' : 'email'"
            autocomplete="email"
            autofocus
            class="mt-1 block w-full"
        />

        <ErrorAlert v-if="form.errors?.email || form.errors?.email_or_username" class="mt-2">
          {{ form.errors?.email || form.errors?.email_or_username }}
        </ErrorAlert>
      </div>

      <div v-show="!isMagicLogin" class="mt-4">
        <div class="flex justify-between items-center">
          <InputLabel for="password" value="Password"/>
          <Link v-if="canResetPassword && $page.props.front.reset_password_enabled"
                :href="route('password.request')"
                class="underline text-xs text-gray-600 hover:text-gray-900 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            {{ __('Forgot your password?') }}
          </Link>
        </div>

        <TextInput
            id="password"
            v-model="form.password"
            :required="!isMagicLogin"
            autocomplete="current-password"
            class="mt-1 block w-full"
            type="password"
        />
        <InputError :message="form.errors.password" class="mt-2"/>
      </div>

      <div v-show="!isMagicLogin" class="block mt-4">
        <label class="flex items-center py-3">
          <SwitchGroup as="div" class="flex items-center gap-x-3">
            <Switch v-model="form.remember"
                    :class="[form.remember ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-hidden focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
            <span
                :class="[form.remember ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-xs ring-0 transition duration-200 ease-in-out']"
                aria-hidden="true"/>
            </Switch>
            <SwitchLabel as="span" class="text-sm">
              <span class="text-gray-700">{{ __('Remember me') }}</span>
            </SwitchLabel>
          </SwitchGroup>
        </label>

        <div v-if="$page.props.front.recaptcha.enabled">
          <div :data-sitekey="$page.props.front.api_keys.recaptcha" :data-theme="$page.props.front.recaptcha.theme"
               class="g-recaptcha"></div>
        </div>

        <ErrorAlert v-if="form.errors['g-recaptcha-response']" class="mt-2">
          <div v-if="typeof form.errors['g-recaptcha-response'] === 'object'" class="flex flex-col gap-y-1">
                        <span v-for="error in form.errors['g-recaptcha-response']">
                            {{ form.errors['g-recaptcha-response'][0] }}
                        </span>
          </div>

          <span v-if="typeof form.errors['g-recaptcha-response'] === 'string'">
                        {{ form.errors['g-recaptcha-response'] }}
                    </span>
        </ErrorAlert>
      </div>

      <div class="flex items-center justify-center mt-4">
        <PrimaryButton v-if="!isMagicLogin" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                       class="w-full justify-center">
          {{ __('Continue') }}
        </PrimaryButton>

        <PrimaryButton v-if="isMagicLogin && $page.props.front.magic_login_enabled"
                       :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                       class="w-full justify-center">
          {{ __('Send Me a Magic Link') }}
        </PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
