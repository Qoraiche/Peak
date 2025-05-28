<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import ExportsList from "@peak/Pages/Admin/Exports/List.vue";

const props = defineProps({
  items: Object,
  exportableData: Array
});

</script>

<template>

  <AdminLayout :description="__('View available data exports')" :page-icon="false" :title="__('Exports')">
    <div class="mt-8">
      <exports-list :exportable="false" :exportable-data="exportableData" :items="items" deferred/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
