<script setup>
import {ref} from 'vue';
import {router, useForm, usePage} from '@inertiajs/vue3';
import ActionMessage from '@/Themes/Breeze/Components/ActionMessage.vue';
import ActionSection from '@/Themes/Breeze/Components/ActionSection.vue';
import ConfirmationModal from '@/Themes/Breeze/Components/ConfirmationModal.vue';
import DangerButton from '@/Themes/Breeze/Components/DangerButton.vue';
import DialogModal from '@/Themes/Breeze/Components/DialogModal.vue';
import FormSection from '@/Themes/Breeze/Components/FormSection.vue';
import InputError from '@/Themes/Breeze/Components/InputError.vue';
import InputLabel from '@/Themes/Breeze/Components/InputLabel.vue';
import PrimaryButton from '@/Themes/Breeze/Components/PrimaryButton.vue';
import SecondaryButton from '@/Themes/Breeze/Components/SecondaryButton.vue';
import SectionBorder from '@/Themes/Breeze/Components/SectionBorder.vue';
import TextInput from '@/Themes/Breeze/Components/TextInput.vue';
import {useConfirm} from "@peak/Composables/useConfirm.js";
import {__} from "@peak/Composables/useTranslate.js";

const props = defineProps({
  team: Object,
  availableRoles: Array,
  userPermissions: Object,
});

const page = usePage();

const addTeamMemberForm = useForm({
  email: '',
  role: null,
});

const updateRoleForm = useForm({
  role: null,
});

const leaveTeamForm = useForm({});
const removeTeamMemberForm = useForm({});

const currentlyManagingRole = ref(false);
const managingRoleFor = ref(null);
const confirmingLeavingTeam = ref(false);
const teamMemberBeingRemoved = ref(null);

const addTeamMember = () => {
  addTeamMemberForm.post(route('team-members.store', props.team), {
    errorBag: 'addTeamMember',
    preserveScroll: true,
    onSuccess: () => addTeamMemberForm.reset(),
  });
};

const cancelTeamInvitation = async (invitation) => {
  const confirmed = await useConfirm({
    title: __("Cancel Team Invitation?"),
    text: __("Are you sure you want to cancel this team member invitation?"),
    confirmButtonText: __("Yes, cancel it"),
  });

  if (confirmed) {
    router.delete(route('team-invitations.destroy', invitation), {
      preserveScroll: true,
    });
  }
};

const manageRole = (teamMember) => {
  managingRoleFor.value = teamMember;
  updateRoleForm.role = teamMember.membership.role;
  currentlyManagingRole.value = true;
};

const updateRole = () => {
  updateRoleForm.put(route('team-members.update', [props.team, managingRoleFor.value]), {
    preserveScroll: true,
    onSuccess: () => currentlyManagingRole.value = false,
  });
};

const confirmLeavingTeam = () => {
  confirmingLeavingTeam.value = true;
};

const leaveTeam = () => {
  leaveTeamForm.delete(route('team-members.destroy', [props.team, page.props.auth.user]));
};

const confirmTeamMemberRemoval = (teamMember) => {
  teamMemberBeingRemoved.value = teamMember;
};

const removeTeamMember = async () => {
  const confirmed = await useConfirm({
    title: __("Delete Team member?"),
    text: __("Are you sure you want to delete this team member? This cannot be undone."),
    confirmButtonText: __("Yes, delete it"),
  });

  if (confirmed) {
    removeTeamMemberForm.delete(route('team-members.destroy', [props.team, teamMemberBeingRemoved.value]), {
      errorBag: 'removeTeamMember',
      preserveScroll: true,
      preserveState: true,
      onSuccess: () => {
        teamMemberBeingRemoved.value = null;
        toast.success(__('Team member removed'))
      },
    });
  }
};

const displayableRole = (role) => {
  return props.availableRoles.find(r => r.key === role).name;
};
</script>

<template>
  <div>
    <div v-if="userPermissions.canAddTeamMembers">
      <SectionBorder/>

      <!-- Add Team Member -->
      <FormSection @submitted="addTeamMember">
        <template #title>
          {{ __('Add Team Member') }}
        </template>

        <template #description>
          {{ __('Add a new team member to your team, allowing them to collaborate with you.') }}
        </template>

        <template #form>
          <div class="col-span-6">
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
              {{ __('Please provide the email address of the person you would like to add to this team.') }}
            </div>
          </div>

          <!-- Member Email -->
          <div class="col-span-6 sm:col-span-4">
            <InputLabel :value="__('Email')" for="email"/>
            <TextInput
                id="email"
                v-model="addTeamMemberForm.email"
                class="mt-1 block w-full"
                type="email"
            />
            <InputError :message="addTeamMemberForm.errors.email" class="mt-2"/>
          </div>

          <!-- Role -->
          <div v-if="availableRoles.length > 0" class="col-span-6 lg:col-span-4">
            <InputLabel :value="__('Role')" for="roles"/>
            <InputError :message="addTeamMemberForm.errors.role" class="mt-2"/>

            <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
              <button
                  v-for="(role, i) in availableRoles"
                  :key="role.key"
                  :class="{'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none': i > 0, 'rounded-b-none': i != Object.keys(availableRoles).length - 1}"
                  class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-hidden focus:border-blue-500 dark:focus:border-blue-600 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600"
                  type="button"
                  @click="addTeamMemberForm.role = role.key"
              >
                <div :class="{'opacity-50': addTeamMemberForm.role && addTeamMemberForm.role != role.key}">
                  <!-- Role Name -->
                  <div class="flex items-center">
                    <div :class="{'font-semibold': addTeamMemberForm.role == role.key}"
                         class="text-sm text-gray-600 dark:text-gray-400">
                      {{ role.name }}
                    </div>

                    <svg v-if="addTeamMemberForm.role == role.key" class="ms-2 size-5 text-green-400"
                         fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                      <path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                            stroke-linejoin="round"/>
                    </svg>
                  </div>

                  <!-- Role Description -->
                  <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 text-start">
                    {{ role.description }}
                  </div>
                </div>
              </button>
            </div>
          </div>
        </template>

        <template #actions>
          <ActionMessage :on="addTeamMemberForm.recentlySuccessful" class="me-3">
            {{ __('Added.') }}
          </ActionMessage>

          <PrimaryButton :class="{ 'opacity-25': addTeamMemberForm.processing }"
                         :disabled="addTeamMemberForm.processing">
            {{ __('Add') }}
          </PrimaryButton>
        </template>
      </FormSection>
    </div>

    <div v-if="team.team_invitations.length > 0 && userPermissions.canAddTeamMembers">
      <SectionBorder/>

      <!-- Team Member Invitations -->
      <ActionSection class="mt-10 sm:mt-0">
        <template #title>
          {{ __('Pending Team Invitations') }}
        </template>

        <template #description>
          {{
            __('These people have been invited to your team and have been sent an invitation email. They may join the team by accepting the email invitation.')
          }}
        </template>

        <!-- Pending Team Member Invitation List -->
        <template #content>
          <div class="space-y-6">
            <div v-for="invitation in team.team_invitations" :key="invitation.id"
                 class="flex items-center justify-between">
              <div class="text-gray-600 dark:text-gray-400">
                {{ invitation.email }}
              </div>

              <div class="flex items-center">
                <!-- Cancel Team Invitation -->
                <button
                    v-if="userPermissions.canRemoveTeamMembers"
                    class="cursor-pointer ms-6 text-sm text-red-500 focus:outline-hidden"
                    @click="cancelTeamInvitation(invitation)"
                >
                  {{ __('Cancel') }}
                </button>
              </div>
            </div>
          </div>
        </template>
      </ActionSection>
    </div>

    <div v-if="team.users.length > 0">
      <SectionBorder/>

      <!-- Manage Team Members -->
      <ActionSection class="mt-10 sm:mt-0">
        <template #title>
          {{ __('Team Members') }}
        </template>

        <template #description>
          {{ __('All of the people that are part of this team.') }}
        </template>

        <!-- Team Member List -->
        <template #content>
          <div class="space-y-6">
            <div v-for="user in team.users" :key="user.id" class="flex items-center justify-between">
              <div class="flex items-center">
                <img :alt="user.name" :src="user.profile_photo_url" class="size-8 rounded-full object-cover">
                <div class="ms-4 dark:text-white">
                  {{ user.name }}
                </div>
              </div>

              <div class="flex items-center">
                <!-- Manage Team Member Role -->
                <button
                    v-if="userPermissions.canUpdateTeamMembers && availableRoles.length"
                    class="ms-2 text-sm text-gray-400 underline"
                    @click="manageRole(user)"
                >
                  {{ displayableRole(user.membership.role) }}
                </button>

                <div v-else-if="availableRoles.length" class="ms-2 text-sm text-gray-400">
                  {{ displayableRole(user.membership.role) }}
                </div>

                <!-- Leave Team -->
                <button
                    v-if="$page.props.auth.user.id === user.id"
                    class="cursor-pointer ms-6 text-sm text-red-500"
                    @click="confirmLeavingTeam"
                >
                  {{ __('Leave') }}
                </button>

                <!-- Remove Team Member -->
                <button
                    v-else-if="userPermissions.canRemoveTeamMembers"
                    class="cursor-pointer ms-6 text-sm text-red-500"
                    @click="confirmTeamMemberRemoval(user)"
                >
                  {{ __('Remove') }}
                </button>
              </div>
            </div>
          </div>
        </template>
      </ActionSection>
    </div>

    <!-- Role Management Modal -->
    <DialogModal :show="currentlyManagingRole" @close="currentlyManagingRole = false">
      <template #title>
        {{ __('Manage Role') }}
      </template>

      <template #content>
        <div v-if="managingRoleFor">
          <div class="relative z-0 mt-1 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer">
            <button
                v-for="(role, i) in availableRoles"
                :key="role.key"
                :class="{'border-t border-gray-200 dark:border-gray-700 focus:border-none rounded-t-none': i > 0, 'rounded-b-none': i !== Object.keys(availableRoles).length - 1}"
                class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-hidden focus:border-blue-500 dark:focus:border-blue-600 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600"
                type="button"
                @click="updateRoleForm.role = role.key"
            >
              <div :class="{'opacity-50': updateRoleForm.role && updateRoleForm.role !== role.key}">
                <!-- Role Name -->
                <div class="flex items-center">
                  <div :class="{'font-semibold': updateRoleForm.role === role.key}"
                       class="text-sm text-gray-600 dark:text-gray-400">
                    {{ role.name }}
                  </div>

                  <svg v-if="updateRoleForm.role == role.key" class="ms-2 size-5 text-green-400"
                       fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                       xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                          stroke-linejoin="round"/>
                  </svg>
                </div>

                <!-- Role Description -->
                <div class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                  {{ role.description }}
                </div>
              </div>
            </button>
          </div>
        </div>
      </template>

      <template #footer>
        <SecondaryButton @click="currentlyManagingRole = false">
          {{ __('Cancel') }}
        </SecondaryButton>

        <PrimaryButton
            :class="{ 'opacity-25': updateRoleForm.processing }"
            :disabled="updateRoleForm.processing"
            class="ms-3"
            @click="updateRole"
        >
          {{ __('Save') }}
        </PrimaryButton>
      </template>
    </DialogModal>

    <!-- Leave Team Confirmation Modal -->
    <ConfirmationModal :show="confirmingLeavingTeam" @close="confirmingLeavingTeam = false">
      <template #title>
        {{ __('Leave Team') }}
      </template>

      <template #content>
        {{ __('Are you sure you would like to leave this team?') }}
      </template>

      <template #footer>
        <SecondaryButton @click="confirmingLeavingTeam = false">
          {{ __('Cancel') }}
        </SecondaryButton>

        <DangerButton
            :class="{ 'opacity-25': leaveTeamForm.processing }"
            :disabled="leaveTeamForm.processing"
            class="ms-3"
            @click="leaveTeam"
        >
          {{ __('Leave') }}
        </DangerButton>
      </template>
    </ConfirmationModal>

    <!-- Remove Team Member Confirmation Modal -->
    <ConfirmationModal :show="teamMemberBeingRemoved" @close="teamMemberBeingRemoved = null">
      <template #title>
        {{ __('Remove Team Member') }}
      </template>

      <template #content>
        {{ __('Are you sure you would like to remove this person from the team?') }}
      </template>

      <template #footer>
        <SecondaryButton @click="teamMemberBeingRemoved = null">
          {{ __('Cancel') }}
        </SecondaryButton>

        <DangerButton
            :class="{ 'opacity-25': removeTeamMemberForm.processing }"
            :disabled="removeTeamMemberForm.processing"
            class="ms-3"
            @click="removeTeamMember"
        >
          {{ __('Remove') }}
        </DangerButton>
      </template>
    </ConfirmationModal>
  </div>
</template>
