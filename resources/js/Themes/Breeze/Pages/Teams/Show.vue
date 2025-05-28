<script setup>
import DeleteTeamForm from '@/Themes/Breeze/Pages/Teams/Partials/DeleteTeamForm.vue';
import SectionBorder from '@/Themes/Breeze/Components/SectionBorder.vue';
import TeamMemberManager from '@/Themes/Breeze/Pages/Teams/Partials/TeamMemberManager.vue';
import UpdateTeamNameForm from '@/Themes/Breeze/Pages/Teams/Partials/UpdateTeamNameForm.vue';
import DashboardLayout from "@/Themes/Breeze/Layouts/Dashboard/DashboardLayout.vue";
import DashboardAccountLayout from "@/Themes/Breeze/Layouts/Dashboard/DashboardAccountLayout.vue";

defineProps({
  team: Object,
  availableRoles: Array,
  permissions: Object,
});
</script>

<template>
  <DashboardLayout :description="__('Team Settings')" :title="__('Team')" :with-space="false">
    <DashboardAccountLayout>
      <UpdateTeamNameForm :permissions="permissions" :team="team"/>

      <TeamMemberManager
          :available-roles="availableRoles"
          :team="team"
          :user-permissions="permissions"
          class="mt-10 sm:mt-0"
      />

      <template v-if="permissions.canDeleteTeam && ! team.personal_team">
        <SectionBorder/>

        <DeleteTeamForm :team="team" class="mt-10 sm:mt-0"/>
      </template>
    </DashboardAccountLayout>
  </DashboardLayout>
</template>
