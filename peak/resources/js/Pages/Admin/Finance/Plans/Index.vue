<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {router} from '@inertiajs/vue3';
import PlansList from "@peak/Pages/Admin/Finance/Plans/List.vue";

const props = defineProps({
  items: Object,
  exportableData: Array
});

</script>

<template>
  <AdminLayout :description="__('Manage Subscription Plans')" :page-icon="false" :title="__('Plans')">
    <template v-slot:actions>
      <PrimaryButton :new-icon="true"
                     @click="router.visit(route('admin.finance.plans.create'))">
        {{ __('Create New Plan') }}
      </PrimaryButton>
    </template>

    <div class="mt-8">
      <plans-list :exportable-data="exportableData" :items="items" searchable/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
