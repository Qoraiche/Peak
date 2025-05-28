<script setup>
import SortableTableHead from "@peak/Components/Admin/SortableTableHead.vue";
import TableCell from "@peak/Components/Admin/TableCell.vue";
import BaseTable from "@peak/Components/Admin/BaseTable.vue";
import {inject} from "vue";
import {PencilSquareIcon} from "@heroicons/vue/24/outline/index.js";

const emitter = inject('emitter');

const props = defineProps({
  items: Object,
  exportable: {default: true, type: Boolean},
  searchable: {default: true, type: Boolean},
  bulkSelectable: {default: true, type: Boolean},
  exportableData: Array,
  deferred: {default: true, type: Boolean}
});

const exportableRouteName = 'admin.blog.export.posts';
const indexRouteName = 'admin.blog.categories.index';
const exportableType = 'articles';
const destroyItemRouteName = 'admin.blog.categories.destroy';
const destroyBulkItemsRouteName = 'admin.blog.categories.bulk-destroy';
const editItemRouteName = 'admin.blog.categories.edit';
const showItemRouteName = 'admin.blog.categories.show';
const editItem = (item) => {
  emitter.emit('blog-category:edit', item);
};

</script>

<template>
  <base-table :bulk-destroy-route="destroyBulkItemsRouteName"
              :destroy-route="destroyItemRouteName"
              :edit-route="editItemRouteName"
              :editable="false"
              :export-route="exportableRouteName"
              :exportable-data="exportableData"
              :exportable-type="exportableType"
              :index-route="indexRouteName"
              :items="items"
              collection-param="category"
              deferred
              deletable
              exportable
              item-param="slug"
              searchable>

    <template #thead>
      <SortableTableHead :sortable="true" :title="__('ID')" sort-key="id"/>
      <SortableTableHead :sortable="true" :title="__('Name')" sort-key="name"/>
      <SortableTableHead :sortable="true" :title="__('Slug')" sort-key="slug"/>
      <SortableTableHead :sortable="true" :title="__('Created at')" sort-key="created_at"/>
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
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.name }}
                                </span>
        </div>
      </TableCell>

      <TableCell class="cursor-pointer" @click="editItem(item)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.slug }}
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
    </template>

    <template #preActions="{item}">
      <button v-tooltip="__('Edit')" class="text-gray-500 hover:text-blue-600" @click="editItem(item)">
        <PencilSquareIcon class="w-5 h-5 stroke-current"/>
      </button>
    </template>
  </base-table>
</template>

<style scoped>

</style>
