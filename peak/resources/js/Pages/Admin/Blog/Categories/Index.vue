<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {__} from '@peak/Composables/useTranslate.js';
import BlogCategoriesList from "@peak/Pages/Admin/Blog/Categories/List.vue";
import {inject} from "vue";

const props = defineProps({
  items: Object,
  exportableData: Array
});

const emitter = inject('emitter');

const newCategory = () => {
  emitter.emit('blog-category:create');
};

</script>

<template>
  <AdminLayout :description="__('View all blog categories')" :page-icon="false" :title="__('Blog Categories')">
    <template v-slot:actions>
      <PrimaryButton :new-icon="true" @click="newCategory">
        {{ __('New blog category') }}
      </PrimaryButton>
    </template>

    <div class="mt-8">
      <blog-categories-list :exportable-data="exportableData" :items="items" deferred/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
