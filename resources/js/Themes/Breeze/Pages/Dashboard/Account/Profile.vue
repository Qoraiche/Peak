<script setup>
import DashboardLayout from "@/Themes/Breeze/Layouts/Dashboard/DashboardLayout.vue";
import DashboardAccountLayout from "@/Themes/Breeze/Layouts/Dashboard/DashboardAccountLayout.vue";
import UpdateProfileInformationForm from "@/Themes/Breeze/Pages/Dashboard/Partials/UpdateProfileInformationForm.vue";
</script>

<template>
  <DashboardLayout :description="__('Edit Your Profile Information')" :title="__('Profile')" :with-space="false">
    <DashboardAccountLayout>
      <div v-if="$page.props.front.profile_management_enabled" class="mx-auto max-w-2xl space-y-16 sm:space-y-20 lg:mx-0 lg:max-w-none">
        <UpdateProfileInformationForm :user="$page.props.auth.user"/>
      </div>
    </DashboardAccountLayout>
  </DashboardLayout>
</template>

<style scoped>
</style>
