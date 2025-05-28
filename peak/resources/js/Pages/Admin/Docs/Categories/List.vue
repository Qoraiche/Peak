<script setup>
import SortableTableHead from "@peak/Components/Admin/SortableTableHead.vue";
import TableCell from "@peak/Components/Admin/TableCell.vue";
import BaseTable from "@peak/Components/Admin/BaseTable.vue";
import {router} from "@inertiajs/vue3";
import {inject} from "vue";
import {PencilSquareIcon} from "@heroicons/vue/24/outline/index.js";
import {CircleChevronDown, CircleChevronUp} from "lucide-vue-next";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');

const props = defineProps({
  items: Object,
  exportable: {default: true, type: Boolean},
  searchable: {default: true, type: Boolean},
  bulkSelectable: {default: true, type: Boolean},
  exportableData: Array,
  deferred: {default: true, type: Boolean}
});

const exportableRouteName = 'admin.docs.categories.export';
const indexRouteName = 'admin.docs.categories.index';
const exportableType = 'categories';
const destroyItemRouteName = 'admin.docs.categories.destroy';
const destroyBulkItemsRouteName = 'admin.docs.categories.bulk-destroy';
const editItemRouteName = 'admin.docs.categories.edit';
const showItemRouteName = 'admin.docs.categories.show';

const toast = useToast();

const editItem = (item) => {
  emitter.emit('doc-category:edit', item);
};

const moveUp = (item) => {
  router.patch(route('admin.docs.categories.move-up', item.id), {}, {
    onSuccess: () => {
      toast.success(__('Category order saved'));
    }
  });
}

const moveDown = (item) => {
  router.patch(route('admin.docs.categories.move-down', item.id), {}, {
    onSuccess: () => {
      toast.success(__('Category order saved'));
    }
  });
}
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
              searchable>

    <template #thead>
      <SortableTableHead :sortable="true" :title="__('ID')" sort-key="id"/>
      <SortableTableHead :sortable="true" :title="__('Name')" sort-key="name"/>
      <SortableTableHead :sortable="true" :title="__('Slug')" sort-key="slug"/>
      <SortableTableHead :sortable="true" :title="__('Order')" sort-key="order_column"/>
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

      <TableCell>
        <div class="inline-flex items-center gap-1.5">
          <div class="text-sm leading-6 text-gray-950 flex items-center gap-x-2">
            <span>{{ item.order_column }}</span>

            <div class="flex gap-x-2 items-center">
              <button v-if="item.order_column > 1" v-tooltip="__('Move up')" @click="moveUp(item)">
                <CircleChevronUp class="size-4"></CircleChevronUp>
              </button>

              <button v-if="item.order_column !== items.total" v-tooltip="__('Move down')" @click="moveDown(item)">
                <CircleChevronDown class="size-4"></CircleChevronDown>
              </button>
            </div>
          </div>
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
