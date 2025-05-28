<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {router} from '@inertiajs/vue3';
import {__} from '@peak/Composables/useTranslate';
import PagesList from "@peak/Pages/Admin/Pages/List.vue";

const props = defineProps({
  items: Object,
  exportableData: Array
});
</script>

<template>
  <AdminLayout :description="__('View and manage website pages')" :page-icon="false" :title="__('Pages')">
    <template v-slot:actions>
      <PrimaryButton :new-icon="true" @click="router.visit(route('admin.pages.create'))">
        {{ __('New page') }}
      </PrimaryButton>
    </template>

    <div class="mt-8">
      <pages-list :exportable-data="exportableData" :items="items" deferred/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
