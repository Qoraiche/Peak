<script setup>

import Badge from "@peak/Components/Admin/Badge.vue";
import BaseTable from "@peak/Components/Admin/BaseTable.vue";
import SortableTableHead from "@peak/Components/Admin/SortableTableHead.vue";
import TableCell from "@peak/Components/Admin/TableCell.vue";
import {limitText} from "@peak/Composables/Utils.js";

const props = defineProps({
  items: Object,
  exportable: {default: true, type: Boolean},
  searchable: {default: true, type: Boolean},
  bulkSelectable: {default: true, type: Boolean},
  exportableData: Array,
  deferred: {default: true, type: Boolean}
});

const exportableRouteName = 'admin.roadmaps.export';
const indexRouteName = 'admin.roadmaps.index';
const exportableType = 'roadmaps';
const destroyItemRouteName = 'admin.roadmaps.destroy';
const destroyBulkItemsRouteName = 'admin.roadmaps.bulk-destroy';
const editItemRouteName = 'admin.roadmaps.edit';

</script>

<template>
  <base-table :bulk-destroy-route="destroyBulkItemsRouteName" :destroy-route="destroyItemRouteName"
              :edit-route="editItemRouteName"
              :export-route="exportableRouteName" :exportable-data="exportableData" :index-route="indexRouteName"
              :items="items" collection-param="roadmap" deferred
              deletable
              exportable searchable>

    <template #thead>
      <SortableTableHead :sortable="true" :title="__('ID')" sort-key="id"/>
      <SortableTableHead :sortable="true" :title="__('Title')" sort-key="title"/>
      <SortableTableHead :sortable="true" :title="__('Status')" sort-key="published"/>
      <SortableTableHead :sortable="true" :title="__('Created at')" sort-key="created_at"/>
      <SortableTableHead :sortable="true" :title="__('Updated at')" sort-key="updated_at"/>
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
        <div class="flex items-center text-gray-900 whitespace-nowrap">
          <div class="text-sm">
            <div v-if="item.title" class="font-semibold">{{ limitText(item.title, 35) }}</div>
            <div v-else class="italic">{{ __('No Title') }}</div>
            <div class="text-xs italic font-normal text-gray-700">
              /{{ item.id ? limitText(item.slug, 35) : item.slug }}
            </div>
          </div>
        </div>
      </TableCell>

      <TableCell :href="route(editItemRouteName, item.id)">
        <div class="inline-flex items-center gap-1.5">
          <Badge :type="item.published ? 'success' : 'default'">
            {{ item.published ? __('Published') : __('Draft') }}
          </Badge>
        </div>
      </TableCell>

      <TableCell :href="route(editItemRouteName, item.id)">
        <div class="inline-flex items-center gap-1.5">
                    <span class="text-sm leading-6 text-gray-950">
                        {{ item.created_at }}
                    </span>
        </div>
      </TableCell>

      <TableCell :href="route(editItemRouteName, item.id)">
        <div class="inline-flex items-center gap-1.5">
                    <span class="text-sm leading-6 text-gray-950">
                        {{ item.updated_at }}
                    </span>
        </div>
      </TableCell>
    </template>

    <template #tbodyActions="{ item }">
      <!-- add more actions-->
    </template>
  </base-table>
</template>

<style scoped></style>
