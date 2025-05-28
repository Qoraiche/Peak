<script setup>
import SortableTableHead from "@peak/Components/Admin/SortableTableHead.vue";
import TableCell from "@peak/Components/Admin/TableCell.vue";
import BaseTable from "@peak/Components/Admin/BaseTable.vue";
import {inject} from "vue";
import {__} from "@peak/Composables/useTranslate.js";
import {Eye} from "lucide-vue-next";
import {Link} from "@inertiajs/vue3";

const props = defineProps({
  items: Object,
  exportable: {default: true, type: Boolean},
  searchable: {default: true, type: Boolean},
  bulkSelectable: {default: true, type: Boolean},
  exportableData: Array,
  deferred: {default: true, type: Boolean},
});

const emitter = inject('emitter');
const exportableRouteName = 'admin.reports.export';
const indexRouteName = 'admin.reports.index';
const exportableType = 'reports';
const destroyItemRouteName = 'admin.reports.destroy';
const destroyBulkItemsRouteName = 'admin.reports.bulk-destroy';

const viewItem = item => {
  emitter.emit('report:view', item);
};

</script>

<template>
  <base-table :bulk-destroy-route="destroyBulkItemsRouteName"
              :destroy-route="destroyItemRouteName"
              :editable="false"
              :export-route="exportableRouteName"
              :exportable-data="exportableData"
              :exportable-type="exportableType"
              :index-route="indexRouteName"
              :items="items"
              collection-param="report"
              deferred
              deletable
              exportable
              item-param="id"
              searchable>

    <template #thead>
      <SortableTableHead :sortable="true" :title="__('ID')" sort-key="id"/>
      <SortableTableHead :sortable="true" :title="__('Subject')" sort-key="subject"/>
      <SortableTableHead :sortable="true" :title="__('Reported By')" sort-key="user_id"/>
      <SortableTableHead :sortable="true" :title="__('Created at')" sort-key="created_at"/>
    </template>

    <template #tbody="{ item }">
      <TableCell class="cursor-pointer" @click="viewItem(item)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.id }}
                                </span>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="viewItem(item)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.reportable_title }}
                                </span>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="viewItem(item)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 capitalize text-gray-950">
                                    {{ item.user.name }}
                                </span>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="viewItem(item)">
        <div class="inline-flex items-center gap-1.5">
                    <span class="text-sm leading-6 text-gray-950">
                        {{ item.created_at }}
                    </span>
        </div>
      </TableCell>
    </template>

    <template #preActions="{item}">
      <button @click="viewItem(item)" v-tooltip="__('Edit')" class="text-gray-500 hover:text-blue-600">
        <Eye class="w-5 h-5 stroke-current"/>
      </button>
    </template>
  </base-table>
</template>

<style scoped>

</style>
