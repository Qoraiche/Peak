<script setup>

import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {__} from '@peak/Composables/useTranslate.js';
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import RoadmapsList from "@peak/Pages/Admin/Roadmaps/List.vue";
import {router} from '@inertiajs/vue3';

const props = defineProps({
  items: Object,
  exportableData: Array
});
</script>

<template>
  <AdminLayout :description="__('View current and upcoming features')" :page-icon="false" :title="__('Roadmaps')">
    <template v-slot:actions>
      <PrimaryButton :new-icon="true" @click="router.visit(route('admin.roadmaps.create'))">
        {{ __('New roadmap') }}
      </PrimaryButton>
    </template>

    <div class="mt-8">
      <roadmaps-list :exportable-data="exportableData" :items="items" deferred/>
    </div>
  </AdminLayout>
</template>

<style scoped></style>
