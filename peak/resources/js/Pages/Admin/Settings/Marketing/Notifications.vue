<script setup>

import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {Switch, SwitchGroup} from "@headlessui/vue";
import {useForm} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');

const props = defineProps({
  notify_user_registration: Boolean,
  notify_user_login: Boolean,
  notify_user_reset_password: Boolean,
  notify_user_app_subscription: Boolean,
  notify_user_purchase_channel_subscription: Boolean,
  notify_user_purchase_channel_gifts: Boolean,
  notify_user_channel_goes_live: Boolean
});

const form = useForm({
  notify_user_registration: props.notify_user_registration,
  notify_user_login: props.notify_user_login,
  notify_user_reset_password: props.notify_user_reset_password,
  notify_user_app_subscription: props.notify_user_app_subscription,
  notify_user_purchase_channel_subscription: props.notify_user_purchase_channel_subscription,
  notify_user_purchase_channel_gifts: props.notify_user_purchase_channel_gifts,
  notify_user_channel_goes_live: props.notify_user_channel_goes_live
});

const toast = useToast();

const save = () => {
  form.put(route('admin.settings.marketing.notifications.update'), {
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    }
  });
}

</script>

<template>
  <AdminLayout description="Manage essential settings for your application." title="Notifications">
    <div class="max-w-2xl p-2 mx-auto">
      <div class="flex flex-col p-1">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                    <span class="text-base font-semibold text-gray-600">
                        Edit Settings
                    </span>

          <PrimaryButton :class="{ 'opacity-25': !form.isDirty || form.processing }"
                         :disabled="!form.isDirty || form.processing" :loading="form.processing"
                         class="self-end"
                         @click="save">
            Save Changes
          </PrimaryButton>
        </div>

        <div>
          <p class="mt-4 font-semibold text-gray-600">Add ways to receive payments. Once you connect a payment
            processor, customers can check out in your website.</p>
          <div class="flex items-center justify-between p-4 mt-8 border border-gray-200 hover:bg-gray-50">
            <div class="font-normal">
              User Registration
            </div>

            <SwitchGroup as="div" class="flex items-center justify-between">
              <Switch v-model="form.notify_user_registration"
                      :class="[form.notify_user_registration ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.notify_user_registration ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
          </div>
          <div class="flex items-center justify-between p-4 mt-2 border border-gray-200 hover:bg-gray-50">
            <div class="font-normal">
              User Login
            </div>

            <SwitchGroup as="div" class="flex items-center justify-between">
              <Switch v-model="form.notify_user_login"
                      :class="[form.notify_user_login ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.notify_user_login ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
          </div>
          <div class="flex items-center justify-between p-4 mt-2 border border-gray-200 hover:bg-gray-50">
            <div class="font-normal">
              User Reset Password
            </div>

            <SwitchGroup as="div" class="flex items-center justify-between">
              <Switch v-model="form.notify_user_reset_password"
                      :class="[form.notify_user_reset_password ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.notify_user_reset_password ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
          </div>
          <div class="flex items-center justify-between p-4 mt-2 border border-gray-200 hover:bg-gray-50">
            <div class="font-normal">
              User Site Subscription
            </div>

            <SwitchGroup as="div" class="flex items-center justify-between">
              <Switch v-model="form.notify_user_app_subscription"
                      :class="[form.notify_user_app_subscription ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.notify_user_app_subscription ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
          </div>
          <div class="flex items-center justify-between p-4 mt-2 border border-gray-200 hover:bg-gray-50">
            <div class="font-normal">
              User Purchase Channel Subscription
            </div>

            <SwitchGroup as="div" class="flex items-center justify-between">
              <Switch v-model="form.notify_user_purchase_channel_subscription"
                      :class="[form.notify_user_purchase_channel_subscription ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.notify_user_purchase_channel_subscription ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
          </div>
          <div class="flex items-center justify-between p-4 mt-2 border border-gray-200 hover:bg-gray-50">
            <div class="font-normal">
              User Purchase Channel Gifts
            </div>

            <SwitchGroup as="div" class="flex items-center justify-between">
              <Switch v-model="form.notify_user_purchase_channel_gifts"
                      :class="[form.notify_user_purchase_channel_gifts ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.notify_user_purchase_channel_gifts ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
          </div>
          <div class="flex items-center justify-between p-4 mt-2 border border-gray-200 hover:bg-gray-50">
            <div class="font-normal">
              User Channel Goes Live
            </div>

            <SwitchGroup as="div" class="flex items-center justify-between">
              <Switch v-model="form.notify_user_channel_goes_live"
                      :class="[form.notify_user_channel_goes_live ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.notify_user_channel_goes_live ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped></style>
