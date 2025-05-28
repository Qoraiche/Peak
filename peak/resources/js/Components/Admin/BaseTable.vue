<script setup>
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";

import {
  ArrowUpOnSquareStackIcon,
  ChevronDownIcon,
  CursorArrowRaysIcon,
  PencilSquareIcon,
  TrashIcon
} from "@heroicons/vue/24/outline/index.js";

import {Deferred, Link, router} from "@inertiajs/vue3";
import {inject, ref, watch} from "vue";
import {useConfirm} from "@peak/Composables/useConfirm.js";
import {useToast} from "vue-toastification";
import Input from "@peak/Components/Admin/Input.vue";
import AlertInfo from "@peak/Components/Admin/Alerts/AlertInfo.vue";
import Pagination from "@peak/Components/Admin/Pagination.vue";
import DataSearchForm from "@peak/Components/Admin/DataSearchForm.vue";
import LimitDropDown from "@peak/Components/Admin/LimitDropDown.vue";
import SortableTableHead from "@peak/Components/Admin/SortableTableHead.vue";
import PrimaryLightButton from "@peak/Components/Admin/PrimaryLightButton.vue";
import {__} from "@peak/Composables/useTranslate.js";
import ExportTable from "@peak/Drawers/Admin/ExportTable.vue";

const props = defineProps({
  items: {default: {}, type: Object},
  itemParam: {default: 'id', type: [String, null]},
  exportable: {default: true, type: Boolean},
  deferred: {default: true, type: Boolean},
  collectionParam: {default: 'id', type: String},
  deletable: {default: true, type: Boolean},
  editable: {default: true, type: Boolean},
  exportNotifyLaterButton: {default: true, type: Boolean},
  exportRoute: {default: null, type: [String, null]},
  indexRoute: {default: null, type: [String, null]},
  editRoute: {default: null, type: [String, null]},
  exportableType: {default: null, type: [String, null]},
  destroyRoute: {default: null, type: [String, null]},
  bulkDestroyRoute: {default: null, type: [String, null]},
  searchable: {default: true, type: Boolean},
  bulkSelectable: {default: true, type: Boolean},
  exportableData: {default: [], type: Array},
  destroyTitle: {default: __('Confirm Item Deletion'), type: String},
  destroyText: {
    default: __('Are you sure you want to delete this item? This action cannot be undone.'),
    type: String
  },
  bulkDestroyTitle: {default: __('Confirm Bulk Deletion'), type: String},
  bulkDestroyText: {
    default: __('Are you sure you want to delete these items? This action cannot be undone.'),
    type: String
  }
});

const exportableRouteName = props.exportRoute;
const indexRouteName = props.indexRoute;
const exportableType = props.exportableType ?? 'collection';
const destroyItemRouteName = props.destroyRoute;
const destroyBulkItemsRouteName = props.bulkDestroyRoute;
const editItemRouteName = props.editRoute;

const toast = useToast();
const emitter = inject('emitter');
const bulkSelect = ref(false);
const selectAllChecked = ref(false);
const selected = ref([]);
const selectAllCheckedText = ref(__(':length item(s) selected', {length: selected.value.length}));

const selectAll = () => {
  const ids = props.items.data?.map(item => item.id);
  selected.value = Array.from(new Set([...selected.value, ...ids]));
}

const unSelectAll = () => {
  selected.value = [];
}

watch(bulkSelect, newValue => {
  if (!newValue) {
    unSelectAll();
  }
});

watch(selected, newValue => {
  selectAllChecked.value = newValue.length === props.items.data?.length;
  selectAllCheckedText.value = __(':length items selected', {length: selected.value.length});
});

watch(selectAllChecked, (newValue) => {
  if (newValue) {
    selectAll();
  } else {
    if (selected.value.length === props.items.data?.length) {
      unSelectAll();
    }
  }
});

const exportData = (all = false) => {
  let ids = [];

  if (!all) {
    ids = selected.value.length > 0 ? selected.value : props.items.data?.map(item => item.id);
  }

  emitter.emit('export:start', {
    type: exportableType,
    exportableData: props.exportableData,
    exportableRoute: exportableRouteName,
    selectedIds: ids
  });
};

const deleteAction = async (items) => {
  if (!items || (Array.isArray(items) && items.length === 0)) {
    toast.error(__("No items selected for deletion."));
    return;
  }

  const isBulkItems = Array.isArray(items);

  const confirmed = await useConfirm({
    title: isBulkItems
        ? props.bulkDestroyTitle
        : props.destroyTitle,
    text: isBulkItems
        ? props.bulkDestroyText
        : props.destroyText,
    confirmButtonText: isBulkItems ? __("Delete All") : __("Delete"),
    confirmButtonColor: "red",
  });

  if (!confirmed) return;

  const RouteName = isBulkItems ? destroyBulkItemsRouteName : destroyItemRouteName;
  const RouteParams = isBulkItems ? null : {[props.collectionParam]: items};

  // todo: use router.post instead with fake method data for single destroy.
  router.post(route(RouteName, RouteParams), {
    _method: 'DELETE',
    ids: items ?? []
  }, {
    onSuccess: () => {
      toast.success(isBulkItems ? __("Selected items were successfully deleted.") : __("Item was successfully deleted."));
    },
    onError: (error) => {
      toast.error(error?.message || __("An error occurred while deleting the item. Please try again."));
    },
    onFinish: () => {
      unSelectAll();
    }
  });
};

const deleteItem = id => {
  deleteAction(id);
}

const bulkDelete = () => {
  deleteAction(selected.value);
};

</script>

<template>
  <ExportTable :notify-later-button="exportNotifyLaterButton"/>

  <div class="relative overflow-x-auto border border-zinc-200/80 rounded-xl sm:rounded-lg bg-white">
    <div
        class="flex px-3 md:px-4 flex-col md:flex-row space-y-4 md:space-y-0 items-start md:items-center justify-between py-3 md:py-7 bg-white dark:bg-gray-900">
      <DataSearchForm v-if="searchable" :placeholder="__('Search')"
                      :search-data-route-name="indexRouteName"/>

      <div class="flex gap-x-2 items-center">

        <LimitDropDown/>

        <div v-if="bulkSelectable">
          <button
              :class="[bulkSelect ? 'bg-red-50 text-red-500 hover:bg-red-500 focus:ring-red-200 border border-red-200': 'border border-blue-100 bg-blue-50 text-blue-500 hover:bg-blue-500 focus:ring-blue-200']"
              class="gap-x-2 hover:text-white  transition-all duration-200 py-2.5 px-5 text-sm inline-flex items-center focus:outline-none focus:ring-4 font-medium rounded-lg"
              @click="bulkSelect = !bulkSelect">
            <CursorArrowRaysIcon v-if="!bulkSelect" class="w-5"/>
            <span>{{ bulkSelect ? __('Cancel') : __('Bulk Select') }}</span>
          </button>
        </div>

        <Menu v-if="bulkSelect && bulkSelectable" as="div" class="relative inline-block text-left">
          <div>
            <MenuButton
                class="inline-flex items-center border border-blue-100 bg-blue-50 text-blue-500 hover:text-white transition-all duration-200 hover:bg-blue-500 py-2.5 px-5 text-sm focus:outline-none focus:ring-4 focus:ring-blue-200 font-medium rounded-lg">
              {{ __('Action') }}
              <ChevronDownIcon aria-hidden="true" class="w-2.5 h-2.5 ltr:ml-2.5 rtl:mr-2.5"/>
            </MenuButton>
          </div>

          <transition enter-active-class="transition ease-out duration-100"
                      enter-from-class="transform opacity-0 scale-95"
                      enter-to-class="transform opacity-100 scale-100"
                      leave-active-class="transition ease-in duration-75"
                      leave-from-class="transform opacity-100 scale-100"
                      leave-to-class="transform opacity-0 scale-95">
            <MenuItems
                class="absolute ltr:left-0 rtl:right-0 z-10 mt-2 w-56 ltr:origin-top-left rtl:origin-top-right rounded-md bg-white shadow-lg ring-opacity-5 focus:outline-none">
              <div class="py-1">
                <MenuItem v-if="exportable && bulkSelect && selected.length > 0" v-slot="{ active }"
                          @click="exportData()">
                  <a :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'rtl:text-right px-4 py-2 text-sm flex gap-x-2 items-center']"
                     href="#"
                     @click.prevent="exportData()">
                                                <span>
                                                    <ArrowUpOnSquareStackIcon class="w-4 h-4"/>
                                                </span>
                    <span v-html="__('Export <b>:length</b> selected items', {length: selected.length})">
                    </span>
                  </a>
                </MenuItem>

                <MenuItem v-if="exportable" v-slot="{ active }" @click="exportData(true)">
                  <a :class="[active ? 'bg-gray-50 text-gray-900' : 'text-gray-700', 'rtl:text-right px-4 py-2 text-sm flex gap-x-2 items-center']"
                     href="#export-all"
                     @click.prevent="exportData(true)">
                                                <span>
                                                    <ArrowUpOnSquareStackIcon class="w-4 h-4"/>
                                                </span>
                    <div>{{ __('Export All') }}</div>
                  </a>
                </MenuItem>

                <MenuItem v-if="deletable && bulkSelect && selected.length > 0" v-slot="{ active }"
                          :disabled="selected.length < 1"
                          @click="bulkDelete">
                  <a :class="[active ? 'bg-red-50 text-red-600' : 'text-red-600 hover:bg-red-50', 'rtl:text-right px-4 py-2 text-sm flex gap-x-2 items-center']"
                     :disabled="selected.length < 1" href="#"
                     @click.prevent="bulkDelete">
                    <span><TrashIcon class="w-4 h-4 stroke-current"/></span>
                    <div>{{ __('Delete') }} <span v-if="selected.length > 0">
                      <span v-html="__('<b>:length </b> selected item(s)', {length: selected.length})"></span></span>
                    </div>
                  </a>
                </MenuItem>
              </div>
            </MenuItems>
          </transition>
        </Menu>

        <div v-if="exportable">
          <PrimaryLightButton @click.prevent="exportData(true)">
            <ArrowUpOnSquareStackIcon class="w-5"/>
            <span>{{ __('Export') }}</span>
          </PrimaryLightButton>
        </div>

        <slot name="actions"></slot>

      </div>
    </div>

    <AlertInfo v-if="items.total < 1" :rounded="false">
      <div class="text-sm text-blue-700" v-text="__('No items found.')"></div>
    </AlertInfo>

    <table class="w-full table-auto text-sm divide-y divide-gray-200 text-start">
      <thead class="text-xs text-gray-700 uppercase divide-y divide-gray-200">
      <tr class="bg-gray-50">
        <th v-if="bulkSelect"
            class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 w-1"
            scope="col">
          <div v-tooltip="selectAllCheckedText" class="px-3 py-4">
            <label class="flex">
              <input id="checkbox-all-search" v-model="selectAllChecked" :class="{'bg-zinc-100': items.total < 1}"
                     :disabled="items.total < 1"
                     class="w-[20px] h-[20px] appearance-none rounded border border-gray-300 bg-white checked:border-blue-600 checked:bg-blue-600 indeterminate:border-blue-600 indeterminate:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"
                     type="checkbox">
              <label class="sr-only" for="checkbox-all-search">checkbox</label>
            </label>
          </div>
        </th>
        <slot name="thead"></slot>
        <SortableTableHead v-if="editable || deletable || $slots.tbodyActions"/>
      </tr>
      </thead>

      <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">

      <Deferred v-if="deferred" :data="['items']">

        <template #fallback>
          <div class="px-4 py-5 sm:px-6 space-y-2 w-full">
            <div v-for="(n, index) in 3"
                 :key="n" class="animate-pulse md:space-y-0 md:gap-x-8 rtl:gap-x-reverse md:flex md:articles-center"
                 role="status">
              <div class="w-full">
                <div
                    :class="{'w-80': index === 0, 'w-48' : index === 1, 'w-28': index === 2}"
                    class="h-2.5 bg-zinc-200/70 rounded-full dark:bg-zinc-700"></div>
              </div>
              <!-- w-80 w-48 w-28 -->
              <span class="sr-only">Loading...</span>
            </div>
          </div>
        </template>

        <tr v-for="(item, index) in items.data"
            :key="item.id"
            class="bg-white dark:bg-gray-800 hover:bg-blue-50/50 dark:hover:bg-gray-600 group transition duration-150">

          <td v-if="bulkSelect && bulkSelectable"
              class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 w-1">
            <div class="px-3 py-4">
              <label class="flex">
                <input :id="'id-' + item.id" v-model="selected" :value="item.id"
                       class="w-[20px] h-[20px] appearance-none rounded border border-gray-300 bg-white checked:border-blue-600 checked:bg-blue-600 indeterminate:border-blue-600 indeterminate:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:checked:bg-gray-100 forced-colors:appearance-auto"
                       type="checkbox">
                <label :for="'id-' + item.id" class="sr-only">checkbox</label>
              </label>
            </div>
          </td>

          <slot :index="index" :item="item" name="tbody"></slot>

          <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
            <div class="flex gap-x-1 items-center">

              <slot :index="index" :item="item" name="preActions"></slot>

              <Link v-if="editable" v-tooltip="__('Edit')"
                    :href="route(editItemRouteName, item[itemParam])" class="text-gray-500 hover:text-blue-600">
                <PencilSquareIcon class="w-5 h-5 stroke-current"/>
              </Link>

              <button v-if="deletable" v-tooltip="__('Delete')" class="text-gray-500 hover:text-red-600"
                      @click="deleteItem(item[itemParam])">
                <TrashIcon class="w-5 h-5 stroke-current"/>
              </button>

              <slot :index="index" :item="item" name="tbodyActions"></slot>
            </div>
          </td>
        </tr>
      </Deferred>

      </tbody>
    </table>

    <div class="mt-2 flex justify-between items-center p-2">
      <div class="flex flex-col space-y-2">
        <div class="my-4 text-xs text-gray-700" v-html="__('pagination.showing', {
              from: items.from ?? 0,
              to: items.to ?? 0,
              allCount: items.total ?? 0
            })">

        </div>
      </div>

      <Pagination :data="items" :data-route-name="indexRouteName"/>
    </div>
  </div>
</template>
