<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {__} from '@peak/Composables/useTranslate';
import ReferralsList from "@peak/Pages/Admin/Referrals/List.vue";

const props = defineProps({
  items: Object,
  exportableData: Array
});
</script>

<template>
  <AdminLayout :description="__('View user referral statistics and history')" :page-icon="false"
               :title="__('Referrals')">
    <div class="mt-8">
      <referrals-list :exportable-data="exportableData" :items="items" deferred/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
