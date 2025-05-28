<script setup>
import SortableTableHead from "@peak/Components/Admin/SortableTableHead.vue";
import TableCell from "@peak/Components/Admin/TableCell.vue";
import BaseTable from "@peak/Components/Admin/BaseTable.vue";
import {inject} from "vue";

const props = defineProps({
  items: Object,
  exportable: {default: true, type: Boolean},
  searchable: {default: true, type: Boolean},
  bulkSelectable: {default: true, type: Boolean},
  exportableData: Array,
  permissions: {default: [], type: [Object, Array]},
  deferred: {default: true, type: Boolean},
});

const exportableRouteName = 'admin.user-management.export.permissions';
const indexRouteName = 'admin.user-management.permissions.index';
const exportableType = 'permissions';
const destroyItemRouteName = 'admin.user-management.permissions.destroy';
const destroyBulkItemsRouteName = 'admin.user-management.permissions.bulk-destroy';

const emitter = inject('emitter');

const editItem = (item) => {
  emitter.emit('permission:manage', {...item, type: 'edit'});
};

const activePermissions = (permissions) => {
  return Object.entries(permissions)
      .filter(([key, value]) => value) // Keep only entries where value is true
      .map(([key]) => key)
      .join(', ')
};
</script>

<template>
  <base-table :bulk-destroy-route="destroyBulkItemsRouteName" :destroy-route="destroyItemRouteName"
              :editable="false"
              :export-route="exportableRouteName"
              :exportable-data="exportableData"
              :exportable-type="exportableType"
              :index-route="indexRouteName"
              :items="items"
              collection-param="permission"
              deferred deletable exportable searchable>

    <template #thead>
      <SortableTableHead :sortable="true" :title="__('ID')" sort-key="id"/>
      <SortableTableHead :sortable="true" :title="__('Name')" sort-key="name"/>
      <SortableTableHead :sortable="true" :title="__('Group')" sort-key="group"/>
      <SortableTableHead :sortable="true" :title="__('Guard name')" sort-key="guard_name"/>
      <SortableTableHead :sortable="true" :title="__('Created at')" sort-key="created_at"/>
      <SortableTableHead :sortable="true" :title="__('Updated at')" sort-key="updated_at"/>
    </template>

    <template #tbody="{ item, index }">
      <TableCell class="cursor-pointer" @click="editItem(item)">
        <div class="inline-flex items-center gap-1.5">
                    <span class="text-sm leading-6 text-gray-950">
                      {{ item.id }}
                    </span>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="editItem(item)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.name }}
                                </span>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="editItem(item)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.group ?? '―' }}
                                </span>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="editItem(item)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.guard_name ?? '―' }}
                                </span>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="editItem(item)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.created_at }}
                                </span>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="editItem(item)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.updated_at }}
                                </span>
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
