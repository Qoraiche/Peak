<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import countries from '@peak/Utils/countries.js';
import {useForm} from '@inertiajs/vue3';
import {__} from "@peak/Composables/useTranslate.js";
import {CheckIcon, ChevronUpDownIcon} from "@heroicons/vue/24/outline/index.js";
import Input from "@peak/Components/Admin/Input.vue";
import {inject, ref, watch} from "vue";
import InfoAlert from "@peak/Components/Admin/Alerts/AlertInfo.vue";

import {
  Listbox,
  ListboxButton,
  ListboxOption,
  ListboxOptions,
  Switch,
  SwitchDescription,
  SwitchGroup
} from "@headlessui/vue";

import InputError from "@peak/Components/Admin/InputError.vue";
import {useToast} from "vue-toastification";
import Card from "@peak/Components/Admin/Card.vue";

const toast = useToast();

const props = defineProps({
  roles: Array,
  timezones: Object
});

const selectedRolesLocal = ref(props.selectedRoles);

const emitter = inject('emitter');

watch(selectedRolesLocal, (newVal, oldVal) => {
  selectedRolesLocal.value = newVal;
})

const form = useForm({
  name: '',
  username: '',
  email: '',
  address: '',
  city: '',
  password: '',
  password_confirmation: '',
  postal_code: '',
  verified_email: true,
  state: '',
  country: null,
  gender: null,
  roles: [],
  timezone: null,
  mobile_number: ''
});

const errors = ref({});

const submit = () => {

  if (selectedRolesLocal.value?.length > 0) {
    form.roles = selectedRolesLocal.value;
  }

  form.post(route('admin.user-management.users.store'), {
    preserveScroll: true,
    onSuccess: () => {
      errors.value = {};
      toast.success(__("User created"));
    },

    onError: (err) => {
      for (let key in err) {

        console.log(key);

        if (err.hasOwnProperty(key)) {
          toast.error(err[key])
        }
      }

      errors.value = err;
    },
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}

const phoneValid = ref(false);

const validatePhone = (status) => {
  phoneValid.value = status.valid;
}

const genders = ref({
  'male': __('Male'),
  'female': __('Female'),
  'prefer-not-to-say': __('Prefer not to say'),
});
</script>

<template>
  <AdminLayout :description="__('Add new user')" :page-icon="false" :title="__('New User')">
    <template #actions>
      <PrimaryButton :class="{'opacity-25': form.processing || !form.isDirty}"
                     :disabled="form.processing || !form.isDirty" :loading="form.processing"
                     class="inline-flex items-center justify-center"
                     type="button"
                     @click="submit">
        {{ __('Create') }}
      </PrimaryButton>
    </template>

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
              <label class="text-sm font-medium text-gray-500" for="first_name">
                {{ __('Name') }} <span
                  class="text-red-600">*</span>
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="name" v-model="form.name" class="w-full"/>
                <InputError :message="form.errors?.name" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="email">{{ __('Email') }} <span
                  class="text-red-600">*</span>
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="email" v-model="form.email" class="w-full"/>
                <InputError :message="form.errors?.email" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="password">{{ __('Password') }} <span
                  class="text-red-600">*</span></label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="email" v-model="form.password" class="w-full" type="password"/>
                <InputError :message="form.errors?.password" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="password_confirmation">
                {{ __('Confirm Password') }}
                <span
                    class="text-red-600">*</span></label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="password_confirmation" v-model="form.password_confirmation" class="w-full"
                       type="password"/>
                <InputError :message="form.errors?.password_confirmation" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label id="mobile_number" class="text-sm font-medium text-gray-500">
                {{ __('Phone number') }}
              </label>
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
                  <div class="relative mt-2">
                    <ListboxButton
                        class="relative w-full cursor-pointer rounded-md bg-white py-2 pl-3 pr-10 text-left text-gray-900 ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
                                            <span class="block truncate capitalize">
                                                {{ form.timezone ?? 'Select Timezone' }}
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

                <InputError :message="form.errors?.country" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="username">
                {{ __('Username') }}
              </label>
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
                  <div class="relative mt-2">
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
                     for="postal_code">
                {{ __('Zip/Postal') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="postal_code" v-model="form.postal_code" class="w-full"/>
                <InputError :message="form.errors?.postal_code" class="mt-2"/>
              </dd>
            </div>

          </dl>
        </Card>

        <!--                <UserTimeline/>-->

        <!-- Comments-->
        <!--                <UserAdminNotes :notes="notes" :userId="user.id"/>-->

      </div>

      <div class="flex flex-col space-y-4">
        <Card :bordered="true">
          <template #header>Settings</template>
          <template #description>{{ __('Manage security and account preferences.') }}</template>
          <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
            <div class="sm:col-span-2">
              <div class="flex flex-col gap-y-2">
                <div>
                  <dt class="text-sm font-medium text-gray-500">{{ __('Roles') }}</dt>

                  <InfoAlert v-if="roles.length < 1" class="mt-2">
                    <p>
                      {{ __('No roles found') }}
                    </p>
                  </InfoAlert>

                  <div v-else class="flex gap-x-1 items-center mt-2">
                    <div class="w-full">
                      <dd class="text-sm text-gray-900">
                        <Listbox id="role" v-model="selectedRolesLocal" as="div" multiple>
                          <div class="relative">
                            <ListboxButton
                                class="relative w-full cursor-pointer rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
                                                    <span class="block truncate capitalize">
                                                        {{ __('Edit user roles') }}
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

                        <InputError :message="form.errors?.roles" class="mt-2"/>
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

            <div class="sm:col-span-2 pt-6">
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
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
