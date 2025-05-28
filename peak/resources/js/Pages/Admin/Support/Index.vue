<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {router} from '@inertiajs/vue3';
import {__} from '@peak/Composables/useTranslate.js';
import TicketsList from "@peak/Pages/Admin/Support/List.vue";

const props = defineProps({
  items: Object,
  exportableData: Array
});
</script>

<template>
  <AdminLayout :description="__('View all user support tickets')" :page-icon="false" :title="__('Support Tickets')">
    <template v-slot:actions>
      <PrimaryButton :new-icon="true" @click="router.visit(route('admin.support.tickets.create'))">
        {{ __('New Support Ticket') }}
      </PrimaryButton>
    </template>

    <div class="mt-8">
      <tickets-list :exportable-data="exportableData" :items="items" deferred/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
