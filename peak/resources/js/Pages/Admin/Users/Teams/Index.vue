<script setup>
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import TeamsList from "@peak/Pages/Admin/Users/Teams/List.vue";
import {inject} from "vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
  items: Object,
  permissions: Object,
  exportableData: Array
});

const emitter = inject('emitter');

const createItem = () => {
  router.visit(route('admin.user-management.teams.create'));
};
</script>

<template>
  <AdminLayout :description="__('Manage Teams')" :page-icon="false" :title="__('Teams')">
    <template v-slot:actions>
      <PrimaryButton :new-icon="true" @click="createItem">
        {{ __('New Team') }}
      </PrimaryButton>
    </template>

    <div class="mt-8">
      <teams-list :exportable-data="exportableData" :items="items"/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
