<script setup>
import ApiTokenManager from '@/Themes/Breeze/Pages/Dashboard/Account/API/Partials/ApiTokenManager.vue';
import DashboardLayout from "@/Themes/Breeze/Layouts/Dashboard/DashboardLayout.vue";
import DashboardAccountLayout from "@/Themes/Breeze/Layouts/Dashboard/DashboardAccountLayout.vue";

defineProps({
  tokens: Array,
  availablePermissions: Array,
  defaultPermissions: Array,
});
</script>

<template>
  <DashboardLayout :description="__('Manage Your API Tokens')" :title="__('API Tokens')" :with-space="false">
    <DashboardAccountLayout>
      <div class="mx-auto max-w-2xl space-y-16 sm:space-y-20 lg:mx-0 lg:max-w-none">
        <ApiTokenManager
            :available-permissions="availablePermissions"
            :default-permissions="defaultPermissions"
            :tokens="tokens"
        />
      </div>
    </DashboardAccountLayout>
  </DashboardLayout>
</template>
