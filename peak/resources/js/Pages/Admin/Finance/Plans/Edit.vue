<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {CheckIcon, ChevronUpDownIcon, PlusCircleIcon, TrashIcon} from "@heroicons/vue/24/outline/index.js";
import Input from "@peak/Components/Admin/Input.vue";
import {inject, ref, watch} from "vue";
import {router, useForm} from '@inertiajs/vue3';
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
  subscriptionPlan: Object,
  selectedRoles: Object,
  roles: Object
});

const emitter = inject('emitter');

const pageTitle = __('Edit Plan');
const pageDescription = __('Modify a subscription plan');

const defaultPricingTier = {
  interval: 'monthly',
  price: 0,
  stripe_id: null,
  paddle_id: null
};

const planPricingTiers = ref(props.subscriptionPlan.subscription_plan_pricings);

const form = useForm({
  name: props.subscriptionPlan.name,
  primary_heading: props.subscriptionPlan.primary_heading,
  description: props.subscriptionPlan.description,
  pricingTiers: planPricingTiers,
  yearly_incentive: props.subscriptionPlan.yearly_incentive,
  monthly_incentive: props.subscriptionPlan.monthly_incentive,
  free_stripe_price_id: props.subscriptionPlan.free_stripe_price_id,
  type: null,
  trial_days: props.subscriptionPlan.trial_days,
  per_seat: props.subscriptionPlan.per_seat,
  seat_name_label: props.subscriptionPlan.seat_name_label,
  seat_name: props.subscriptionPlan.seat_name,
  max_seats: props.subscriptionPlan.max_seats,
  features: props.subscriptionPlan.features ? props.subscriptionPlan?.features?.join(',') : [],
  free: props.subscriptionPlan.free,
  featured: props.subscriptionPlan.featured,
  status: props.subscriptionPlan.status,
  roles: props.subscriptionPlan.roles
});

const selectedRolesLocal = ref(props.selectedRoles);

watch(selectedRolesLocal, (newVal, oldVal) => {
  selectedRolesLocal.value = newVal;
});

const addYearlyIncentivePromo = ref(props.subscriptionPlan.yearly_incentive !== null);
const addMonthlyIncentivePromo = ref(props.subscriptionPlan.monthly_incentive !== null);
const offerTrialDays = ref(props.subscriptionPlan.trial_days > 0);

watch([addYearlyIncentivePromo, addMonthlyIncentivePromo], ([yearlyIncentivePromoVal, monthlyIncentivePromo]) => {
  if (yearlyIncentivePromoVal === false) {
    form.yearly_incentive = null;
  } else {
    if (props.subscriptionPlan.yearly_incentive !== null) {
      form.yearly_incentive = props.subscriptionPlan.yearly_incentive;
    }
  }

  if (monthlyIncentivePromo === false) {
    form.monthly_incentive = null;
  } else {
    if (props.subscriptionPlan.monthly_incentive !== null) {
      form.monthly_incentive = props.subscriptionPlan.monthly_incentive;
    }
  }
});

watch(offerTrialDays, trialDays => {
  if (trialDays === true) {
    if (form.free) {
      offerTrialDays.value = !offerTrialDays.value;
      toast.warning(__('Free trials are only available for no free plans.'))
      return;
    }
    form.trial_days = props.subscriptionPlan.trial_days;
  } else {
    form.trial_days = 0;
  }
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

watch(() => form.free, (free) => {
  if (free === true) {
    form.trial_days = 0;
    planPricingTiers.value = null;
    offerTrialDays.value = false;
    addYearlyIncentivePromo.value = false;
    addMonthlyIncentivePromo.value = false;
  } else {
    if (props.subscriptionPlan.trial_days !== 0) {
      form.trial_days = props.subscriptionPlan.trial_days;
    }
    planPricingTiers.value = [defaultPricingTier];
  }
});

const errors = ref({
  info: {},
  pricing: {},
  settings: {},
  features: {},
});

const toast = useToast();

const addPricingTier = () => {
  if (planPricingTiers.value.length < 2) {
    planPricingTiers.value.push({...defaultPricingTier});
  } else {
    toast.warning(__('Oops! You can only add up to two pricing tiers per plan.'));
  }
}

const removePricingTierByIndex = index => {
  window.swal.fire({
    title: __('Are you sure you want to delete this pricing tier?'),
    text: __('This action cannot be undone.'),
    showCancelButton: true,
    confirmButtonText: __('Delete'),
    cancelButtonText: __('Cancel'),
    confirmButtonColor: 'tomato'
  }).then((result) => {
    if (result.isConfirmed) {
      planPricingTiers.value.splice(index, 1);
    }
  });
}


const save = (type) => {

  if (selectedRolesLocal.value.length > 0) {
    form.roles = selectedRolesLocal.value;
  }

  form.type = type;

  form.put(route('admin.finance.plans.update', {plan: props.subscriptionPlan.id}), {
    preserveScroll: true,
    onError: (requestErrors) => {
      errors.value[type] = requestErrors[type];

      for (let key in requestErrors[type]) {
        if (requestErrors[type].hasOwnProperty(key)) {
          toast.error(requestErrors[type][key])
        }
      }
    },
    onSuccess: () => {
      toast.success(__('Changes saved'));
      errors.value[type] = {};

      router.visit(route('admin.finance.plans.edit', {plan: props.subscriptionPlan.id}), {
        preserveScroll: true,
        showProgress: false,
      });
    }
  })
}
const savePlanDetails = () => {
  save('info');
};

const savePricingTiers = () => {
  form.pricingTiers = planPricingTiers.value;
  save('pricing');
};

const savePlanSettings = () => {
  save('settings')
};

const saveFeatures = () => {
  save('features')
};
</script>

<template>
  <AdminLayout :description="pageDescription" :page-icon="false" :title="pageTitle">
    <template v-slot:actions>
    </template>

    <div class="mx-auto mt-8 grid max-w-3xl grid-cols-1 gap-6 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
      <div class="space-y-6 lg:col-span-2 lg:col-start-1">
        <Card :has-error="Object.keys(errors.info).length !== 0">
          <template #header>
            {{ __('Information') }}
          </template>

          <template #footer>
            <PrimaryButton :class="{'opacity-75': form.processing}" :disabled="form.processing"
                           :loading="form.processing"
                           @click="savePlanDetails">
              {{ __('Save Changes') }}
            </PrimaryButton>
          </template>

          <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="name">
                {{ __('Name') }}
                <span
                    class="text-red-600">*</span>
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="name" v-model="form.name"
                       class="w-full"/>
                <InputError :message="form.errors?.info?.name" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="primary_heading">
                {{ __('Primary Heading') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="primary_heading" v-model="form.primary_heading"
                       class="w-full"/>
                <InputError :message="form.errors?.info?.primary_heading" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-3 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="description">
                {{ __('Short description') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <text-area id="description" v-model="form.description" class="w-full"/>
                <InputError :message="form.errors?.info?.description" class="mt-2"/>
              </dd>
            </div>
          </dl>
        </Card>

        <Card :has-error="Object.keys(errors.pricing).length !== 0" class="bg-opacity-5">
          <template #header>
            {{ __('Pricing') }}
          </template>

          <template #footer>
            <PrimaryButton :class="{'opacity-75': form.processing}" :disabled="form.processing"
                           :loading="form.processing" @click="savePricingTiers">
              {{ __('Save Changes') }}
            </PrimaryButton>
          </template>

          <template #description>
            {{ __('Set monthly and yearly pricing details for the plan.') }}
          </template>

          <AlertInfo v-show="form.free">
            Please note that a <b>free plan</b> does not support additional pricing tiers. If you wish to
            create tiers, consider selecting a different plan option.
          </AlertInfo>

          <div v-show="!form.free" class="flex flex-col space-y-5">
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
                <label :for="'price-' + index" class="flex gap-x-1 text-sm font-medium text-gray-600">
                  {{ __('Price') }}
                  <span class="text-red-600">*</span>
                </label>
                <dd class="mt-1 text-sm text-gray-900">
                  <Input :id="'price-' + index" v-model="tier.price" class="w-full" min="0"
                         placeholder="e.g: 10"
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
                <PlusCircleIcon class="w-6">{{ __('Add Pricing Tier') }}</PlusCircleIcon>
              </SecondaryButton>
            </div>
          </div>
        </Card>

        <Card :has-error="Object.keys(errors.features).length !== 0">
          <template #header>
            {{ __('Features') }}
          </template>

          <template #description>
            {{ __('Enable or customize extra functionalities included with this plan.') }}
          </template>

          <template #footer>
            <PrimaryButton :class="{'opacity-75': form.processing}" :disabled="form.processing"
                           :loading="form.processing" @click="saveFeatures">
              {{ __('Save Changes') }}
            </PrimaryButton>
          </template>

          <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="col-span-3 space-y-3">
              <label class="text-sm font-medium text-gray-500" for="features">
                {{ __('Features') }}
                <span
                    class="text-red-600"> {{ __('* (comma separated)') }}</span>
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <text-area id="features" v-model="form.features" class="w-full"
                           placeholder="feature 1, feature 2"/>

                <InputError :message="form.errors?.features?.features" class="mt-2"/>
              </dd>
            </div>
          </dl>
        </Card>
      </div>

      <div class="flex flex-col space-y-4">
        <Card :bordered="true" :has-error="Object.keys(errors.settings).length !== 0">
          <template #header>
            {{ __('Settings') }}
          </template>
          <template #description>
            {{ __('Configure plan roles, and availability options') }}
          </template>

          <template #footer>
            <PrimaryButton :class="{'opacity-75': form.processing}" :disabled="form.processing"
                           :loading="form.processing" @click="savePlanSettings">
              {{ __('Save Changes') }}
            </PrimaryButton>
          </template>

          <dl class="grid grid-cols-1 gap-x-4 gap-8 sm:grid-cols-2">
            <div class="sm:col-span-2">
              <dt class="text-sm font-medium text-gray-500">
                {{ __('Status') }}
              </dt>
              <dd class="mt-1 text-sm text-gray-900">
                <SelectInput v-model="form.status" :disabled="form.processing" :values="['archive', 'active', 'draft']"/>
              </dd>
            </div>

            <div class="sm:col-span-2">
              <div class="flex flex-col space-y-2">
                <div>
                  <dt class="text-sm font-medium text-gray-500">
                    {{ __('Roles') }}
                  </dt>

                  <AlertInfo v-if="roles.length < 1" class="mt-2">
                    <p>{{ __('No roles found.') }}</p>
                  </AlertInfo>

                  <div v-else class="flex gap-x-1 items-center mt-2">
                    <div class="w-full">
                      <dd class="text-sm text-gray-900">
                        <Listbox id="role" v-model="selectedRolesLocal" as="div" multiple>
                          <div class="relative">
                            <ListboxButton
                                class="relative w-full cursor-pointer rounded-md bg-white py-1.5 pl-3 pr-10 ltr:text-left rtl:text-right text-gray-900 ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
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

            <div class="sm:col-span-2">
              <dt class="text-sm font-medium text-gray-500">{{ __('Offer Trial Days') }}</dt>
              <dd class="mt-1 text-sm text-gray-900 flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex gap-x-2 items-center justify-between">
                                    <span class="flex flex-grow flex-col">
                                      <SwitchDescription as="span" class="text-sm text-gray-500">
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

                <div v-if="offerTrialDays">
                  <Input id="name" v-model="form.trial_days" :class="{'bg-gray-100': form.free || form.processing}"
                         :disabled="form.free || form.processing"
                         class="w-full" min="0"
                         type="number"/>
                </div>
              </dd>
            </div>

            <div class="sm:col-span-2">
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

                <InputError :message="form.errors?.settings?.per_seat" class="mt-2"/>

                <div v-if="form.per_seat" class="flex flex-col space-y-2">
                  <Label for="max_seats">{{ __('Max seats:') }}</Label>
                  <Input id="max_seats" v-model="form.max_seats" :class="{'bg-gray-100': form.free || form.processing}"
                         :disabled="form.free"
                         class="w-full" min="0"
                         type="number"/>
                </div>

                <InputError :message="form.errors?.settings?.max_seats" class="mt-2"/>

                <div v-if="form.per_seat" class="flex flex-col space-y-2">
                  <Label for="seat_name">{{ __('Seat name:') }}</Label>
                  <Input id="seat_name" v-model="form.seat_name" :class="{'bg-gray-100': form.free || form.processing}"
                         :disabled="form.free"
                         class="w-full" placeholder="ex: Project, Team"
                         type="text"/>
                </div>

                <InputError :message="form.errors?.settings?.seat_name" class="mt-2"/>

                <div v-if="form.per_seat" class="flex flex-col space-y-2">
                  <Label for="seat_name_label">{{ __('Seat Label:') }}</Label>
                  <Input id="seat_name_label" v-model="form.seat_name_label"
                         :class="{'bg-gray-100': form.free || form.processing}"
                         :disabled="form.free"
                         class="w-full" placeholder="ex: Project, Team"
                         type="text"/>
                </div>

                <InputError :message="form.errors?.settings?.seat_name_label" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-2">
              <dt class="text-sm font-medium text-gray-500">{{ __('Monthly Discount Incentive') }}</dt>
              <dd class="mt-1 text-sm text-gray-900 flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex gap-x-2 items-center justify-between">
                                    <span class="flex flex-grow flex-col">
                                      <SwitchDescription as="span" class="text-sm text-gray-500">
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
                  <Input id="name" v-model="form.monthly_incentive" :class="{'bg-gray-100': form.processing}"
                         :disabled="form.processing" :placeholder="__('Save 20% on monthly plan')"
                         class="w-full"/>
                </div>

                <InputError :message="form.errors?.settings?.monthly_incentive" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-2">
              <dt class="text-sm font-medium text-gray-500">
                {{ __('Yearly Incentive Discount') }}
              </dt>
              <dd class="mt-1 text-sm text-gray-900 flex flex-col space-y-2">
                <SwitchGroup as="div" class="flex gap-x-2 items-center justify-between">
                                    <span class="flex flex-grow flex-col">
                                      <SwitchDescription as="span" class="text-sm text-gray-500">
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
                  <Input id="name" v-model="form.yearly_incentive" :class="{'bg-gray-100': form.processing}"
                         :disabled="form.processing" class="w-full"
                         placeholder="Save 20% on monthly plan"/>
                </div>

                <InputError :message="form.errors?.settings?.yearly_incentive" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-2">
              <dt class="text-sm font-medium text-gray-500">Featured</dt>
              <dd class="mt-1 text-sm text-gray-900">
                <SwitchGroup as="div" class="flex gap-x-2 items-center justify-between">
                                    <span class="flex flex-grow flex-col">
                                      <SwitchDescription as="span" class="text-sm text-gray-500">
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

              <InputError :message="form.errors?.settings?.yearly_incentive" class="mt-2"/>
            </div>
          </dl>
        </Card>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
