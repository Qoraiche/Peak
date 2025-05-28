<script setup>
import SortableTableHead from "@peak/Components/Admin/SortableTableHead.vue";
import TableCell from "@peak/Components/Admin/TableCell.vue";
import Badge from "@peak/Components/Admin/Badge.vue";
import BaseTable from "@peak/Components/Admin/BaseTable.vue";
import {__} from "@peak/Composables/useTranslate.js";
import {ChatBubbleLeftRightIcon} from "@heroicons/vue/24/outline/index.js";
import {Link} from "@inertiajs/vue3";

const props = defineProps({
  items: Object,
  exportable: {default: true, type: Boolean},
  searchable: {default: true, type: Boolean},
  bulkSelectable: {default: true, type: Boolean},
  exportableData: Array,
  deferred: {default: true, type: Boolean},
});

const exportableRouteName = 'admin.support.export.tickets';
const indexRouteName = 'admin.support.tickets.index';
const exportableType = 'tickets';
const destroyItemRouteName = 'admin.support.tickets.destroy';
const destroyBulkItemsRouteName = 'admin.support.tickets.bulk-destroy';
const editItemRouteName = 'admin.support.tickets.edit';
const showItemRouteName = 'admin.support.tickets.show';

const ticketStatusBadge = status => {
  switch (status) {
    case 'open':
      return 'info';
    case 'solved':
      return 'success';
    case 'pending':
      return 'alert';
    case 'closed':
      return 'danger';
    default:
      return 'default';
  }
};

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
              collection-param="ticket"
              deferred
              deletable exportable item-param="uuid" searchable>

    <template #thead>
      <SortableTableHead :sortable="true" :title="__('ID')" sort-key="id"/>
      <SortableTableHead :sortable="true" :title="__('Subject')" sort-key="subject"/>
      <SortableTableHead :sortable="true" :title="__('User')" sort-key="user_id"/>
      <SortableTableHead :sortable="true" :title="__('Status')" sort-key="status"/>
      <SortableTableHead :sortable="true" :title="__('Priority')" sort-key="priority"/>
      <SortableTableHead :title="__('Total Comments')"/>
      <SortableTableHead :sortable="true" :title="__('Created at')" sort-key="created_at"/>
    </template>

    <template #tbody="{ item }">
      <TableCell :href="route(showItemRouteName, item.uuid)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.id }}
                                </span>
        </div>
      </TableCell>

      <TableCell :href="route(showItemRouteName, item.uuid)">
        <div class="inline-flex items-center gap-1.5">
          <div class="text-sm">
            <div class="font-semibold">{{ item.subject }}</div>
            <div class="font-normal text-gray-700 text-xs">{{ __('Updated on :date', {date: item.updated_at}) }}</div>
          </div>
        </div>
      </TableCell>

      <TableCell :href="route(showItemRouteName, item.uuid)">
        <div class="flex items-center text-gray-900 whitespace-nowrap">
          <img :alt="item.user.name"
               :src="item.user.profile_photo_url" class="size-8 min-w-[2rem] rounded-full object-cover">
          <div class="ltr:pl-3 rtl:pr-3 text-sm">
            <div class="font-semibold">{{ item.user.name }}</div>
            <div class="font-normal text-gray-700 text-xs">{{ item.user.email }}</div>
          </div>
        </div>
      </TableCell>

      <TableCell :href="route(showItemRouteName, item.uuid)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    <Badge :type="ticketStatusBadge(item.status_name)">
                                        {{ __(item.status_name) }}
                                    </Badge>
                                </span>
        </div>
      </TableCell>

      <TableCell :href="route(showItemRouteName, item.uuid)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 capitalize text-gray-950">
                                    {{ item.priority_name }}
                                </span>
        </div>
      </TableCell>

      <TableCell :href="route(showItemRouteName, item.uuid)">
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.comments_count }}
                                </span>
        </div>
      </TableCell>

      <TableCell :href="route(showItemRouteName, item.uuid)">
        <div class="inline-flex items-center gap-1.5">
                    <span class="text-sm leading-6 text-gray-950">
                        {{ item.created_at }}
                    </span>
        </div>
      </TableCell>
    </template>

    <template #preActions="{item}">
      <Link v-tooltip="__('View')"
            :href="route(showItemRouteName, item.uuid)" class="text-gray-500 hover:text-blue-600">
        <ChatBubbleLeftRightIcon class="h-5 w-5"/>
      </Link>
    </template>
  </base-table>
</template>

<style scoped>

</style>
