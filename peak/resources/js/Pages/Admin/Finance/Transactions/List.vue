<script setup>
import SortableTableHead from "@peak/Components/Admin/SortableTableHead.vue";
import TableCell from "@peak/Components/Admin/TableCell.vue";
import Badge from "@peak/Components/Admin/Badge.vue";
import BaseTable from "@peak/Components/Admin/BaseTable.vue";
import {inject} from "vue";

const emitter = inject('emitter');

const props = defineProps({
  items: Object,
  exportable: {default: true, type: Boolean},
  searchable: {default: true, type: Boolean},
  bulkSelectable: {default: true, type: Boolean},
  exportableData: Array,
  deferred: {default: true, type: Boolean},
});

const exportableRouteName = 'admin.finance.export.transactions';
const indexRouteName = 'admin.finance.transactions.index';
const exportableType = 'transactions';

const transactionStatusBadge = status => {
  switch (status) {
    case 'success':
    case 'paid':
    case 'completed':
      return 'success';
    case 'refunded':
      return 'alert';
    case 'failed':
      return 'danger';
    default:
      return 'default';
  }
};

const viewItem = (item) => {
  emitter.emit('transaction:view', item);
}

</script>

<template>
  <base-table :deletable="false" :editable="false"
              :export-route="exportableRouteName"
              :exportable-data="exportableData"
              :index-route="indexRouteName"
              :items="items"
              collection-param="transaction"
              deferred
              exportable
              searchable>

    <template #thead>
      <SortableTableHead :sortable="true" :title="__('ID')" sort-key="id"/>
      <SortableTableHead :sortable="true" :title="__('User')" sort-key="billable_id"/>
      <SortableTableHead :sortable="true" :title="__('Provider')" sort-key="provider"/>
      <SortableTableHead :sortable="true" :title="__('Invoice Number')" sort-key="invoice_number"/>
      <SortableTableHead :sortable="true" :title="__('Status')" sort-key="status"/>
      <SortableTableHead :sortable="true" :title="__('Full amount')" sort-key="total"/>
      <SortableTableHead :sortable="true" :title="__('Created at')" sort-key="created_at"/>
    </template>

    <template #tbody="{ item }">
      <TableCell @click="viewItem(item)">
        <div class="inline-flex items-center gap-1.5">
                    <span class="text-sm leading-6 text-gray-950">
                        {{ item.id }}
                    </span>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="viewItem(item)">
        <div class="flex items-center">
          <img :alt="item.user.name"
               :src="item.user.profile_photo_url" class="w-10 h-10 rounded-full">
          <div class="ltr:pl-3 rtl:pr-3 text-sm">
            <div class="font-semibold">{{ item.user.name }}</div>
            <div class="font-normal text-xs text-gray-500">{{ item.user.email }}</div>
          </div>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="viewItem(item)">
        <div class="inline-flex items-center gap-1.5 capitalize">
          {{ item.provider }}
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="viewItem(item)">
        <div class="inline-flex items-center gap-1.5">
          {{ item.invoice_number }}
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="viewItem(item)">
        <div class="inline-flex items-center gap-1.5">
          <Badge
              :type="transactionStatusBadge(item.status)">
            {{ item.status }}
          </Badge>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="viewItem(item)">
        <div class="inline-flex items-center gap-1.5">
          <div class="flex items-center">
            {{ item.full_amount }}
          </div>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="viewItem(item)">
        <div class="inline-flex items-center gap-1.5">
          <div class="flex items-center">
            {{ item.created_at }}
          </div>
        </div>
      </TableCell>
    </template>

    <template #tbodyActions="{item}">
      <!-- add more actions-->
    </template>
  </base-table>
</template>

<style scoped>

</style>
