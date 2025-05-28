<script setup>

import {inject, onMounted, ref} from 'vue';
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import Card from "@peak/Components/Admin/Card.vue";
import {Deferred, Link, router, usePage} from "@inertiajs/vue3";
import {useUrlSearchParams} from '@vueuse/core';
import AlertInfo from "@peak/Components/Admin/Alerts/AlertInfo.vue";

const page = usePage();

const props = defineProps({
  revenue: String,
  activeSubscriptions: Number,
  canceledSubscriptions: Number,
  onTrialSubscriptions: Number,
  newSubscriptions: Number,
  topPlan: String,
  defaultDates: Object,
  dateFilters: Object,
  activeDateFilter: String,
  activeChartFilter: String,
  chartStatsData: Object,
  chartTitle: String,
  chartDataNotEnough: Boolean
});

const dateFilter = ref(props.activeDateFilter);
const date = ref();
const dateReadable = ref();
const dayJS = inject('dayJS');
const urlParams = useUrlSearchParams('history');
const dataChartElement = ref(null);

onMounted(() => {
  const betweenDates = urlParams.date ? urlParams.date.split(',') : null;
  const startDate = new Date(betweenDates?.[0] ?? props.defaultDates.startDate);
  const endDate = betweenDates?.[1] ? new Date(betweenDates[1]) : new Date();
  date.value = [startDate, endDate];
});
const handleDate = (modelData) => {
  date.value = modelData;
  const secondDate = modelData[1] ? dayJS(modelData[1]).format('YYYY-MM-DD') : '';
  const betweenDate = `${dayJS(modelData[0]).format('YYYY-MM-DD')},${secondDate}`;

  router.get(route('admin.finance.overview'), {
    date: betweenDate
  }, {
    preserveScroll: true,
    preserveState: true,
  });
}

// Mock data for the chart
const series = ref([
  {
    name: props.activeChartFilter === 'revenue' ? 'Total Revenue in ' + page.props.admin.siteCurrencySymbol : '',
    data: props.chartStatsData['data'] || [], // Replace with your actual prices
  },
]);

const chartOptions = ref({
  chart: {
    type: 'area',
    height: 350,
    zoom: {
      enabled: false,
    },
  },
  dataLabels: {
    enabled: false,
  },
  stroke: {
    curve: 'straight',
  },
  title: {
    text: props.chartTitle,
    align: 'left',
  },
  subtitle: {
    text: '',
    align: 'left',
  },
  labels: props.chartStatsData['labels'] || [], // Replace with your actual dates in 'yyyy-MM-dd' format
  xaxis: {
    type: 'datetime',
  },
  yaxis: {
    labels: {
      formatter: (value) => {
        return value; // Rounds to nearest integer
      }
    },
    tickAmount: 6,
    opposite: false,
  },
  legend: {
    horizontalAlign: 'left',
  },
});

const activateChart = (type) => {

  if (dataChartElement.value) {
    dataChartElement.value.scrollIntoView({behavior: 'smooth'});
  }

  router.get(route('admin.finance.overview'), {
    date: urlParams.date,
    filter: urlParams.filter,
    'chart-filter': type,
  }, {
    preserveScroll: true,
    preserveState: false,
  });
};

</script>

<template>
  <AdminLayout :description="__('View financial stats and revenue charts')" :page-icon="false"
               :title="__('Finance Overview')">
    <template #actions>
      <div class="flex gap-x-3 items-center">
        <VueDatePicker v-model="date" :enable-time-picker="false" :max-date="new Date()"
                       range @update:model-value="handleDate"/>
      </div>
    </template>

    <div class="flex flex-col space-y-3 items-start w-full">
      <ul class="grid grid-cols-5 lg:flex lg:flex-wrap items-center space-x-3 gap-x-3 text-sm divide-gray-300">
        <li :class="[dateFilter === 'all_time' ? 'text-blue-500 font-semibold bg-white p-1 px-3 rounded-full border' : 'font-medium text-gray-800']"
            class="hover:underline">
          <Link
              :href="route('admin.finance.overview', {'date': `${dateFilters.all_time[0]},${dateFilters.all_time[1]}`, 'filter': 'all_time', 'chart-filter': activeChartFilter })">
            {{ __('All time') }}
          </Link>
        </li>
        <li :class="[dateFilter === 'today' ? 'text-blue-500 font-semibold bg-white p-1 px-3 rounded-full border' : 'font-medium text-gray-800']"
            class="hover:underline">
          <Link
              :href="route('admin.finance.overview', {'date': dateFilters.today, 'filter': 'today', 'chart-filter': activeChartFilter })">
            {{ __('Today') }}
          </Link>
        </li>
        <li :class="[dateFilter === 'last_week' ? 'text-blue-500 font-semibold bg-white p-1 px-3 rounded-full border' : 'font-medium text-gray-800']"
            class="hover:underline">
          <Link
              :href="route('admin.finance.overview', {'date': `${dateFilters.last_week[0]},${dateFilters.last_week[1]}`, 'filter': 'last_week', 'chart-filter': activeChartFilter })">
            {{ __('Last week') }}
          </Link>
        </li>
        <li :class="[dateFilter === 'last_30_days' ? 'text-blue-500 font-semibold bg-white p-1 px-3 rounded-full border' : 'font-medium text-gray-800']"
            class="hover:underline">
          <Link
              :href="route('admin.finance.overview', {'date': `${dateFilters.last_30_days[0]},${dateFilters.last_30_days[1]}`, 'filter': 'last_30_days', 'chart-filter': activeChartFilter })">
            {{ __('Last 30 days') }}
          </Link>
        </li>
        <li :class="[activeDateFilter === 'last_year' ? 'text-blue-500 font-semibold bg-white p-1 px-3 rounded-full border' : 'font-medium text-gray-800']"
            class="hover:underline">
          <Link
              :href="route('admin.finance.overview', {'date': `${dateFilters.last_year[0]},${dateFilters.last_year[1]}`, 'filter': 'last_year', 'chart-filter': activeChartFilter })">
            {{ __('Last year') }}
          </Link>
        </li>
      </ul>

      <div class="flex flex-col space-y-3 items-start w-full">
        <div class="w-full">
          <h3 class="text-base font-semibold leading-6 text-gray-600">{{ dateReadable }}</h3>
          <dl class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-4">
            <div
                :class="[activeChartFilter === 'revenue' ? 'border border-gray-500 shadow-md' : '']"
                class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6 hover:shadow-md transition-shadow duration-300 cursor-pointer"
                @click="activateChart('revenue')">
              <dt class="truncate text-sm font-medium text-gray-500">
                {{ __('Total Revenue') }}
              </dt>
              <dd class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">
                <Deferred data="revenue">
                  <template #fallback>
                    <svg class="size-5 animate-spin text-zinc-400 mt-3"
                         fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                              stroke-width="4"></circle>
                      <path class="opacity-75"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            fill="currentColor"></path>
                    </svg>
                  </template>

                  {{ revenue }}

                </Deferred>
              </dd>
            </div>
            <div
                :class="[activeChartFilter === 'active_subscriptions' ? 'border border-gray-500 shadow-md' : '']"
                class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6 hover:shadow-md transition-shadow duration-300 cursor-pointer"
                @click="activateChart('active_subscriptions')">
              <dt class="truncate text-sm font-medium text-gray-500">
                {{ __('Active Subscriptions') }}
              </dt>
              <dd class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">

                {{ activeSubscriptions }}
              </dd>
            </div>
            <div
                :class="[activeChartFilter === 'canceled_subscriptions' ? 'border border-gray-500 shadow-md' : '']"
                class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6 hover:shadow-md transition-shadow duration-300 cursor-pointer"
                @click="activateChart('canceled_subscriptions')">
              <dt class="truncate text-sm font-medium text-gray-500">
                {{ __('Canceled Subscriptions') }}
              </dt>
              <dd class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">
                {{ canceledSubscriptions }}
              </dd>
            </div>
            <div
                :class="[activeChartFilter === 'on_trial_subscriptions' ? 'border border-gray-500 shadow-md' : '']"
                class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6 hover:shadow-md transition-shadow duration-300 cursor-pointer"
                @click="activateChart('on_trial_subscriptions')">
              <dt class="truncate text-sm font-medium text-gray-500">
                {{ __('On-Trial Subscriptions') }}
              </dt>
              <dd class="mt-1 text-2xl font-semibold tracking-tight text-gray-900">
                {{ onTrialSubscriptions }}
              </dd>
            </div>
          </dl>
        </div>

        <div class="w-full">
          <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
              <div class="flex justify-between">
                <dt class="truncate text-sm font-medium text-gray-500">
                  {{ __('Top Plan') }}
                </dt>
                <Link v-if="topPlan?.subscription_plan"
                      :href="route('admin.finance.plans.edit', topPlan.subscription_plan.id)"
                      class="text-xs font-semibold hover:text-blue-600 text-right">
                  {{ __('Edit Plan') }}
                </Link>
              </div>

              <dd class="truncate mt-1 text-lg capitalize tracking-tight text-gray-900">
                <div v-if="topPlan?.subscription_plan" class="flex gap-x-2">
                                    <span>
                                        {{ topPlan.id ? topPlan.subscription_plan.name : '_' }}
                                    </span>

                  <span>
                                        - {{ topPlan.formatted_price_without_offset }} / {{ topPlan.interval }}
                                    </span>
                </div>

                <span v-else>
                                    _
                                </span>
              </dd>
            </div>
            <div
                :class="[activeChartFilter === 'new_subscriptions' ? 'border border-gray-500 shadow-md' : '']"
                class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6 hover:shadow-md transition-shadow duration-300 cursor-pointer"
                @click="activateChart('new_subscriptions')">
              <div class="flex justify-between">
                <dt class="truncate text-sm font-medium text-gray-500">
                  {{ __('New Subscriptions') }}
                </dt>
                <Link :href="route('admin.finance.subscriptions.index')"
                      class="text-xs font-semibold hover:text-blue-600 text-right"
                      @click.stop>
                  {{ __('Manage Subscriptions') }}
                </Link>
              </div>

              <dd class="truncate mt-1 text-2xl font-semibold tracking-tight text-gray-900">
                {{ newSubscriptions }}
              </dd>
            </div>
          </dl>
        </div>
        <div ref="dataChartElement" class="w-full pt-5">
          <Card>
            <div v-if="!chartDataNotEnough">
              <apexchart :options="chartOptions" :series="series" height="350" type="area"/>
            </div>
            <div v-else>
              <AlertInfo>
                {{ __('The chart requires more data to display meaningful insights.') }}
              </AlertInfo>
            </div>
          </Card>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
