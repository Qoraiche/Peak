<script setup>
import SortableTableHead from "@peak/Components/Admin/SortableTableHead.vue";
import TableCell from "@peak/Components/Admin/TableCell.vue";
import Badge from "@peak/Components/Admin/Badge.vue";
import BaseTable from "@peak/Components/Admin/BaseTable.vue";

const props = defineProps({
  items: Object,
  exportable: {default: true, type: Boolean},
  searchable: {default: true, type: Boolean},
  bulkSelectable: {default: true, type: Boolean},
  exportableData: Array,
  deferred: {default: true, type: Boolean},
});

const exportableRouteName = 'admin.finance.export.subscriptions';
const indexRouteName = 'admin.finance.subscriptions.index';
const editItemRouteName = 'admin.finance.subscriptions.edit';
const exportableType = 'subscriptions';

const subscriptionStatusBadge = status => {
  switch (status) {
    case 'active':
      return 'success';
    case 'canceled':
    case 'incomplete':
    case 'unpaid':
      return 'alert';
    case 'ended':
      return 'danger';
    default:
      return 'default';
  }
};

</script>

<template>
  <base-table :deletable="false" :edit-route="editItemRouteName"
              :export-route="exportableRouteName"
              :exportable-data="exportableData"
              :index-route="indexRouteName"
              :items="items"
              collection-param="subscription"
              deferred
              exportable
              searchable>

    <template #thead>
      <SortableTableHead :sortable="true" :title="__('ID')" sort-key="id"/>
      <SortableTableHead :sortable="true" :title="__('User')" sort-key="title"/>
      <SortableTableHead title="Plan Name"/>
      <SortableTableHead :sortable="true" :title="__('Status')" sort-key="status"/>
      <SortableTableHead :sortable="true" :title="__('Created at')" sort-key="start_date"/>
    </template>

    <template #tbody="{ item }">
      <TableCell :href="route(editItemRouteName, item.id)">
        <div class="inline-flex items-center gap-1.5">
                    <span class="text-sm leading-6 text-gray-950">
                        {{ item.id }}
                    </span>
        </div>
      </TableCell>

      <TableCell :href="route(editItemRouteName, item.id)">
        <div class="flex items-center">
          <img :alt="item.user.name"
               :src="item.user.profile_photo_url" class="w-10 h-10 rounded-full">
          <div class="ltr:pl-3 rtl:pr-3 text-sm">
            <div class="font-semibold">{{ item.user.name }}</div>
            <div class="font-normal text-xs text-gray-500">{{ item.user.email }}</div>
          </div>
        </div>
      </TableCell>

      <TableCell :href="route(editItemRouteName, item.id)">
        <div class="inline-flex items-center gap-1.5">
                                <span v-if="item.plan_pricing">{{
                                    item.plan_pricing?.subscription_plan?.name + ' / ' + item.plan_pricing?.interval
                                  }}</span>

          <span v-else>
                        _
                    </span>
        </div>
      </TableCell>

      <TableCell :href="route(editItemRouteName, item.id)">
        <div class="inline-flex items-center gap-1.5">
          <Badge
              :type="subscriptionStatusBadge(item.stripe_status ? item.stripe_status.toLowerCase() : item.status.toLowerCase())">
            {{ item.stripe_status ?? item.status }}
          </Badge>
        </div>
      </TableCell>

      <TableCell :href="route(editItemRouteName, item.id)">
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
