<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import countries from '@peak/Utils/countries.js';
import {Link, router, useForm, usePage} from '@inertiajs/vue3';
import StatsWidget from "@peak/Components/Admin/StatsWidget.vue";
import {CheckCircle, Clock2, MousePointerClickIcon, Users, View, WandSparkles} from "lucide-vue-next";
import {CheckIcon, ChevronUpDownIcon} from "@heroicons/vue/24/outline/index.js";
import Input from "@peak/Components/Admin/Input.vue";
import {inject, ref, watch} from "vue";
import AlertInfo from "@peak/Components/Admin/Alerts/AlertInfo.vue";

import {
  Listbox,
  ListboxButton,
  ListboxOption,
  ListboxOptions,
  Switch,
  SwitchDescription,
  SwitchGroup
} from "@headlessui/vue";

import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import DangerButton from "@peak/Components/Admin/DangerButton.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import {useToast} from "vue-toastification";
import UserAdminNotes from "@peak/Pages/Admin/Users/Sections/UserAdminNotes.vue";
import Card from "@peak/Components/Admin/Card.vue";
import ChangeProfileAvatar from "@peak/Layouts/Admin/Components/ChangeProfileAvatar.vue";
import Plan from "@peak/Layouts/Admin/Components/Plan.vue";
import {useLoading} from "vue-loading-overlay";
import UserActivity from "@peak/Layouts/Admin/Components/UserActivity.vue";
import {useConfirm} from "@peak/Composables/useConfirm.js";
import {__} from "@peak/Composables/useTranslate.js";

const toast = useToast();

const props = defineProps({
  user: Object,
  seatName: String | null,
  notes: Object | null,
  roles: Array | null,
  selectedRoles: Array,
  timezones: Object | null
});

const selectedRolesLocal = ref(props.selectedRoles);
const emitter = inject('emitter');

watch(selectedRolesLocal, (newVal, oldVal) => {
  selectedRolesLocal.value = newVal;
});

const form = useForm({
  name: props.user.name,
  username: props.user.username,
  email: props.user.email,
  address: props.user.address,
  city: props.user.city,
  postal_code: props.user.postal_code,
  verified_email: props.user.email_verified_at !== null,
  state: props.user.state,
  country: props.user.country,
  gender: props.user.gender,
  roles: [],
  timezone: props.user.timezone,
  mobile_number: props.user.mobile_number,
  has_enabled_two_factor_authentication: props.user.has_enabled_two_factor_authentication
});

const errors = ref({});

const submit = () => {

  if (selectedRolesLocal.value.length > 0) {
    form.roles = selectedRolesLocal.value;
  }

  form.put(route('admin.user-management.users.update', {
    user: props.user.id
  }), {
    preserveScroll: true,
    onSuccess: () => {
      errors.value = {};
      open.value = false;
      toast.success("User Updated");
    },

    onError: (err) => {
      for (let key in err) {

        console.log(key);

        if (err.hasOwnProperty(key)) {
          toast.error(key + ' - ' + err[key]);
        }
      }

      errors.value = err;
    }
  })
}

const phoneValid = ref(false);

const validatePhone = (status) => {
  phoneValid.value = status.valid;
}

const editUserPassword = () => {
  emitter.emit('user:update-password', {
    userId: props.user.id
  });
}

const genders = ref({
  'male': __('Male'),
  'female': __('Female'),
  'other': __('Other'),
  'prefer-not-to-say': __('Prefer not to say'),
});

const $loading = useLoading({
  active: true,
  color: 'blue'
});

const page = usePage();

</script>

<template>

  <AdminLayout :description="__('Edit :resourcename #:id', {resourcename: 'User', id: user.id})" :page-icon="false"
               :title="__('Edit User')">

    <template #actions>
      <PrimaryButton :class="{'opacity-25': form.processing}" :disabled="form.processing" :loading="form.processing"
                     class="items-center w-full md:w-auto justify-center"
                     type="button"
                     @click="submit">
        {{ __('Save Changes') }}
      </PrimaryButton>
    </template>

    <div class="mx-auto max-w-3xl md:flex md:items-center md:justify-between md:gap-x-5 lg:max-w-7xl">
      <div class="flex items-center gap-x-5">

        <ChangeProfileAvatar :user="user"/>

        <div class="flex flex-col space-y-1">
          <h1 class="text-xl md:text-2xl font-bold text-gray-900">{{ user.name }}</h1>
          <p class="text-sm font-medium text-gray-500">
            {{ __('Joined :date', {date: user.created_at}) }}
          </p>
        </div>
      </div>
    </div>

    <div class="mx-auto mt-8 grid max-w-3xl grid-cols-1 gap-6 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
      <div class="space-y-6 lg:col-span-2 lg:col-start-1">
        <Card :has-error="Object.keys(form.errors).length !== 0">
          <template #header>
            {{ __('Information') }}
          </template>

          <template #description>
            {{ __('User details') }}
          </template>

          <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="name">{{ __('Name') }} <span
                  class="text-red-600">*</span>
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="name" v-model="form.name" class="w-full"/>
                <InputError :message="form.errors?.name" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="email">{{ __('Email') }} <span
                  class="text-red-600">*</span></label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="email" v-model="form.email" class="w-full"/>
                <InputError :message="form.errors?.email" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label id="mobile_number" class="text-sm font-medium text-gray-500">{{ __('Phone number') }} </label>
              <dd class="mt-1 text-sm text-gray-900">
                <vue-tel-input id="mobile_number" v-model="form.mobile_number"
                               :validCharactersOnly="true"
                               mode="international"
                               required @validate="validatePhone"/>

                <InputError v-if="!phoneValid" :message="form.errors?.mobile_number" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="gender">
                {{ __('Gender') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Listbox id="gender" v-model="form.gender" as="div">
                  <div class="relative mt-2">
                    <ListboxButton
                        class="relative w-full cursor-pointer rounded-md bg-white py-2 pl-3 pr-10 text-left text-gray-900 ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">

                                            <span class="block truncate capitalize">
                                                {{ genders[form.gender] ?? __('Select Gender') }}
                                            </span>

                      <span
                          class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
          <ChevronUpDownIcon aria-hidden="true" class="h-5 w-5 text-gray-400"/>
        </span>
                    </ListboxButton>

                    <transition leave-active-class="transition ease-in duration-100"
                                leave-from-class="opacity-100" leave-to-class="opacity-0">
                      <ListboxOptions
                          class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-opacity-5 focus:outline-none sm:text-sm">
                        <ListboxOption v-for="(gender, key) in genders"
                                       :key="key"
                                       v-slot="{ active, selected }" :value="key"
                                       as="template">
                          <li :class="[active ? 'bg-blue-600 text-white' : 'text-gray-900', 'relative cursor-default select-none py-2 pl-8 pr-4']">
                                                                <span
                                                                    :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">{{
                                                                    gender
                                                                  }}</span>

                            <span v-if="selected"
                                  :class="[active ? 'text-white' : 'text-blue-600', 'absolute inset-y-0 left-0 flex items-center pl-1.5']">
                <CheckIcon aria-hidden="true" class="h-5 w-5"/>
              </span>
                          </li>
                        </ListboxOption>
                      </ListboxOptions>
                    </transition>
                  </div>
                </Listbox>

                <InputError :message="form.errors?.gender" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="timezone">
                {{ __('Timezone') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Listbox id="timezone" v-model="form.timezone" as="div">
                  <div class="relative">
                    <ListboxButton
                        class="relative w-full cursor-pointer rounded-md bg-white py-2 pl-3 pr-10 text-left text-gray-900 ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
                                            <span class="block truncate capitalize">
                                                {{ form.timezone ?? __('Select Timezone') }}
                                            </span>

                      <span
                          class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                                <ChevronUpDownIcon aria-hidden="true" class="h-5 w-5 text-gray-400"/>
                                            </span>
                    </ListboxButton>

                    <transition leave-active-class="transition ease-in duration-100"
                                leave-from-class="opacity-100" leave-to-class="opacity-0">
                      <ListboxOptions
                          class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-opacity-5 focus:outline-none sm:text-sm">
                        <ListboxOption v-for="timezone in timezones"
                                       :key="timezone"
                                       v-slot="{ active, selected }" :value="timezone"
                                       as="template">
                          <li :class="[active ? 'bg-blue-600 text-white' : 'text-gray-900', 'relative cursor-default select-none py-2 pl-8 pr-4']">
                                                                <span
                                                                    :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">{{
                                                                    timezone
                                                                  }}</span>

                            <span v-if="selected"
                                  :class="[active ? 'text-white' : 'text-blue-600', 'absolute inset-y-0 left-0 flex items-center pl-1.5']">
                <CheckIcon aria-hidden="true" class="h-5 w-5"/>
              </span>
                          </li>
                        </ListboxOption>
                      </ListboxOptions>
                    </transition>
                  </div>
                </Listbox>

                <InputError :message="form.errors?.timezone" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="username">{{ __('Username') }} <span
                  class="text-red-600">*</span></label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="username" v-model="form.username" class="w-full"/>
                <InputError :message="form.errors?.username" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-2">
              <label class="text-sm font-medium text-gray-500" for="address">
                {{ __('Address') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="address" v-model="form.address" class="w-full"/>
                <InputError :message="form.errors?.address" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="country">
                {{ __('Country') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Listbox id="country" v-model="form.country" as="div">
                  <div class="relative">
                    <ListboxButton
                        class="relative w-full cursor-pointer rounded-md bg-white py-2 pl-3 pr-10 text-left text-gray-900 ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
                                            <span class="block truncate capitalize">{{
                                                countries[form.country] ?? 'Select country'
                                              }}</span>
                      <span
                          class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
          <ChevronUpDownIcon aria-hidden="true" class="h-5 w-5 text-gray-400"/>
        </span>
                    </ListboxButton>

                    <transition leave-active-class="transition ease-in duration-100"
                                leave-from-class="opacity-100" leave-to-class="opacity-0">
                      <ListboxOptions
                          class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-opacity-5 focus:outline-none sm:text-sm">
                        <ListboxOption v-for="(country, countryCode) in countries"
                                       :key="countryCode"
                                       v-slot="{ active, selected }" :value="countryCode"
                                       as="template">
                          <li :class="[active ? 'bg-blue-600 text-white' : 'text-gray-900', 'relative cursor-default select-none py-2 pl-8 pr-4']">
                                                                <span
                                                                    :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">{{
                                                                    country
                                                                  }}</span>

                            <span v-if="selected"
                                  :class="[active ? 'text-white' : 'text-blue-600', 'absolute inset-y-0 left-0 flex items-center pl-1.5']">
                                                            <CheckIcon aria-hidden="true" class="h-5 w-5"/>
                                                          </span>
                          </li>
                        </ListboxOption>
                      </ListboxOptions>
                    </transition>
                  </div>
                </Listbox>

                <InputError :message="form.errors?.country" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="city">
                {{ __('City') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="city" v-model="form.city" class="w-full"/>
                <InputError :message="form.errors?.city" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="state">
                {{ __('State') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="state" v-model="form.state" class="w-full"/>
                <InputError :message="form.errors?.state" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-500"
                     for="postal_code">{{ __('Zip/Postal') }}</label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="postal_code" v-model="form.postal_code" class="w-full"/>
                <InputError :message="form.errors?.postal_code" class="mt-2"/>
              </dd>
            </div>

          </dl>
        </Card>

        <!-- Comments-->
        <UserAdminNotes :notes="notes" :userId="user.id"/>

      </div>

      <div class="flex flex-col space-y-4">
        <Card :bordered="true">
          <template #header>Settings</template>
          <template #description>{{ __('Manage security and account preferences.') }}</template>
          <dl class="grid grid-cols-1 gap-x-4 gap-8 sm:grid-cols-2">
            <div class="sm:col-span-2">
              <div class="flex flex-col space-y-2">
                <div>
                  <dt class="text-sm font-medium text-gray-500">{{ __('Roles') }}</dt>

                  <AlertInfo v-if="roles.length < 1" class="mt-2">
                    <p>
                      {{ __('No roles found.') }}
                    </p>
                  </AlertInfo>

                  <div v-else class="flex gap-x-1 items-center mt-2">
                    <div class="w-full">
                      <dd class="text-sm text-gray-900">
                        <Listbox id="role" v-model="selectedRolesLocal" as="div" multiple>
                          <div class="relative">
                            <ListboxButton
                                class="relative w-full cursor-pointer rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
                                                    <span class="block truncate capitalize">
                                                        {{ __('Select user roles') }}
                                                    </span>
                              <span
                                  class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
          <ChevronUpDownIcon aria-hidden="true" class="h-5 w-5 text-gray-400"/>
        </span>
                            </ListboxButton>

                            <transition leave-active-class="transition ease-in duration-100"
                                        leave-from-class="opacity-100"
                                        leave-to-class="opacity-0">
                              <ListboxOptions
                                  class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-opacity-5 focus:outline-none sm:text-sm">
                                <ListboxOption v-for="role in roles"
                                               :key="role"
                                               v-slot="{ active, selected }" :value="role"
                                               as="template">
                                  <li :class="[active ? 'bg-blue-600 text-white' : 'text-gray-900', 'relative cursor-default select-none py-2 pl-8 pr-4']">
                                                                <span
                                                                    :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">
                                                                    {{ role }}
                                                                </span>

                                    <span v-if="selected"
                                          :class="[active ? 'text-white' : 'text-blue-600', 'absolute inset-y-0 left-0 flex items-center pl-1.5']">
                <CheckIcon aria-hidden="true" class="h-5 w-5"/>
              </span>
                                  </li>
                                </ListboxOption>
                              </ListboxOptions>
                            </transition>
                          </div>
                        </Listbox>
                      </dd>
                    </div>

                    <!--                                        <div>
                                                                <SecondaryButton @click="addActiveSelectedRole">Add</SecondaryButton>
                                                            </div>-->
                  </div>
                </div>

                <div class="flex gap-1.5 items-start flex-wrap justify-start cursor-default">
                                    <span v-for="role in selectedRolesLocal" :key="role"
                                          class="gap-x-0.5 rounded-md bg-gray-100 px-2 py-1 text-sm font-medium text-gray-600">
                                        {{ role }}
                                      </span>
                </div>
              </div>
            </div>
            <div class="sm:col-span-2">
              <dt class="text-sm font-medium text-gray-500">{{ __('Two Factor Authentication') }}</dt>
              <dd class="mt-1 text-sm text-gray-900">
                <SwitchGroup as="div" class="flex items-center justify-between">
    <span class="flex flex-grow flex-col">

      <SwitchDescription as="span" class="text-sm text-gray-500">
        {{ __('Manage 2FA status for extra security.') }}
      </SwitchDescription>
    </span>
                  <Switch v-model="form.has_enabled_two_factor_authentication"
                          :class="[form.has_enabled_two_factor_authentication ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                                <span
                                                    :class="[form.has_enabled_two_factor_authentication ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                                    aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>
              </dd>
            </div>
            <div class="sm:col-span-2">
              <dt class="text-sm font-medium text-gray-500">{{ __('Email Verification') }}</dt>
              <dd class="mt-1 text-sm text-gray-900">
                <SwitchGroup as="div" class="flex items-center justify-between">
    <span class="flex flex-grow flex-col">

      <SwitchDescription as="span" class="text-sm text-gray-500">
      {{ __('Check or update email verification status.') }}
      </SwitchDescription>
    </span>
                  <Switch v-model="form.verified_email"
                          :class="[form.verified_email ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                                <span
                                                    :class="[form.verified_email ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                                    aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>
              </dd>
            </div>
            <div class="sm:col-span-2">
              <dt class="text-sm font-medium text-gray-500">{{ __('Password') }}</dt>
              <dd class="mt-1 text-sm text-gray-900">
                <div class="flex flex-col items-start space-y-3 justify-between">
    <span class="flex flex-grow flex-col">
      <span class="text-sm text-gray-500">
        {{ __("Reset or change the user's password.") }}
      </span>
    </span>
                  <button class="text-blue-600 hover:underline" @click="editUserPassword">
                    {{ __('Edit password') }}
                  </button>
                </div>
              </dd>
            </div>
            <!--
                                    <div class="sm:col-span-2 pt-6">
                                        <dt class="text-sm font-medium text-gray-500">Notification History</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <div class="flex flex-col items-start space-y-3 justify-between">
                <span class="flex flex-grow flex-col">
                  <span class="text-sm text-gray-500">Nulla amet tempus sit accumsan. Aliquet turpis sed sit lacinia.</span>
                </span>
                                                <button href="#" class="text-blue-600 hover:underline"
                                                        @click="viewNotificationsHistory">
                                                    View notifications history
                                                </button>
                                            </div>
                                        </dd>
                                    </div>

                                    <div class="sm:col-span-2 pt-6">
                                        <dt class="text-sm font-medium text-gray-500">Emails History</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <div class="flex flex-col items-start space-y-3 justify-between">
                <span class="flex flex-grow flex-col">
                  <span class="text-sm text-gray-500">Nulla amet tempus sit accumsan. Aliquet turpis sed sit lacinia.</span>
                </span>
                                                <button href="#" class="text-blue-600 hover:underline"
                                                        @click="viewEmailsHistory">
                                                    View emails history
                                                </button>
                                            </div>
                                        </dd>
                                    </div>

                                    <div class="sm:col-span-2 pt-6">
                                        <dt class="text-sm font-medium text-gray-500">Logins History</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <div class="flex flex-col items-start space-y-3 justify-between">
                <span class="flex flex-grow flex-col">
                  <span class="text-sm text-gray-500">Nulla amet tempus sit accumsan. Aliquet turpis sed sit lacinia.</span>
                </span>
                                                <button href="#" class="text-blue-600 hover:underline"
                                                        @click="viewLoginsHistory">
                                                    View logins history
                                                </button>
                                            </div>
                                        </dd>
                                    </div>

                                    <div class="sm:col-span-2 pt-6">
                                        <dt class="text-sm font-medium text-gray-500">Browser sessions</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <div class="flex flex-col items-start space-y-3 justify-between">
                <span class="flex flex-grow flex-col">
                  <span class="text-sm text-gray-500">
                      Manage and log out your active sessions on other browsers and devices.
                  </span>
                </span>
                                                <button type="button" class="text-blue-600 hover:underline"
                                                        @click="viewBrowserSessions">
                                                    View browser sessions
                                                </button>
                                            </div>
                                        </dd>
                                    </div>

                                    <div class="sm:col-span-2 pt-6">
                                        <dt class="text-sm font-medium text-gray-500">Activity history</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            <div class="flex flex-col items-start space-y-3 justify-between">
                <span class="flex flex-grow flex-col">
                  <span class="text-sm text-gray-500">
                      Manage and log out your active sessions on other browsers and devices.
                  </span>
                </span>
                                                <button type="button" class="text-blue-600 hover:underline"
                                                        @click="viewActivitiesHistory">
                                                    View activities history
                                                </button>
                                            </div>
                                        </dd>
                                    </div>-->
          </dl>
        </Card>

        <!--                <Card :bordered="false">
                            <template #header>Ban User</template>
                            <span class="text-sm text-gray-500">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</span>
                            <div class="mt-6 flex flex-col justify-stretch">
                                <WarningButton type="button">
                                    Ban User
                                </WarningButton>
                            </div>
                        </Card>-->

        <!--                <Card :bordered="false">
                            <template #header>Delete User</template>
                            <span class="text-sm text-gray-500">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</span>
                            <div class="mt-6 flex flex-col justify-stretch">
                                <DangerButton type="button" @click="deleteUser">
                                    Delete User
                                </DangerButton>
                            </div>
                        </Card>-->
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
