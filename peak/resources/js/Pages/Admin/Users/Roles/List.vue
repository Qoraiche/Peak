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

const exportableRouteName = 'admin.user-management.export.roles';
const indexRouteName = 'admin.user-management.roles.index';
const exportableType = 'roles';
const destroyItemRouteName = 'admin.user-management.roles.destroy';
const destroyBulkItemsRouteName = 'admin.user-management.roles.bulk-destroy';

const emitter = inject('emitter');

const editItem = (id, index) => {
  const data = props.items.data[index];

  emitter.emit('role:manage', {
    id: id,
    type: 'edit',
    permissions: props.permissions,
    roleName: data.name,
    selectedPermissions: data?.permissions_ids
  });
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
              collection-param="role" deferred deletable exportable searchable>

    <template #thead>
      <SortableTableHead :sortable="true" :title="__('ID')" sort-key="id"/>
      <SortableTableHead :sortable="true" :title="__('Name')" sort-key="name"/>
      <SortableTableHead :title="__('Permissions')"/>
      <SortableTableHead :title="__('Total Users')"/>
      <SortableTableHead :sortable="true" :title="__('Created at')" sort-key="created_at"/>
    </template>

    <template #tbody="{ item, index }">
      <TableCell class="cursor-pointer" @click="editItem(item.id, index)">
        <div class="inline-flex items-center gap-1.5">
                    <span class="text-sm leading-6 text-gray-950">
                      {{ item.id }}
                    </span>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="editItem(item.id, index)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.name }}
                                </span>
        </div>
      </TableCell>

      <TableCell v-tooltip="activePermissions(item.role_permissions_based_on_all)" class="cursor-pointer"
                 @click="editItem(item.id, index)">
        <div class="inline-flex items-center gap-1.5" :class="{'text-blue-600': item.permissions_ids.length > 0}">
          {{ item.permissions_ids?.length }}
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="editItem(item.id, index)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.total_role_users_count }}
                                </span>
        </div>
      </TableCell>
      <TableCell class="cursor-pointer" @click="editItem(item.id, index)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.created_at }}
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
