<script setup>
import SortableTableHead from "@peak/Components/Admin/SortableTableHead.vue";
import TableCell from "@peak/Components/Admin/TableCell.vue";
import BaseTable from "@peak/Components/Admin/BaseTable.vue";
import {router} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";
import {CheckCircle, Clock2, HandCoins} from "lucide-vue-next";
import Badge from "@peak/Components/Admin/Badge.vue";
import {__} from "@peak/Composables/useTranslate.js";

const props = defineProps({
  items: Object,
  exportable: {default: true, type: Boolean},
  searchable: {default: true, type: Boolean},
  bulkSelectable: {default: true, type: Boolean},
  exportableData: Array,
  deferred: {default: true, type: Boolean}
});

const exportableRouteName = 'admin.referrals.export';
const indexRouteName = 'admin.referrals.index';
const exportableType = 'referrals';
const destroyItemRouteName = 'admin.referrals.destroy';
const destroyBulkItemsRouteName = 'admin.referrals.bulk-destroy';

const toast = useToast();

const setApproved = id => {
  router.patch(route('admin.referrals.approve', id), {}, {
    onSuccess: () => {
      toast.success(__('Referral approved'));
    }
  });
}

const setPending = id => {
  router.patch(route('admin.referrals.pending', id), {}, {
    onSuccess: () => {
      toast.success(__('Referral pending'));
    }
  });
}

const setPaid = id => {
  router.patch(route('admin.referrals.pay', id), {}, {
    onSuccess: () => {
      toast.success(__('Referral paid'));
    }
  });
}


</script>

<template>
  <base-table :bulk-destroy-route="destroyBulkItemsRouteName"
              :deferred="deferred"
              :destroy-route="destroyItemRouteName"
              :editable="false"
              :export-route="exportableRouteName"
              :exportable="exportable"
              :exportable-data="exportableData"
              :index-route="indexRouteName"
              :items="items"
              :searchable="searchable"
              collection-param="referral"
              deletable>
    <template #thead>
      <SortableTableHead :sortable="true" :title="__('ID')" sort-key="id"/>
      <SortableTableHead :sortable="true" :title="__('User')" sort-key="referrer_id"/>
      <SortableTableHead :sortable="true" :title="__('Referred By')" sort-key="referred_id"/>
      <SortableTableHead :sortable="true" :title="__('Status')" sort-key="status"/>
      <SortableTableHead :sortable="true" :title="__('Pending Reward')" sort-key="pending_reward"/>
      <SortableTableHead :sortable="true" :title="__('Paid Reward')" sort-key="paid_reward"/>
      <SortableTableHead :sortable="true" :title="__('Referred at')" sort-key="created_at"/>
    </template>

    <template #tbody="{ item }">
      <TableCell>
        <div class="inline-flex items-center gap-1.5">
                                <span class="text-sm leading-6 text-gray-950">
                                    {{ item.id }}
                                </span>
        </div>
      </TableCell>

      <TableCell :href="route('admin.user-management.users.edit', item.referrer.id)">
        <div class="flex items-center text-gray-900 whitespace-nowrap">
          <img :alt="item.referrer.name"
               :src="item.referrer.profile_photo_url" class="size-8 min-w-[2rem] rounded-full object-cover">
          <div class="ltr:pl-3 rtl:pr-3 text-sm">
            <div class="font-semibold">{{ item.referrer.name }}</div>
            <div class="font-normal text-gray-700 text-xs">{{ item.referrer.email }}</div>
          </div>
        </div>
      </TableCell>

      <TableCell :href="route('admin.user-management.users.edit', item.referred.id)">
        <div class="flex items-center text-gray-900 whitespace-nowrap">
          <img :alt="item.referred.name"
               :src="item.referred.profile_photo_url" class="size-8 min-w-[2rem] rounded-full object-cover">
          <div class="ltr:pl-3 rtl:pr-3 text-sm">
            <div class="font-semibold">{{ item.referred.name }}</div>
            <div class="font-normal text-gray-700 text-xs">{{ item.referred.email }}</div>
          </div>
        </div>
      </TableCell>

      <TableCell>
        <div class="inline-flex items-center gap-1.5">
          <Badge v-if="item.status === 'paid'" type="success">
            {{ __('Paid') }}
          </Badge>

          <Badge v-if="item.status === 'pending'">
            {{ __('Pending') }}
          </Badge>

          <Badge v-if="item.status === 'approved'" type="info">
            {{ __('Approved') }}
          </Badge>
        </div>
      </TableCell>

      <TableCell>
        <div class="inline-flex items-center gap-1.5">
                    <span class="text-sm leading-6 text-gray-950">
                        {{ item.pending_reward }}
                    </span>
        </div>
      </TableCell>

      <TableCell>
        <div class="inline-flex items-center gap-1.5">
          <span class="text-sm leading-6 text-gray-950">
            {{ item.paid_reward }}
          </span>
        </div>
      </TableCell>

      <TableCell>
        <div class="inline-flex items-center gap-1.5">
          <span class="text-sm leading-6 text-gray-950">
            {{ item.created_at }}
          </span>
        </div>
      </TableCell>
    </template>

    <template #tbodyActions="{item}">
      <button v-if="item.status === 'pending'" v-tooltip="__('Approve')" class="hover:text-blue-600"
              @click="setApproved(item.id)">
        <CheckCircle class="w-5 h-5 stroke-current"/>
      </button>

      <button v-if="item.status === 'paid' || item.status === 'approved'" v-tooltip="__('Pending')"
              class="hover:text-blue-600"
              @click="setPending(item.id)">
        <Clock2 class="w-5 h-5 stroke-current"/>
      </button>

      <button v-if="item.status === 'approved'" v-tooltip="__('Pay')" class="hover:text-blue-600"
              @click="setPaid(item.id)">
        <HandCoins class="w-5 h-5 stroke-current"/>
      </button>
    </template>
  </base-table>
</template>

<style scoped>

</style>
