<script setup>
import DashboardLayout from "@/Themes/Breeze/Layouts/Dashboard/DashboardLayout.vue";
import DashboardAccountLayout from "@/Themes/Breeze/Layouts/Dashboard/DashboardAccountLayout.vue";
import UpdatePasswordForm from "@/Themes/Breeze/Pages/Dashboard/Partials/UpdatePasswordForm.vue";
import TwoFactorAuthenticationForm from "@/Themes/Breeze/Pages/Dashboard/Partials/TwoFactorAuthenticationForm.vue";
import LogoutOtherBrowserSessionsForm
  from "@/Themes/Breeze/Pages/Dashboard/Partials/LogoutOtherBrowserSessionsForm.vue";

import SectionBorder from '@/Themes/Breeze/Components/SectionBorder.vue';
import DeleteUserForm from "@/Themes/Breeze/Pages/Dashboard/Partials/DeleteUserForm.vue";

defineProps({
  confirmsTwoFactorAuthentication: Boolean,
  sessions: Array,
});

</script>

<template>
  <DashboardLayout :description="__('Edit Your Account Security Settings')" :title="__('Security')" :with-space="false">
    <DashboardAccountLayout>

      <div class="pb-8">
        <div class="border-b border-gray-200 hidden lg:block">
          <nav aria-label="Tabs" class="-mb-px flex gap-x-8">
            <!-- Current: "border-blue-500 text-blue-600", Default: "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" -->
            <a v-if="$page.props.front.password_update_enabled"
               class="whitespace-nowrap border-b-2 border-transparent px-1 py-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
               href="#update-password">
              {{ __('Update Password') }}
            </a>
            <a class="whitespace-nowrap border-b-2 border-transparent px-1 py-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
               href="#2fa">
              {{ __('Two Factor Authentication') }}
            </a>
            <a v-if="$page.props.front.profile_browser_sessions_enabled" aria-current="page"
               class="whitespace-nowrap border-b-2 border-transparent px-1 py-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
               href="#logout-other-browser-sessions">
              {{ __('Browser Sessions') }}
            </a>
            <a v-if="$page.props.front.account_deletion_enabled" aria-current="page"
               class="whitespace-nowrap border-b-2 border-transparent px-1 py-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
               href="#delete-account">
              {{ __('Delete Account') }}
            </a>
          </nav>
        </div>
      </div>

      <div class="mx-auto max-w-2xl space-y-10 lg:space-y-0 lg:mx-0 lg:max-w-none">

        <div v-if="$page.props.front.password_update_enabled" id="update-password">
          <UpdatePasswordForm/>
        </div>
        <SectionBorder/>

        <div id="2fa">
          <TwoFactorAuthenticationForm
              :requires-confirmation="confirmsTwoFactorAuthentication"/>
        </div>

        <SectionBorder/>

        <div v-if="$page.props.front.profile_browser_sessions_enabled" id="logout-other-browser-sessions">
          <LogoutOtherBrowserSessionsForm :sessions="sessions" class="mt-10 sm:mt-0"/>
        </div>

        <SectionBorder v-if="$page.props.front.account_deletion_enabled"/>

        <div v-if="$page.props.front.account_deletion_enabled" id="delete-account">
          <DeleteUserForm/>
        </div>
      </div>
    </DashboardAccountLayout>
  </DashboardLayout>
</template>

<style scoped>
</style>
