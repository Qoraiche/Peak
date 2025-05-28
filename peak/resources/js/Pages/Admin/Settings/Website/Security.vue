<script setup>

import Card from "@peak/Components/Admin/Card.vue";
import TextInput from "@peak/Components/Admin/Input.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import SelectInput from "@peak/Components/Admin/SelectInput.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {Switch, SwitchDescription, SwitchGroup, SwitchLabel} from "@headlessui/vue";
import {useForm} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');
const toast = useToast();

const props = defineProps({
  force_https_enabled: Boolean,
  email_verification_enabled: Boolean,
  social_login_enabled: Boolean,
  login_enabled: Boolean,
  login_with_username: Boolean,
  reset_password_enabled: Boolean,
  registration_enabled: Boolean,
  two_factor_auth_enabled: Boolean,
  password_reset_expire: [Number, String],
  password_reset_throttle: [Number, String],
  session_lifetime: [Number, String],
  recaptcha_enabled: Boolean,
  recaptcha_secret: String,
  recaptcha_sitekey: String,
  recaptcha_theme: String,
  magic_login_enabled: Boolean,
  profile_management_enabled: Boolean,
  password_update_enabled: Boolean,
  teams_management_enabled: Boolean,
  account_deletion_enabled: Boolean,
  profile_browser_sessions_enabled: Boolean
});

const form = useForm({
  force_https_enabled: props.force_https_enabled,
  email_verification_enabled: props.email_verification_enabled,
  login_enabled: props.login_enabled,
  login_with_username: props.login_with_username,
  reset_password_enabled: props.reset_password_enabled,
  magic_login_enabled: props.magic_login_enabled,
  social_login_enabled: props.social_login_enabled,
  profile_management_enabled: props.profile_management_enabled,
  password_update_enabled: props.password_update_enabled,
  teams_management_enabled: props.teams_management_enabled,
  account_deletion_enabled: props.account_deletion_enabled,
  profile_browser_sessions_enabled: props.profile_browser_sessions_enabled,
  registration_enabled: props.registration_enabled,
  two_factor_auth_enabled: props.two_factor_auth_enabled,
  password_reset_expire: props.password_reset_expire,
  password_reset_throttle: props.password_reset_throttle,
  session_lifetime: props.session_lifetime,
  recaptcha_enabled: props.recaptcha_enabled,
  recaptcha_secret: props.recaptcha_secret,
  recaptcha_sitekey: props.recaptcha_sitekey,
  recaptcha_theme: props.recaptcha_theme,
});

const save = () => {
  form.put(route('admin.settings.website.security.update'), {
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    },
    onError: () => {
      toast.error(__('Something went wrong.'));
    },
  });
};
</script>

<template>
  <AdminLayout :description="__('Set security preferences')" :title="__('Security')">
    <template #actions>
      <PrimaryButton :class="{ 'opacity-25': !form.isDirty || form.processing }"
                     :disabled="!form.isDirty || form.processing" :loading="form.processing"
                     class="self-end"
                     @click="save">
        {{ __('Save Changes') }}
      </PrimaryButton>
    </template>

    <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
      <template #header>
        {{ __('Edit Settings') }}
      </template>

      <div class="grid grid-cols-1 gap-5 py-5 md:grid-cols-2">
        <div class="col-span-2 md:col-span-1">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Force HTTPS') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                  {{ __('Ensure all requests use a secure HTTPS connection.') }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.force_https_enabled"
                      :class="[form.force_https_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.force_https_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.force_https_enabled" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2 md:col-span-1">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Email verification') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                  {{ __('Require email verification before login.') }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.email_verification_enabled"
                      :class="[form.email_verification_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.email_verification_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.email_verification_enabled" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2 md:col-span-1">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Login') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                  {{ __('Enable or disable user login functionality.') }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.login_enabled"
                      :class="[form.login_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.login_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.login_enabled" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2 md:col-span-1">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Login with username') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                  {{ __('Allow users to log in using their username instead of email.') }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.login_with_username"
                      :class="[form.login_with_username ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.login_with_username ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.login_with_username" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2 md:col-span-1">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Magic Login') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                  {{ __('Enable or disable magic login.') }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.magic_login_enabled"
                      :class="[form.magic_login_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.magic_login_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.magic_login_enabled" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2 md:col-span-1">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Social Login') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                  {{ __('Enable or disable social login.') }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.social_login_enabled"
                      :class="[form.social_login_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.social_login_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.social_login_enabled" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2 md:col-span-1">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Profile Management') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                  {{ __('Enable or disable Profile Management.') }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.profile_management_enabled"
                      :class="[form.profile_management_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.profile_management_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.profile_management_enabled" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2 md:col-span-1">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Password Update') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                  {{ __('Enable or disable Profile Password update.') }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.password_update_enabled"
                      :class="[form.password_update_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.password_update_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.password_update_enabled" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2 md:col-span-1">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Teams Management') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                  {{ __('Enable or disable Profile Teams Management.') }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.teams_management_enabled"
                      :class="[form.teams_management_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.teams_management_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.teams_management_enabled" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2 md:col-span-1">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Account Deletion') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                  {{ __('Enable or disable Account deletion.') }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.account_deletion_enabled"
                      :class="[form.account_deletion_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.account_deletion_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.account_deletion_enabled" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2 md:col-span-1">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Profile Browser Sessions') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                  {{ __('Enable or disable Profile browser sessions.') }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.profile_browser_sessions_enabled"
                      :class="[form.profile_browser_sessions_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.profile_browser_sessions_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.profile_browser_sessions_enabled" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2 md:col-span-1">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Reset password') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                  {{ __('Enable password reset via email.') }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.reset_password_enabled"
                      :class="[form.reset_password_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.reset_password_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.reset_password_enabled" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2 md:col-span-1">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Registration') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                  {{ __('Allow new users to create an account.') }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.registration_enabled"
                      :class="[form.registration_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.registration_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.registration_enabled" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2 md:col-span-1">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Two Factor Authentication') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                {{ __('Add an extra layer of login security with 2FA.') }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.two_factor_auth_enabled"
                      :class="[form.two_factor_auth_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.two_factor_auth_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.two_factor_auth_enabled" class="mt-1"/>
          </div>
        </div>

        <!-- expire -->
        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Reset Password Expire')" for="password_reset_expire"/>
            <TextInput id="password_reset_expire" v-model="form.password_reset_expire" class="block w-full"
                       placeholder="60"/>
            <InputError :message="form.errors?.password_reset_expire" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Reset Password Throttle')" for="password_reset_throttle"/>
            <TextInput id="password_reset_throttle" v-model="form.password_reset_throttle" class="block w-full"
                       placeholder="60"/>
            <InputError :message="form.errors?.password_reset_throttle" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Session Timeout')" for="session_lifetime"/>
            <TextInput id="session_lifetime" v-model="form.session_lifetime" class="block w-full"
                       placeholder="9999"/>
            <InputError :message="form.errors?.session_lifetime" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2 md:col-span-1">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Recaptcha') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                {{ __('Protect forms from bots using Google reCAPTCHA.') }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.recaptcha_enabled"
                      :class="[form.recaptcha_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.recaptcha_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.recaptcha_enabled" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.recaptcha_enabled">
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Recaptcha secret')" for="recaptcha_secret"/>
            <TextInput id="recaptcha_secret" v-model="form.recaptcha_secret" class="block w-full"/>
            <InputError :message="form.errors?.recaptcha_secret" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.recaptcha_enabled">
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Recaptcha site key')" for="recaptcha_sitekey"/>
            <TextInput id="recaptcha_sitekey" v-model="form.recaptcha_sitekey" class="block w-full"/>
            <InputError :message="form.errors?.recaptcha_sitekey" class="mt-1"/>
          </div>
        </div>

        <div v-if="form.recaptcha_enabled">
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Recaptcha theme')" for="recaptcha_theme"/>
            <SelectInput v-model="form.recaptcha_theme" :values="['light', 'dark']"/>
            <InputError :message="form.errors?.recaptcha_theme" class="mt-1"/>
          </div>
        </div>
      </div>

    </Card>

  </AdminLayout>
</template>

<style scoped></style>
