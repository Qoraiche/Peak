<script setup>

import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {__} from '@peak/Composables/useTranslate.js';
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PostsList from "@peak/Pages/Admin/Blog/List.vue";
import {router} from '@inertiajs/vue3';

const props = defineProps({
  items: Object,
  exportableData: Array
});

</script>

<template>
  <AdminLayout :description="__('Browse all blog posts')" :page-icon="false" :title="__('Blog Posts')">
    <template v-slot:actions>
      <PrimaryButton :new-icon="true" @click="router.visit(route('admin.blog.articles.create'))">
        {{ __('New Post') }}
      </PrimaryButton>
    </template>

    <div class="mt-8">
      <posts-list :exportable-data="exportableData" :items="items" deferred/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
