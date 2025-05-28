<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import PermissionsList from "@peak/Pages/Admin/Users/Permissions/List.vue";
import {inject} from "vue";

const props = defineProps({
  items: Object,
  permissions: Object,
  exportableData: Array
});

const emitter = inject('emitter');

const createItem = () => {
  emitter.emit('permission:manage', {
    type: 'create'
  });
};
</script>

<template>
  <AdminLayout :description="__('Manage App Permissions')" :page-icon="false" :title="__('Permissions')">
    <template v-slot:actions>
      <PrimaryButton :new-icon="true" @click="createItem">
        {{ __('New Permission') }}
      </PrimaryButton>
    </template>

    <div class="mt-8">
      <permissions-list :exportable-data="exportableData" :items="items" :permissions="permissions"/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
