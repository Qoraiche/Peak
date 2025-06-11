<script setup>
import SortableTableHead from "@peak/Components/Admin/SortableTableHead.vue";
import TableCell from "@peak/Components/Admin/TableCell.vue";
import BaseTable from "@peak/Components/Admin/BaseTable.vue";

const props = defineProps({
  items: Object,
  exportable: {default: true, type: Boolean},
  searchable: {default: true, type: Boolean},
  bulkSelectable: {default: true, type: Boolean},
  exportableData: Array,
  deferred: {default: true, type: Boolean}
});

const exportableRouteName = 'admin.user-management.export.users';
const indexRouteName = 'admin.user-management.users.index';
const exportableType = 'users';
const destroyItemRouteName = 'admin.user-management.users.destroy';
const destroyBulkItemsRouteName = 'admin.user-management.users.bulk-destroy';
const editItemRouteName = 'admin.user-management.users.edit';

</script>

<template>
  <base-table :bulk-destroy-route="destroyBulkItemsRouteName"
              :destroy-route="destroyItemRouteName"
              :edit-route="editItemRouteName"
              :export-route="exportableRouteName"
              :exportable-data="exportableData"
              :exportable-type="exportableType"
              :index-route="indexRouteName"
              :items="items"
              collection-param="page"
              deferred
              deletable
              exportable
              searchable>

    <template #thead>
      <SortableTableHead :sortable="true" :title="__('ID')" sort-key="id"/>
      <SortableTableHead :sortable="true" :title="__('User')" sort-key="name"/>
      <SortableTableHead :sortable="true" :title="__('Role')" sort-key="published"/>
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
        <div class="flex items-center text-gray-900 whitespace-nowrap">
          <img :alt="item.name"
               :src="item.profile_photo_url" class="size-8 min-w-[2rem] rounded-full object-cover">
          <div class="ltr:pl-3 rtl:pr-3 text-sm">
            <div class="font-semibold">{{ item.name }}</div>
            <div class="font-normal text-gray-700 text-xs">{{ item.email }}</div>
          </div>
        </div>
      </TableCell>

      <TableCell :href="route(editItemRouteName, item.id)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.roles?.length < 1 ? '- ' : item.roles.map(role => role.name).join(', ') }}
                                </span>
        </div>
      </TableCell>

      <TableCell :href="route(editItemRouteName, item.id)">
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
