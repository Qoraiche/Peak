<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import StatsWidget from "@peak/Components/Admin/StatsWidget.vue";
import TransactionsList from "@peak/Pages/Admin/Finance/Transactions/List.vue";

const props = defineProps({
  items: Object,
  totalTransactionsCount: String | Number,
  totalTransactionsAmount: String | Number,
  totalTransactionsFees: String | Number,
  exportableData: Array
});

</script>

<template>

  <AdminLayout :description="__('View a log of all transactions')" :page-icon="false" :title="__('Transactions')">
    <div class="mt-8 flex flex-col gap-y-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        <StatsWidget :deferred="true" deferred-data="totalTransactionsCount">
          <template #label>
            {{ __('Total Transactions') }}
          </template>

          <template #data>
            {{ totalTransactionsCount }}
          </template>
        </StatsWidget>

        <StatsWidget :deferred="true" deferred-data="totalTransactionsAmount">
          <template #label>
            {{ __('Total Transactions Amount') }}
          </template>

          <template #data>
            {{ totalTransactionsAmount }}
          </template>
        </StatsWidget>

        <StatsWidget :deferred="true" deferred-data="totalTransactionsFees">
          <template #label>
            {{ __('Total Transactions Fees') }}
          </template>

          <template #data>
            {{ totalTransactionsFees }}
          </template>
        </StatsWidget>
      </div>

      <transactions-list :exportable="true" :exportable-data="exportableData" :items="items" :searchable="true"/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
