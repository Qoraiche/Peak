<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {router} from '@inertiajs/vue3';
import {__} from '@peak/Composables/useTranslate';
import DocsList from "@peak/Pages/Admin/Docs/List.vue";

const props = defineProps({
  items: Object,
  exportableData: Array
});
</script>

<template>
  <AdminLayout :description="__('Browse all documentation entries')" :page-icon="false" :title="__('Documentation')">
    <template v-slot:actions>
      <PrimaryButton :new-icon="true" @click="router.visit(route('admin.docs.create'))">
        {{ __('New Doc') }}
      </PrimaryButton>
    </template>

    <div class="mt-8">
      <docs-list :exportable-data="exportableData" :items="items" deferred/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
