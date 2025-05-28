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

const exportableRouteName = 'admin.user-management.export.teams';
const indexRouteName = 'admin.user-management.teams.index';
const exportableType = 'teams';
const destroyItemRouteName = 'admin.user-management.teams.destroy';
const destroyBulkItemsRouteName = 'admin.user-management.teams.bulk-destroy';
const editItemRouteName = 'admin.user-management.teams.edit';

const emitter = inject('emitter');

const editItem = (id, index) => {
  const data = props.items.data[index];

  emitter.emit('team:manage', {
    id: id,
    type: 'edit',
  });
};

</script>

<template>
  <base-table :edit-route="editItemRouteName" :bulk-destroy-route="destroyBulkItemsRouteName" :destroy-route="destroyItemRouteName"
              :export-route="exportableRouteName"
              :exportable-data="exportableData"
              :exportable-type="exportableType"
              :index-route="indexRouteName"
              :items="items"
              collection-param="team" :editable="true" deferred deletable exportable searchable>

    <template #thead>
      <SortableTableHead :sortable="true" :title="__('ID')" sort-key="id"/>
      <SortableTableHead :sortable="true" :title="__('Owner')" sort-key="name"/>
      <SortableTableHead :sortable="true" :title="__('Name')" sort-key="name"/>
      <SortableTableHead :title="__('Total Users')"/>
      <SortableTableHead :sortable="true" :title="__('Created at')" sort-key="created_at"/>
    </template>

    <template #tbody="{ item, index }">
      <TableCell :href="route(editItemRouteName, item.id)">
        <div class="inline-flex items-center gap-1.5">
                    <span class="text-sm leading-6 text-gray-950">
                      {{ item.id }}
                    </span>
        </div>
      </TableCell>

      <TableCell :href="route(editItemRouteName, item.id)">
        <div class="flex items-center text-gray-900 whitespace-nowrap">
          <img :alt="item.owner.name"
               :src="item.owner.profile_photo_url" class="size-8 min-w-[2rem] rounded-full object-cover">
          <div class="ltr:pl-3 rtl:pr-3 text-sm">
            <div class="font-semibold">{{ item.owner.name }}</div>
            <div class="font-normal text-gray-700 text-xs">{{ item.owner.email }}</div>
          </div>
        </div>
      </TableCell>

      <TableCell :href="route(editItemRouteName, item.id)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.name }}
                                </span>
        </div>
      </TableCell>

      <TableCell :href="route(editItemRouteName, item.id)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.users_count }}
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
