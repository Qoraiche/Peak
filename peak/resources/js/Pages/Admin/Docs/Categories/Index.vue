<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {__} from '@peak/Composables/useTranslate.js';
import DocsCategoriesList from "@peak/Pages/Admin/Docs/Categories/List.vue";
import {inject} from "vue";

const props = defineProps({
  items: Object,
  exportableData: Array
});

const emitter = inject('emitter');

const newCategory = () => {
  emitter.emit('doc-category:create');
}
</script>

<template>
  <AdminLayout :description="__('View all docs categories')" :page-icon="false" :title="__('Docs Categories')">
    <template v-slot:actions>
      <PrimaryButton :new-icon="true" @click="newCategory">
        {{ __('New Docs Category') }}
      </PrimaryButton>
    </template>

    <div class="mt-8">
      <docs-categories-list :exportable-data="exportableData" :items="items" deferred/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
