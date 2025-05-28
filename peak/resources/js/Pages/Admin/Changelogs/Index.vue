<script setup>

import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {__} from '@peak/Composables/useTranslate.js';
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import ChangelogsList from "@peak/Pages/Admin/Changelogs/List.vue";
import {router} from '@inertiajs/vue3';

const props = defineProps({
  items: Object,
  exportableData: Array
});
</script>

<template>
  <AdminLayout :description="__('View the list of product updates and changes')" :page-icon="false"
               :title="__('Changelogs')">
    <template v-slot:actions>
      <PrimaryButton :new-icon="true" @click="router.visit(route('admin.changelogs.create'))">
        {{ __('New changelog') }}
      </PrimaryButton>
    </template>

    <div class="mt-8">
      <changelogs-list :exportable-data="exportableData" :items="items" deferred/>
    </div>
  </AdminLayout>
</template>

<style scoped></style>
