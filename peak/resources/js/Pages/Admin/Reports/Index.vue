<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {__} from '@peak/Composables/useTranslate.js';
import ReportsList from "@peak/Pages/Admin/Reports/List.vue";

const props = defineProps({
  items: Object,
  exportableData: Array
});
</script>

<template>
  <AdminLayout :description="__('View all user reports')" :page-icon="false" :title="__('Reports')">
    <!--    <template v-slot:actions>
          <PrimaryButton :new-icon="true" @click="router.visit(route('admin.support.tickets.create'))">
            {{ __('New Support Ticket') }}
          </PrimaryButton>
        </template>-->

    <div class="mt-8">
      <reports-list :exportable-data="exportableData" :items="items" deferred/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
