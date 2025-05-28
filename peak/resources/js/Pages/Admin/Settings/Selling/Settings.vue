<script setup>

import InputError from "@peak/Components/Admin/InputError.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {Switch, SwitchDescription, SwitchGroup, SwitchLabel} from "@headlessui/vue";
import {useForm} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import Card from "@peak/Components/Admin/Card.vue";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');
const toast = useToast();

const props = defineProps({
  proration_enabled: Boolean | null,
  promotion_codes_enabled: Boolean | null,
  sends_invoices_to_custom_addresses: Boolean | null,
  collect_vat_enabled: Boolean | null,
  cancel_subscription_immediately: Boolean | null,
  automatically_calculate_taxes: Boolean | null,
  skip_trial_if_subscribed_before_enabled: Boolean | null,
  billing_address_enabled: Boolean | null,
  generic_trial_without_payment_method_enabled: String | null,
  limit_user_trials_enabled: Boolean | null,
  first_reminder_enabled: Boolean | null,
  second_reminder_enabled: Boolean | null,
  maximum_trial_count: Number | null,
  trial_end_first_reminder_days: Number | null,
  trial_end_second_reminder_days: Number | null
});

const form = useForm({
  proration_enabled: props.proration_enabled,
  promotion_codes_enabled: props.promotion_codes_enabled,
  sends_invoices_to_custom_addresses: props.sends_invoices_to_custom_addresses,
  collect_vat_enabled: props.collect_vat_enabled,
  cancel_subscription_immediately: props.cancel_subscription_immediately,
  automatically_calculate_taxes: props.automatically_calculate_taxes,
  billing_address_enabled: props.billing_address_enabled,
  skip_trial_if_subscribed_before_enabled: props.skip_trial_if_subscribed_before_enabled,
  generic_trial_without_payment_method_enabled: props.generic_trial_without_payment_method_enabled,
  limit_user_trials_enabled: props.limit_user_trials_enabled,
  first_reminder_enabled: props.first_reminder_enabled,
  second_reminder_enabled: props.second_reminder_enabled,
  maximum_trial_count: props.maximum_trial_count,
  trial_end_first_reminder_days: props.trial_end_first_reminder_days,
  trial_end_second_reminder_days: props.trial_end_second_reminder_days
});

const save = () => {
  form.put(route('admin.settings.selling.settings.update'), {
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
  <AdminLayout :description="__('Manage billing behavior')" :title="__('Selling Settings')">

    <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
      <template #header>
        {{ __('Edit Settings') }}
      </template>

      <template #actions>
        <PrimaryButton :class="{ 'opacity-25': !form.isDirty || form.processing }"
                       :disabled="!form.isDirty || form.processing" :loading="form.processing"
                       class="self-end"
                       @click="save">
          {{ __('Save Changes') }}
        </PrimaryButton>
      </template>

      <div id="business-information" class="rounded-md">
        <div class="flex flex-col">
          <div>
            <div class="grid grid-cols-1 gap-5 py-5 md:grid-cols-2">
              <div class="flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex items-center justify-between">
                                    <span class="flex flex-col grow">
                                        <SwitchLabel as="span" class="font-medium text-gray-900 text-sm/6" passive>
                                            {{ __('Proration Enabled') }}
                                        </SwitchLabel>
                                        <SwitchDescription as="span" class="text-sm text-gray-500">
                                          {{
                                            __('Adjust charges automatically when a subscription is upgraded or downgraded mid-cycle.')
                                          }}
                                        </SwitchDescription>
                                    </span>
                  <Switch v-model="form.proration_enabled"
                          :class="[form.proration_enabled ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                        <span
                                            :class="[form.proration_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                            aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>

                <InputError :message="form.errors?.proration_enabled" class="mt-1"/>
              </div>

              <div class="flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex items-center justify-between">
                                    <span class="flex flex-col grow">
                                        <SwitchLabel as="span" class="font-medium text-gray-900 text-sm/6" passive>
                                            {{ __('Promotion Codes Enabled') }}</SwitchLabel>
                                        <SwitchDescription as="span" class="text-sm text-gray-500">
                                          {{
                                            __('Allow customers to apply promotion or discount codes during checkout.')
                                          }}
                                        </SwitchDescription>
                                    </span>
                  <Switch v-model="form.promotion_codes_enabled"
                          :class="[form.promotion_codes_enabled ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                        <span
                                            :class="[form.promotion_codes_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                            aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>

                <InputError :message="form.errors?.promotion_codes_enabled" class="mt-1"/>
              </div>

              <div class="flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex items-center justify-between">
                                    <span class="flex flex-col grow">
                                        <SwitchLabel as="span" class="font-medium text-gray-900 text-sm/6" passive>Send
                                            {{ __('Invoices to custom Addresses') }}
                                        </SwitchLabel>
                                        <SwitchDescription as="span" class="text-sm text-gray-500">
                                          {{
                                            __("Send invoices to a custom email address instead of the customer's default email.")
                                          }}
                                        </SwitchDescription>
                                    </span>
                  <Switch v-model="form.sends_invoices_to_custom_addresses"
                          :class="[form.sends_invoices_to_custom_addresses ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                        <span
                                            :class="[form.sends_invoices_to_custom_addresses ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                            aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>

                <InputError :message="form.errors?.sends_invoices_to_custom_addresses" class="mt-1"/>
              </div>

              <div class="flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex items-center justify-between">
                                    <span class="flex flex-col grow">
                                        <SwitchLabel as="span" class="font-medium text-gray-900 text-sm/6" passive>
                                            {{ __('Collect VAT Enabled') }}</SwitchLabel>
                                        <SwitchDescription as="span" class="text-sm text-gray-500">Nulla amet tempus sit
                                          {{
                                            __('Collect VAT (Value Added Tax) during checkout, based on the customer’s location.')
                                          }}
                                        </SwitchDescription>
                                    </span>
                  <Switch v-model="form.collect_vat_enabled"
                          :class="[form.collect_vat_enabled ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                        <span
                                            :class="[form.collect_vat_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                            aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>

                <InputError :message="form.errors?.collect_vat_enabled" class="mt-1"/>
              </div>

              <div class="flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex items-center justify-between">
                                    <span class="flex flex-col grow">
                                        <SwitchLabel as="span" class="font-medium text-gray-900 text-sm/6" passive>
                                            {{ __('Cancel Subscription Immediately') }}</SwitchLabel>
                                        <SwitchDescription as="span" class="text-sm text-gray-500">
                                            {{
                                            __('Terminate subscriptions right away instead of at the end of the billing period.')
                                          }}
                                        </SwitchDescription>
                                    </span>
                  <Switch v-model="form.cancel_subscription_immediately"
                          :class="[form.cancel_subscription_immediately ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                        <span
                                            :class="[form.cancel_subscription_immediately ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                            aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>

                <InputError :message="form.errors?.cancel_subscription_immediately" class="mt-1"/>
              </div>

              <div class="flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex items-center justify-between">
                                    <span class="flex flex-col grow">
                                        <SwitchLabel as="span" class="font-medium text-gray-900 text-sm/6" passive>
                                            {{ __('Automatically Calculate Taxes') }}</SwitchLabel>
                                        <SwitchDescription as="span" class="text-sm text-gray-500">
                                          {{
                                            __('Enable automatic tax calculation based on customer location and product tax rules.')
                                          }}
                                        </SwitchDescription>
                                    </span>
                  <Switch v-model="form.automatically_calculate_taxes"
                          :class="[form.automatically_calculate_taxes ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                        <span
                                            :class="[form.automatically_calculate_taxes ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                            aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>

                <InputError :message="form.errors?.automatically_calculate_taxes" class="mt-1"/>
              </div>

              <div class="flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex items-center justify-between">
                                    <span class="flex flex-col grow">
                                        <SwitchLabel as="span" class="font-medium text-gray-900 text-sm/6" passive>
                                            {{ __('Billing Address Enabled') }}</SwitchLabel>
                                        <SwitchDescription as="span" class="text-sm text-gray-500">
                                          {{ __('Collect the customer’s billing address during checkout.') }}
                                        </SwitchDescription>
                                    </span>
                  <Switch v-model="form.billing_address_enabled"
                          :class="[form.billing_address_enabled ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                        <span
                                            :class="[form.billing_address_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                            aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>

                <InputError :message="form.errors?.billing_address_enabled" class="mt-1"/>
              </div>

              <div class="flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex items-center justify-between">
                                    <span class="flex flex-col grow">
                                        <SwitchLabel as="span" class="font-medium text-gray-900 text-sm/6" passive>
                                          {{ __('Skip Trial If Subscribed Before') }}</SwitchLabel>
                                        <SwitchDescription as="span" class="text-sm text-gray-500">
                                          {{
                                            __('Automatically skip the trial period if the customer has subscribed in the past.')
                                          }}
                                        </SwitchDescription>
                                    </span>
                  <Switch v-model="form.skip_trial_if_subscribed_before_enabled"
                          :class="[form.skip_trial_if_subscribed_before_enabled ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                        <span
                                            :class="[form.skip_trial_if_subscribed_before_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                            aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>

                <InputError :message="form.errors?.skip_trial_if_subscribed_before_enabled"
                            class="mt-1"/>
              </div>

              <div class="flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex items-center justify-between">
                                    <span class="flex flex-col grow">
                                        <SwitchLabel as="span" class="font-medium text-gray-900 text-sm/6" passive>
                                          {{ __('Trial Without Payment Enabled') }}
                                        </SwitchLabel>
                                        <SwitchDescription as="span" class="text-sm text-gray-500">
                                          {{
                                            __('Allow users to start a free trial without providing payment information.')
                                          }}
                                        </SwitchDescription>
                                    </span>
                  <Switch v-model="form.generic_trial_without_payment_method_enabled"
                          :class="[form.generic_trial_without_payment_method_enabled ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                        <span
                                            :class="[form.generic_trial_without_payment_method_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                            aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>

                <InputError :message="form.errors?.generic_trial_without_payment_method_enabled"
                            class="mt-1"/>
              </div>

              <!--                            <div class="flex flex-col space-y-2">
                                              <SwitchGroup as="div" class="flex items-center justify-between">
                                                      <span class="flex flex-col grow">
                                                        <SwitchLabel as="span" class="font-medium text-gray-900 text-sm/6"
                                                                     passive>Limit User Trials Enabled</SwitchLabel>
                                                        <SwitchDescription as="span" class="text-sm text-gray-500">Nulla amet tempus sit accumsan. Aliquet turpis sed sit lacinia.</SwitchDescription>
                                                      </span>
                                                  <Switch v-model="form.limit_user_trials_enabled"
                                                          :class="[form.limit_user_trials_enabled ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                          <span aria-hidden="true"
                                                :class="[form.limit_user_trials_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"/>
                                                  </Switch>
                                              </SwitchGroup>

                                              <InputError class="mt-1" :message="form.errors?.limit_user_trials_enabled"/>
                                          </div>-->

              <!--                            <div class="flex flex-col space-y-2">
                                              <SwitchGroup as="div" class="flex items-center justify-between">
                                                      <span class="flex flex-col grow">
                                                        <SwitchLabel as="span" class="font-medium text-gray-900 text-sm/6"
                                                                     passive>Trial First Reminder Enabled</SwitchLabel>
                                                        <SwitchDescription as="span" class="text-sm text-gray-500">Nulla amet tempus sit accumsan. Aliquet turpis sed sit lacinia.</SwitchDescription>
                                                      </span>
                                                  <Switch v-model="form.first_reminder_enabled"
                                                          :class="[form.first_reminder_enabled ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                          <span aria-hidden="true"
                                                :class="[form.first_reminder_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"/>
                                                  </Switch>
                                              </SwitchGroup>

                                              <InputError class="mt-1" :message="form.errors?.first_reminder_enabled"/>
                                          </div>

                                          <div class="flex flex-col space-y-2">
                                              <SwitchGroup as="div" class="flex items-center justify-between">
                                                      <span class="flex flex-col grow">
                                                        <SwitchLabel as="span" class="font-medium text-gray-900 text-sm/6"
                                                                     passive>Trial Second Reminder Enabled</SwitchLabel>
                                                        <SwitchDescription as="span" class="text-sm text-gray-500">Nulla amet tempus sit accumsan. Aliquet turpis sed sit lacinia.</SwitchDescription>
                                                      </span>
                                                  <Switch v-model="form.second_reminder_enabled"
                                                          :class="[form.second_reminder_enabled ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                          <span aria-hidden="true"
                                                :class="[form.second_reminder_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"/>
                                                  </Switch>
                                              </SwitchGroup>

                                              <InputError class="mt-1" :message="form.errors?.second_reminder_enabled"/>
                                          </div>

                                          <div v-if="form.limit_user_trials_enabled">
                                              <div class="flex flex-col space-y-2">
                                                  <InputLabel for="max_trial_count" value="Max Trial count"/>
                                                  <TextInput id="max_trial_count" v-model="form.maximum_trial_count" min="0"
                                                             class="block w-full" type="number"/>
                                                  <InputError class="mt-1" :message="form.errors?.maximum_trial_count"/>
                                              </div>
                                          </div>

                                          <div v-if="form.first_reminder_enabled">
                                              <div class="flex flex-col space-y-2">
                                                  <InputLabel for="trial_end_first_reminder_days" value="Trial First Reminder Days"/>
                                                  <TextInput id="trial_end_first_reminder_days" type="number" min="1"
                                                             v-model="form.trial_end_first_reminder_days" class="block w-full"/>
                                                  <InputError class="mt-1" :message="form.errors?.trial_end_first_reminder_days"/>
                                              </div>
                                          </div>

                                          <div v-if="form.second_reminder_enabled">
                                              <div class="flex flex-col space-y-2">
                                                  <InputLabel for="trial_end_second_reminder_days" value="Second Reminder Days"/>
                                                  <TextInput id="trial_end_second_reminder_days" type="number" min="1"
                                                             v-model="form.trial_end_second_reminder_days" class="block w-full"/>
                                                  <InputError class="mt-1" :message="form.errors?.trial_end_second_reminder_days"/>
                                              </div>
                                          </div>-->

            </div>
          </div>
        </div>
      </div>

    </Card>

  </AdminLayout>
</template>

<style scoped></style>
