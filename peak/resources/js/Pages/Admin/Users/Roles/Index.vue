<script setup>
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import RolesList from "@peak/Pages/Admin/Users/Roles/List.vue";
import {inject} from "vue";

const props = defineProps({
  items: Object,
  permissions: Object,
  exportableData: Array
});

const emitter = inject('emitter');

const createItem = () => {
  emitter.emit('role:manage', {
    type: 'create',
    permissions: props.permissions
  });
};
</script>

<template>
  <AdminLayout :description="__('Manage App Roles')" :page-icon="false" :title="__('Roles')">
    <template v-slot:actions>
      <PrimaryButton :new-icon="true" @click="createItem">
        {{ __('New Role') }}
      </PrimaryButton>
    </template>

    <div class="mt-8">
      <roles-list :exportable-data="exportableData" :items="items" :permissions="permissions"/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
