<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import LanguagesList from "@peak/Pages/Admin/Languages/List.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {inject} from "vue";

const props = defineProps({
  items: Object,
  exportableData: Array
});

const emitter = inject('emitter');

const addNewLanguage = () => {
  emitter.emit('language:create');
};

</script>

<template>
  <AdminLayout :description="__('View and manage available languages')" :page-icon="false" :title="__('Languages')">
    <template v-slot:actions>
      <PrimaryButton :new-icon="true" @click="addNewLanguage">
        {{ __('New Language') }}
      </PrimaryButton>
    </template>

    <div class="mt-8">
      <languages-list :exportable-data="exportableData" :items="items" deferred/>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
