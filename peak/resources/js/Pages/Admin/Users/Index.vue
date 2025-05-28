<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {router} from '@inertiajs/vue3';
import {__} from '@peak/Composables/useTranslate.js';
import UsersList from "@peak/Pages/Admin/Users/List.vue";

const props = defineProps({
  items: Object,
  exportableData: Array
});
</script>


<template>
  <AdminLayout :description="__('Users Management')" :page-icon="false" :title="__('Users')">
    <template v-slot:actions>
      <PrimaryButton :new-icon="true" @click="router.visit(route('admin.user-management.users.create'))">
        {{ __('New User') }}
      </PrimaryButton>
    </template>

    <div class="mt-8">
      <users-list :exportable-data="exportableData" :items="items" deferred/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
