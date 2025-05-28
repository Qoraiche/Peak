<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {CheckIcon, ChevronUpDownIcon, PlusCircleIcon, TrashIcon} from "@heroicons/vue/24/outline/index.js";
import Input from "@peak/Components/Admin/Input.vue";
import {inject, ref, watch} from "vue";
import {useForm} from '@inertiajs/vue3';
import {
  Listbox,
  ListboxButton,
  ListboxOption,
  ListboxOptions,
  Switch,
  SwitchDescription,
  SwitchGroup
} from "@headlessui/vue";
import Card from "@peak/Components/Admin/Card.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import TextArea from "@peak/Components/Admin/TextArea.vue";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import SelectInput from "@peak/Components/Admin/SelectInput.vue";
import {useToast} from "vue-toastification";
import AlertInfo from "@peak/Components/Admin/Alerts/AlertInfo.vue";
import {__} from "@peak/Composables/useTranslate.js";

const props = defineProps({
  roles: Object | null
});

const emitter = inject('emitter');

const defaultPricingTier = {
  interval: 'monthly',
  price: 0,
  stripe_id: null,
  paddle_id: null
};

const planPricingTiers = ref([defaultPricingTier]);

const form = useForm({
  name: null,
  free: false,
  primary_heading: null,
  description: null,
  features: null,
  pricingTiers: planPricingTiers,
  monthlyIncentive: null,
  // free_stripe_price_id: null,
  yearlyIncentive: null,
  trial_days: 0,
  per_seat: false,
  seat_name_label: null,
  seat_name: null,
  max_seats: 0,
  // free: false,
  featured: false,
  status: 'active',
  roles: []
});

const addYearlyIncentivePromo = ref(false);
const addMonthlyIncentivePromo = ref(false);
const offerTrialDays = ref(false);
const selectedRolesLocal = ref([]);

watch(selectedRolesLocal, (newVal, oldVal) => {
  selectedRolesLocal.value = newVal;
});

watch([addYearlyIncentivePromo, addMonthlyIncentivePromo], ([addYearlyIncentive, addMonthlyIncentive]) => {
  if ((addYearlyIncentive || addMonthlyIncentive) && form.free) {
    if (addYearlyIncentive) {
      addYearlyIncentivePromo.value = !addYearlyIncentivePromo.value;
    } else {
      addMonthlyIncentivePromo.value = !addMonthlyIncentivePromo.value;
    }
    toast.warning(__('you cannot add an incentive discount on a free plan.'))
  }
});

watch(offerTrialDays, trialDays => {
  if (trialDays === true && form.trial_days < 1) {
    if (form.free) {
      offerTrialDays.value = !offerTrialDays.value;
      toast.warning(__('Free trials are only available for no free plans.'))
      return;
    }
    form.trial_days = 1;
  }
});

const errors = ref({});
const toast = useToast();
const addPricingTier = () => {
  if (planPricingTiers.value.length < 2) {
    planPricingTiers.value.push({...defaultPricingTier});
  } else {
    toast.warning(__('Ops! you cannot add more than 2 pricing tiers for each plan.'));
  }
};
const removePricingTierByIndex = index => {
  planPricingTiers.value.splice(index, 1);
}

const add = () => {
  form.pricingTiers = planPricingTiers.value;

  if (selectedRolesLocal.value.length > 0) {
    form.roles = selectedRolesLocal.value;
  }

  form.post(route('admin.finance.plans.store'), {
    preserveScroll: false,
    onError: (requestErrors) => {
      errors.value = requestErrors;

      for (let key in requestErrors) {
        if (requestErrors.hasOwnProperty(key)) {
          toast.error(requestErrors[key])
        }
      }
    },
    onSuccess: () => {
      toast.success(__('Plan created'));
      errors.value = {};
    }
  });
}

/*watch(() => form.free, (free) => {
    if (free === true) {
        form.trial_days = 0;
        planPricingTiers.value = null;
        offerTrialDays.value = false;
    } else {
        planPricingTiers.value = [defaultPricingTier];
    }
});*/

function containsPricingTiers(obj) {
  for (let key in obj) {
    if (key.includes('pricingTiers')) {
      return true;
    }
    if (typeof obj[key] === 'object' && !Array.isArray(obj[key])) {
      if (containsPricingTiers(obj[key])) {
        return true;
      }
    }
  }
  return false;
}

function containsFormNameErrors(obj) {
  for (let key in obj) {
    if (key.includes('name')) {
      return true;
    }
    if (typeof obj[key] === 'object' && !Array.isArray(obj[key])) {
      if (containsFormNameErrors(obj[key])) {
        return true;
      }
    }
  }
  return false;
}
</script>

<template>
  <AdminLayout :description="__('Create a new plan')" :page-icon="false" :title="__('New Pricing Plan')">

    <template v-slot:actions>
      <PrimaryButton :class="{'opacity-75': form.processing || !form.isDirty}"
                     :disabled="form.processing || !form.isDirty"
                     :loading="form.processing"
                     @click="add">
        {{ __('Create') }}
      </PrimaryButton>
    </template>

    <div class="mx-auto mt-8 grid max-w-3xl grid-cols-1 gap-6 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
      <div class="space-y-6 lg:col-span-2 lg:col-start-1">
        <Card :has-error="containsFormNameErrors(errors)">
          <template #header>
            {{ __('Information') }}
          </template>

          <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Name') }}
                <span class="text-red-600">*</span>
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="name" v-model="form.name" class="w-full" placeholder="Basic"/>
                <InputError :message="form.errors?.name" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="primary_heading">
                {{ __('Primary Heading') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="primary_heading" v-model="form.primary_heading"
                       class="w-full"/>
                <InputError :message="form.errors?.primary_heading" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-3 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="description">
                {{ __('Short description') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <text-area id="description" v-model="form.description" class="w-full"></text-area>
                <InputError :message="form.errors?.description" class="mt-2"/>
              </dd>
            </div>
          </dl>
        </Card>

        <Card :has-error="containsPricingTiers(errors)">
          <template #header>
            {{ __('Pricing') }}
          </template>

          <template #description>
            {{ __('Set monthly and yearly pricing details for the plan.') }}
          </template>

          <div class="flex flex-col space-y-5">

            <AlertInfo v-show="form.free">
              Please note that a <b>free plan</b> does not support additional pricing tiers. If you wish
              to create tiers, consider selecting a different plan option.
            </AlertInfo>

            <dl v-for="(tier, index) in planPricingTiers"
                v-show="!form.free" :key="index"
                class="grid grid-cols-2 sm:grid-cols-3 gap-x-4 gap-y-8 bg-zinc-50 rounded-md border border-zinc-100 p-3">
              <div class="sm:col-span-1 space-y-3">
                <label :for="'interval-' + index"
                       class="flex gap-x-1 text-sm font-medium text-gray-600">
                  {{ __('Payment Interval') }}
                  <span class="text-red-600">*</span>
                  <button v-if="index > 0" class="flex items-center gap-x-1 text-xs hover:underline text-red-600"
                          @click="removePricingTierByIndex(index)">
                    <TrashIcon class="w-4"/>
                  </button>
                </label>
                <dd class="mt-1 text-sm text-gray-900">
                  <SelectInput :id="'interval-' + index" v-model="tier.interval"
                               :values="['monthly', 'yearly']"/>
                </dd>
              </div>

              <div class="sm:col-span-1 space-y-3">
                <label :for="'price-' + index" class="flex gap-x-1 text-sm font-medium text-gray-600">{{ __('Price') }}
                  <span class="text-red-600">*</span>
                </label>
                <dd class="mt-1 text-sm text-gray-900">
                  <Input :id="'price-' + index" v-model="tier.price" class="w-full" min="0"
                         placeholder="10"
                         type="number"/>
                </dd>
              </div>

              <div class="sm:col-span-1 space-y-3">
                <label :for="'stripe_id-' + index"
                       class="flex gap-x-1 text-sm font-medium text-gray-600">
                  {{ __('Stripe ID') }}
                </label>
                <dd class="mt-1 text-sm text-gray-900">
                  <Input :id="'stripe_id-' + index" v-model="tier.stripe_id" class="w-full" placeholder="price_"
                         type="text"/>
                </dd>
              </div>

              <div class="sm:col-span-1 space-y-3">
                <label :for="'paddle_id-' + index"
                       class="flex gap-x-1 text-sm font-medium text-gray-600">
                  {{ __('Paddle ID') }}
                </label>
                <dd class="mt-1 text-sm text-gray-900">
                  <Input :id="'paddle_id-' + index" v-model="tier.paddle_id" class="w-full" placeholder="pri_"
                         type="text"/>
                </dd>
              </div>
            </dl>
            <div class="self-center">
              <SecondaryButton v-tooltip="__('Add new pricing tier')" @click="addPricingTier">
                <PlusCircleIcon class="w-6">Add Pricing Tier</PlusCircleIcon>
              </SecondaryButton>
            </div>
          </div>
        </Card>

        <Card :has-error="errors.features">
          <template #header>
            {{ __('Features') }}
          </template>

          <template #description>
            {{ __('Enable or customize extra functionalities included with this plan.') }}
          </template>

          <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="col-span-3 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="features">
                {{ __('Features') }}
                <span
                    class="text-red-600"> {{ __('* (comma separated)') }}</span>
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <text-area id="features" v-model="form.features" :placeholder="__('feature 1, feature 2')"
                           class="w-full"/>

                <InputError :message="form.errors?.features" class="mt-2"/>
              </dd>
            </div>
          </dl>
        </Card>
      </div>

      <div class="flex flex-col space-y-4">
        <Card>
          <template #header>{{ __('Settings') }}</template>
          <template #description>{{ __('Configure plan roles, and availability options') }}</template>

          <dl class="grid grid-cols-1 gap-x-4 gap-y-3 sm:grid-cols-2">
            <div class="sm:col-span-2">
              <dt class="text-sm font-medium text-gray-600">{{ __('Status') }}</dt>
              <dd class="mt-1 text-sm text-gray-900">
                <SelectInput v-model="form.status" :values="['active', 'draft']"/>
              </dd>
            </div>

            <!--                        <div class="sm:col-span-2 pt-6">
                                        <dt class="text-sm font-medium text-gray-600">Free</dt>
                                        <dd class="mt-1 text-sm text-gray-900 flex flex-col space-y-2">
                                            <SwitchGroup as="div" class="flex items-center justify-between">
                                                <span class="flex flex-grow flex-col">
                                                  <SwitchDescription as="span" class="text-sm text-gray-600">Nulla amet tempus sit accumsan. Aliquet turpis sed sit lacinia.</SwitchDescription>
                                                </span>
                                                <Switch v-model="form.free"
                                                        :class="[form.free ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                                            <span aria-hidden="true"
                                                                  :class="[form.free ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"/>
                                                </Switch>
                                            </SwitchGroup>

                                            <div v-if="form.free" class="flex flex-col space-y-2">
                                                <Input class="w-full" placeholder="price_" v-model="form.free_stripe_price_id"
                                                       id="name" :disabled="!form.free" type="text"
                                                       :class="{'bg-gray-100': !form.free || form.processing}"/>
                                            </div>
                                        </dd>
                                    </div>-->

            <div class="sm:col-span-2 pt-6">
              <div class="flex flex-col space-y-2">
                <div>
                  <dt class="text-sm font-medium text-gray-500">{{ __('Roles') }}</dt>

                  <AlertInfo v-if="roles.length < 1" class="mt-2">
                    <p>{{ __('No roles found.') }}</p>
                  </AlertInfo>

                  <div v-else class="flex gap-x-1 items-center mt-2">
                    <div class="w-full">
                      <dd class="text-sm text-gray-900">
                        <Listbox id="role" v-model="selectedRolesLocal" as="div" multiple>
                          <div class="relative">
                            <ListboxButton
                                class="relative w-full cursor-pointer rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
                                                    <span class="block truncate capitalize">
                                                        {{ __('Select roles') }}
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

            <div class="sm:col-span-2 pt-6">
              <dt class="text-sm font-medium text-gray-600">{{ __('Offer Trial Days') }}</dt>
              <dd class="mt-1 text-sm text-gray-900 flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex gap-x-2 items-center justify-between">
                                    <span class="flex flex-grow flex-col">
                                      <SwitchDescription as="span" class="text-sm text-gray-600">
                                          {{ __('Set the number of free trial days for new users.') }}
                                      </SwitchDescription>
                                    </span>
                  <Switch v-model="offerTrialDays"
                          :class="[offerTrialDays ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                                <span
                                                    :class="[offerTrialDays ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                                    aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>

                <div v-if="offerTrialDays" class="flex flex-col space-y-2">
                  <Input id="name" v-model="form.trial_days" :class="{'bg-gray-100': form.free || form.processing}"
                         :disabled="form.free"
                         class="w-full" min="0"
                         type="number"/>
                </div>
              </dd>
            </div>

            <div class="sm:col-span-2 pt-6">
              <dt class="text-sm font-medium text-gray-600">{{ __('Per Seat Pricing') }}</dt>
              <dd class="mt-1 text-sm text-gray-900 flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex gap-x-2 items-center justify-between">
                                    <span class="flex flex-grow flex-col">
                                      <SwitchDescription as="span" class="text-sm text-gray-600">
                                          {{ __('Enable pricing based on the number of users (seats).') }}
                                      </SwitchDescription>
                                    </span>
                  <Switch v-model="form.per_seat"
                          :class="[form.per_seat ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                                <span
                                                    :class="[form.per_seat ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                                    aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>

                <InputError :message="form.errors?.per_seat" class="mt-2"/>

                <div v-if="form.per_seat" class="flex flex-col space-y-2">
                  <Label for="max_seats">{{ __('Max seats:') }}</Label>
                  <Input id="max_seats" v-model="form.max_seats" :class="{'bg-gray-100': form.free || form.processing}"
                         :disabled="form.free"
                         class="w-full" min="0"
                         type="number"/>
                </div>

                <InputError :message="form.errors?.max_seats" class="mt-2"/>

                <div v-if="form.per_seat" class="flex flex-col space-y-2">
                  <Label for="seat_name">{{ __('Seat name:') }}</Label>
                  <Input id="seat_name" v-model="form.seat_name" :class="{'bg-gray-100': form.free || form.processing}"
                         :disabled="form.free"
                         class="w-full" placeholder="ex: Project, Team"
                         type="text"/>
                </div>

                <InputError :message="form.errors?.seat_name" class="mt-2"/>

                <div v-if="form.per_seat" class="flex flex-col space-y-2">
                  <Label for="seat_name_label">{{ __('Seat Label:') }}</Label>
                  <Input id="seat_name_label" v-model="form.seat_name_label"
                         :class="{'bg-gray-100': form.free || form.processing}"
                         :disabled="form.free"
                         class="w-full" placeholder="ex: Project, Team"
                         type="text"/>
                </div>

                <InputError :message="form.errors?.seat_name_label" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-2 pt-6">
              <dt class="text-sm font-medium text-gray-600">{{ __('Monthly Incentive Discount') }}</dt>
              <dd class="mt-1 text-sm text-gray-900 flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex gap-x-2 items-center justify-between">
                                    <span class="flex flex-grow flex-col">
                                      <SwitchDescription as="span" class="text-sm text-gray-600">
                                          {{ __('Add a discount to encourage monthly subscriptions.') }}
                                      </SwitchDescription>
                                    </span>
                  <Switch v-model="addMonthlyIncentivePromo"
                          :class="[addMonthlyIncentivePromo ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                                <span
                                                    :class="[addMonthlyIncentivePromo ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                                    aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>

                <div v-if="addMonthlyIncentivePromo">
                  <Input id="name" v-model="form.monthlyIncentive" :class="{'bg-gray-100': form.processing}"
                         :disabled="form.processing" :placeholder="__('Save 20% on monthly plan')"
                         class="w-full"/>
                </div>
              </dd>
            </div>

            <div class="sm:col-span-2 pt-6">
              <dt class="text-sm font-medium text-gray-600">
                {{ __('Yearly Incentive Discount') }}
              </dt>
              <dd class="mt-1 text-sm text-gray-900 flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex gap-x-2 items-center justify-between">
                                    <span class="flex flex-grow flex-col">
                                      <SwitchDescription as="span" class="text-sm text-gray-600">
                                          {{ __('Add a discount for yearly billing commitment.') }}
                                      </SwitchDescription>
                                    </span>
                  <Switch v-model="addYearlyIncentivePromo"
                          :class="[addYearlyIncentivePromo ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                                <span
                                                    :class="[addYearlyIncentivePromo ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                                    aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>

                <div v-if="addYearlyIncentivePromo">
                  <Input id="name" v-model="form.yearlyIncentive" :class="{'bg-gray-100': form.processing}"
                         :disabled="form.processing" :placeholder="__('Save 20% on yearly plan')"
                         class="w-full"/>
                </div>
              </dd>
            </div>

            <div class="sm:col-span-2 pt-6">
              <dt class="text-sm font-medium text-gray-600">{{ __('Featured') }}</dt>
              <dd class="mt-1 text-sm text-gray-900">
                <SwitchGroup as="div" class="flex items-center justify-between">
                                    <span class="flex flex-grow flex-col">
                                      <SwitchDescription as="span" class="text-sm text-gray-600">
                                          {{ __('Highlight this plan in listings or promotions.') }}
                                      </SwitchDescription>
                                    </span>
                  <Switch v-model="form.featured"
                          :class="[form.featured ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                                <span
                                                    :class="[form.featured ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                                    aria-hidden="true"/>
                  </Switch>
                </SwitchGroup>
              </dd>
            </div>
          </dl>

        </Card>

      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
