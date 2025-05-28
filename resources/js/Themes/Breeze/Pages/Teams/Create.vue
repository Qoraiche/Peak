<script setup>
import CreateTeamForm from '@/Themes/Breeze/Pages/Teams/Partials/CreateTeamForm.vue';
import DashboardLayout from "@/Themes/Breeze/Layouts/Dashboard/DashboardLayout.vue";
import DashboardAccountLayout from "@/Themes/Breeze/Layouts/Dashboard/DashboardAccountLayout.vue";
</script>

<template>
  <DashboardLayout :description="__('Create New Team')" :title="__('Create Team')" :with-space="false">
    <DashboardAccountLayout>
      <CreateTeamForm/>
    </DashboardAccountLayout>
  </DashboardLayout>
</template>
