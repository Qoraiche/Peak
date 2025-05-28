<script setup>
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import AdminCards from "@peak/Layouts/Admin/Components/AdminCards.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminWidgets from "@peak/Layouts/Admin/Components/AdminWidgets.vue";
import {router} from "@inertiajs/vue3";
import {useFeature} from "@peak/Composables/useFeature.js";

const {featureEnabled} = useFeature();
</script>

<template>
  <AdminLayout :description="__('Your at-a-glance snapshot of key performance indicators, stats, and recent activity.')"
               :page-icon="false"
               :title="__('Administration')">
    <template v-slot:actions>

    </template>

    <div class="flex flex-col space-y-6">
      <!--Add more content here when needed-->
      <AdminWidgets/>
      <AdminCards/>
      <!--Add more content here when needed-->
    </div>
  </AdminLayout>
</template>
