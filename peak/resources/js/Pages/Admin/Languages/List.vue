<script setup>
import SortableTableHead from "@peak/Components/Admin/SortableTableHead.vue";
import TableCell from "@peak/Components/Admin/TableCell.vue";
import Badge from "@peak/Components/Admin/Badge.vue";
import BaseTable from "@peak/Components/Admin/BaseTable.vue";
import {inject} from "vue";
import {CircleArrowDown, CircleArrowUp, CircleCheck, Languages} from "lucide-vue-next";
import {Link, router} from "@inertiajs/vue3";

const emitter = inject('emitter');

const props = defineProps({
  items: Object,
  exportable: {default: true, type: Boolean},
  searchable: {default: true, type: Boolean},
  bulkSelectable: {default: true, type: Boolean},
  exportableData: Array,
  deferred: {default: true, type: Boolean},
});

const exportableRouteName = 'admin.languages.export';
const indexRouteName = 'admin.languages.index';
const showRouteName = 'admin.languages.show';
const exportableType = 'languages';
const destroyItemRouteName = 'admin.languages.destroy';
const destroyBulkItemsRouteName = 'admin.languages.bulk-destroy';
const editItemRouteName = 'admin.languages.edit';

const editItem = (item) => {
  emitter.emit('language:edit', {language: item});
};
</script>

<template>
  <base-table :bulk-destroy-route="destroyBulkItemsRouteName" :deferred="deferred"
              :destroy-route="destroyItemRouteName"
              :edit-route="editItemRouteName"
              :editable="false"
              :export-route="exportableRouteName"
              :exportable-data="exportableData"
              :index-route="indexRouteName"
              :items="items"
              collection-param="language" deletable exportable searchable>

    <template #thead>
      <SortableTableHead :sortable="true" :title="__('ID')" sort-key="id"/>
      <SortableTableHead :sortable="true" :title="__('Name')" sort-key="name"/>
      <SortableTableHead :sortable="true" :title="__('Code')" sort-key="code"/>
      <SortableTableHead :sortable="true" :title="__('Direction')" sort-key="direction"/>
      <SortableTableHead :sortable="true" :title="__('Status')" sort-key="active"/>
      <SortableTableHead :sortable="true" :title="__('Created at')" sort-key="created_at"/>
      <SortableTableHead :sortable="true" :title="__('Updated at')" sort-key="updated_at"/>
    </template>

    <template #tbody="{ item }">

      <TableCell class="cursor-pointer" @click="editItem(item)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.id }}
                                </span>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="editItem(item)">
        <div class="inline-flex items-center gap-1.5">
          <div class="flex items-center gap-x-2">
            <span class="emoji">{{ item.flag_emoji }}</span>
            <span>{{ item.name }}</span>
            <Badge v-if="item.default" class="text-xs uppercase" type="default">{{ __('Default') }}</Badge>
          </div>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="editItem(item)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950 uppercase">
                                    {{ item.code }}
                                </span>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="editItem(item)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950 uppercase">
                                    {{ item.direction }}
                                </span>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="editItem(item)">
        <div class="inline-flex items-center gap-1.5">
          <Badge :type="item.active ? 'success' : 'default'" class="text-xs">
            {{ item.active ? __('Active') : __('Inactive') }}
          </Badge>
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

    <template #preActions="{item}">
      <button v-if="!item.default" v-tooltip="__('Set Default')" class="text-gray-500 hover:text-blue-600"
              @click="router.patch(route('admin.languages.make-default', item.id))">
        <CircleCheck class="w-5 h-5 stroke-current"/>
      </button>

      <button v-if="item.order_column > 1" v-tooltip="__('Move up')" class="text-gray-500 hover:text-blue-600"
              @click="router.patch(route('admin.languages.move-up', item.id))">
        <CircleArrowUp class="w-5 h-5 stroke-current"/>
      </button>

      <button v-if="item.order_column !== items.total" v-tooltip="__('Move down')" class="text-gray-500 hover:text-blue-600"
              @click="router.patch(route('admin.languages.move-down', item.id))">
        <CircleArrowDown class="w-5 h-5 stroke-current"/>
      </button>

      <Link v-tooltip="__('Edit Translations')"
            :href="route('admin.languages.show', item.id)"
            class="text-gray-500 hover:text-blue-600">
        <Languages class="w-5 h-5 stroke-current"/>
      </Link>
    </template>
  </base-table>
</template>

<style scoped>

</style>
