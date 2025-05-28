<template>
  <div class="px-6 sm:px-0 flex items-center">
        <span class="font-bold text-sm text-gray-600 uppercase">
            <template v-if="$page.props.defaultInterval == 'monthly'">{{ __('Monthly') }}</template>
            <template v-if="$page.props.defaultInterval == 'yearly'">{{ __('Yearly') }}</template>
        </span>

    <button :class="$page.props.brandColor" aria-checked="false" class="ml-3 relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-hidden" role="checkbox"
            tabindex="0"
            @click="$emit('toggled')">
            <span :class="{ 'translate-x-0': showingDefaultIntervalPlans, 'ltr:translate-x-5 rtl:-translate-x-5': ! showingDefaultIntervalPlans }"
                  aria-hidden="true"
                  class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow-xs transform ring-0 transition ease-in-out duration-200">
            </span>
    </button>

    <span class="ml-3 font-bold text-sm text-gray-600 uppercase">
            <template v-if="$page.props.defaultInterval == 'yearly'">{{ __('Monthly') }}</template>
            <template v-if="$page.props.defaultInterval == 'monthly'">{{ __('Yearly') }}</template>
        </span>
  </div>
</template>

<script>
export default {
  props: ['showingDefaultIntervalPlans'],
}
</script>
