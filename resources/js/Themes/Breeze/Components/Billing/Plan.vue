<template>
  <div>
    <div class="flex justify-between">
      <h2 class="ltr:pl-6 rtl:pr-6 pt-6 text-xl font-semibold text-gray-700">
        {{ __(plan.name) }}
      </h2>

      <div v-if="! hideIncentive && ((plan.incentive.monthly && plan.interval == 'monthly') ||
                              (plan.incentive.yearly && plan.interval == 'yearly'))"
           class="h-1/2 px-4 py-1 bg-gray-200 text-gray-700 text-sm font-semibold rounded-bl-md">
        {{ __(plan.incentive[plan.interval]) }}
      </div>
    </div>

    <div class="px-6 pb-6">
      <div class="mt-2 text-md font-semibold text-gray-700">
        <span v-html="plan.price"></span>
        <span v-if="$page.props.collectsVat">({{ __('ex VAT') }})</span>
        <span v-if="seatName" class="pr-2"> / {{ __(seatName) }} / {{ __(plan.interval) }}</span>
        <span v-else>/ {{ __(plan.interval) }}</span>
        <span v-if="plan.trial_days" class="text-gray-400">({{
            __(':days day trial', {days: plan.trial_days})
          }})</span>
      </div>

      <div class="mt-3 max-w-xl text-sm text-gray-600">
        {{ __(plan.short_description) }}
      </div>

      <div class="mt-3 space-y-2">
        <div v-for="feature in plan.subscription_plan?.features" class="flex items-center">
          <svg :class="{'text-green-400': !feature.startsWith('--'), 'text-gray-400': feature.startsWith('--')}"
               class="shrink-0 w-5 h-5"
               fill="currentColor"
               viewBox="0 0 20 20">
            <path clip-rule="evenodd"
                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                  fill-rule="evenodd"></path>
          </svg>

          <div
              :class="{'text-gray-600': !feature.startsWith('--'), 'text-gray-400 line-through': feature.startsWith('--')}"
              class="ml-2 text-sm text-gray-600">
            {{ __(feature.startsWith('--') ? feature.slice(2) : feature) }}
          </div>
        </div>
      </div>

      <div v-if="collectionMethod" class="mt-3 text-xs text-gray-500">
                <span v-if="collectionMethod === 'send_invoice'" class="flex justify-items-center">
                    <envelope-icon class="inline-block mr-1 w-4 h-4"/>
                    {{ __('You will receive an invoice and payment link via email for each billing period.') }}
                </span>

        <span v-if="collectionMethod === 'charge_automatically'" class="flex justify-items-center">
                    <arrow-path-icon class="inline-block ltr:mr-1 rtl:ml-1 w-4 h-4"/>
                    {{ __('Your payment method will be charged automatically for each billing period.') }}
                </span>
      </div>
    </div>
  </div>
</template>

<script>
import {ArrowPathIcon, EnvelopeIcon} from '@heroicons/vue/24/solid/index.js';

export default {
  components: {
    EnvelopeIcon,
    ArrowPathIcon,
  },

  props: ['plan', 'collectionMethod', 'seatName', 'hideIncentive'],
}
</script>
