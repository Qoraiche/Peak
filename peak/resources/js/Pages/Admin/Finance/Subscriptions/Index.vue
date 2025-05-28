<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {__} from '@peak/Composables/useTranslate.js';
import SubscriptionsList from "@peak/Pages/Admin/Finance/Subscriptions/List.vue";

const props = defineProps({
  items: Object,
  exportableData: Array
});
</script>

<template>
  <AdminLayout :description="__('List all active and cancelled subscriptions.')" :page-icon="false"
               :title="__('Subscriptions')">
    <div class="mt-8">
      <subscriptions-list :exportable-data="exportableData" :items="items" deferred/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
