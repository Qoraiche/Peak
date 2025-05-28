<script setup>
import SortableTableHead from "@peak/Components/Admin/SortableTableHead.vue";
import TableCell from "@peak/Components/Admin/TableCell.vue";
import Badge from "@peak/Components/Admin/Badge.vue";
import BaseTable from "@peak/Components/Admin/BaseTable.vue";
import {DocumentArrowDownIcon} from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
  items: Object,
  exportable: {default: true, type: Boolean},
  searchable: {default: true, type: Boolean},
  bulkSelectable: {default: true, type: Boolean},
  exportableData: Array,
  deferred: {default: true, type: Boolean},
});

const exportableRouteName = 'admin.pages.export';
const indexRouteName = 'admin.exports.index';
const exportableType = 'exports';
const destroyItemRouteName = 'admin.exports.delete';
const destroyBulkItemsRouteName = 'admin.exports.items.bulk-delete';

const exportStatusBadge = (status) => {
  switch (status) {
    case 'pending':
      return 'info';

    case 'completed':
      return 'success';

    case 'failed':
      return 'danger';

    default:
      return 'default';
  }
};

</script>

<template>
  <base-table :bulk-destroy-route="destroyBulkItemsRouteName" :destroy-route="destroyItemRouteName"
              :editable="false"
              :export-route="exportableRouteName"
              :exportable="exportable"
              :exportable-data="exportableData"
              :index-route="indexRouteName"
              :items="items"
              collection-param="export" deferred deletable searchable>

    <template #thead>
      <SortableTableHead :sortable="true" :title="__('ID')" sort-key="id"/>
      <SortableTableHead :sortable="true" :title="__('Name')" sort-key="name"/>
      <SortableTableHead :sortable="true" :title="__('Status')" sort-key="status"/>
      <SortableTableHead :sortable="true" :title="__('File Name')" sort-key="file_name"/>
      <SortableTableHead :sortable="true" :title="__('Format')" sort-key="format"/>
      <SortableTableHead :sortable="true" :title="__('Locale')" sort-key="locale"/>
      <SortableTableHead :sortable="true" :title="__('Created at')" sort-key="created_at"/>
    </template>

    <template #tbody="{ item }">

      <TableCell>
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.id }}
                                </span>
        </div>
      </TableCell>

      <TableCell>
        <div class="inline-flex items-center gap-1.5">
                    <span class="text-sm leading-6 text-gray-950">
                        {{ item.name }}
                    </span>
        </div>
      </TableCell>

      <TableCell>
        <div class="inline-flex items-center gap-1.5">
          <Badge :type="exportStatusBadge(item.status)" class="text-xs uppercase">
            {{ __(item.status) }}
          </Badge>
        </div>
      </TableCell>


      <TableCell>
        <div class="inline-flex items-center gap-1.5">
                    <span class="text-sm leading-6 text-gray-950">
                        {{ item.file_name }}
                    </span>
        </div>
      </TableCell>

      <TableCell>
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950 uppercase">
                                    {{ item.format }}
                                </span>
        </div>
      </TableCell>

      <TableCell>
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950 uppercase">
                                    {{ item.locale }}
                                </span>
        </div>
      </TableCell>

      <TableCell>
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950 uppercase">
                                    {{ item.created_at }}
                                </span>
        </div>
      </TableCell>
    </template>

    <template #preActions="{item}">
      <a v-if="item.status === 'completed'" v-tooltip="__('Download')" :href="item.download_url"
         class="text-gray-500 hover:text-blue-600"
         target="_blank">
        <DocumentArrowDownIcon class="w-5 h-5 stroke-current"/>
      </a>
    </template>
  </base-table>
</template>

<style scoped>

</style>
