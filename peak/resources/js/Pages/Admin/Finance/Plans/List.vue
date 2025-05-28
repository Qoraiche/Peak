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
  deferred: {default: true, type: Boolean}
});

const exportableRouteName = 'admin.finance.export.plans';
const indexRouteName = 'admin.finance.plans.index';
const editItemRouteName = 'admin.finance.plans.edit';
const destroyItemRouteName = 'admin.finance.plans.destroy';
const destroyBulkItemsRouteName = 'admin.finance.plans.bulk-destroy';
const exportableType = 'plans';

const planStatusForBadge = status => {
  switch (status) {
    case 'active':
      return 'success';
    case 'archive':
      return 'danger';
    default:
      return 'default';
  }
};

</script>

<template>
  <base-table :bulk-destroy-route="destroyBulkItemsRouteName" :destroy-route="destroyItemRouteName"
              :edit-route="editItemRouteName"
              :export-route="exportableRouteName"
              :exportable-data="exportableData"
              :exportable-type="exportableType"
              :index-route="indexRouteName"
              :items="items"
              collection-param="plan"
              deferred
              deletable
              exportable
              searchable>

    <template #thead>
      <SortableTableHead :sortable="true" :title="__('ID')" sort-key="id"/>
      <SortableTableHead :sortable="true" :title="__('Name')" sort-key="name"/>
      <SortableTableHead title="Tiers"/>
      <SortableTableHead :sortable="true" :title="__('Status')" sort-key="status"/>
      <SortableTableHead :sortable="true" :title="__('Created at')" sort-key="created_at"/>
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
        <div class="flex gap-x-3 items-center">
          <div class="inline-flex items-center gap-1.5">
            {{ item.name || '-' }}
          </div>

          <Badge v-if="item.featured" type="default">{{ __('Featured') }}</Badge>
        </div>
      </TableCell>

      <TableCell :href="route(editItemRouteName, item.id)">
        <div class="inline-flex items-center gap-1.5">
          <ul>
            <li v-for="pricingTier in item.subscription_plan_pricings" :key="pricingTier.id">
              {{ pricingTier.formatted_price }} / {{ pricingTier.interval }}
            </li>
          </ul>
        </div>
      </TableCell>

      <TableCell :href="route(editItemRouteName, item.id)">
        <div class="inline-flex items-center gap-1.5">
          <Badge
              :type="planStatusForBadge(item.status)">
            {{ item.status }}
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
