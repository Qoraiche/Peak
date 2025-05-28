<script setup>

import Card from "@peak/Components/Admin/Card.vue";
import Input from "@peak/Components/Admin/Input.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import InputError from "@peak/Layouts/Admin/Components/InputError.vue";
import SelectUser from "@peak/Layouts/Admin/Components/SelectUser.vue";
import {router, useForm} from "@inertiajs/vue3";
import {computed, defineProps, ref} from "vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";
import DangerButton from "@peak/Components/Admin/DangerButton.vue";
import InputLabel from "@/Themes/Breeze/Components/InputLabel.vue";
import {useConfirm} from "@peak/Composables/useConfirm.js";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import ConfirmationModal from "@peak/Components/Admin/ConfirmationModal.vue";
import DialogModal from "@peak/Components/Admin/DialogModal.vue";

const props = defineProps({
  team: Object,
  availableRoles: Array,
  userPermissions: Object,
});

const owner = props.team.owner;

const form = useForm({
  owner: owner?.id,
  name: props.team.name,
});

const toast = useToast();

const submit = () => {
  form.put(route('admin.user-management.teams.update', props.team), {
    onSuccess: () => {
      toast.success(__('Team updated'));
    }
  });
};

const userSelected = (user) => {
  form.owner = user.id;
};

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
    onSuccess: () => {
      addTeamMemberForm.reset();
      toast.success(__('Team member added'));
    },
  });
};

const cancelTeamInvitation = async (invitation) => {
  const confirmed = await useConfirm({
    title: __("Cancel team invitation?"),
    text: __("Are you sure you want to cancel this team invitation? This cannot be undone."),
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

const removeTeamMember = () => {
    removeTeamMemberForm.delete(route('team-members.destroy', [props.team, teamMemberBeingRemoved.value]), {
      errorBag: 'removeTeamMember',
      preserveScroll: true,
      preserveState: true,
      onSuccess: () => teamMemberBeingRemoved.value = null,
    });
};

const displayableRole = (role) => {
  return props.availableRoles.find(r => r.key === role).name;
};

const deleteTeam = async () => {
  const confirmed = await useConfirm({
    title: __("Delete team?"),
    text: __("Are you sure you want to delete this team? This cannot be undone."),
    confirmButtonText: __("Yes, delete it"),
  });

  if (confirmed) {
    useForm({}).delete(route('admin.user-management.teams.destroy', props.team.id), {
      onSuccess: () => {
        toast.success(__('Team deleted'));
      },
    });
  }
};

</script>

<template>
  <AdminLayout :description="__('Edit Team')" :page-icon="false" :title="__('Edit Team')">
    <div class="grid max-w-3xl grid-cols-1 gap-6 mx-auto mt-8 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
      <div class="space-y-6 lg:col-span-2 lg:col-start-1">
        <Card :has-error="form.errors?.length > 0">
          <template #header>
            {{ __('Team Details') }}
          </template>

          <template #description>
            {{ __('Edit team details.') }}
          </template>

          <template #footer>
            <PrimaryButton :class="{ 'opacity-75': form.processing || !form.isDirty }"
                           :disabled="form.processing || !form.isDirty"
                           :loading="form.processing"
                           @click="submit">
              {{ __('Save') }}
            </PrimaryButton>
          </template>

          <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="space-y-3 sm:col-span-2">
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Team Name') }}
                <span class="text-red-600">*</span>
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <Input id="name" v-model="form.name" class="w-full"/>
                <InputError :message="form.errors?.name" class="mt-2"/>
              </dd>
            </div>

            <div class="space-y-3 sm:col-span-2">
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Owner') }}
                <span class="text-red-600">*</span>
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <SelectUser :limit="20"
                            :pre-selected-user="owner"
                            :roles="$page.props.config.admin_roles"
                            @userSelected="userSelected"/>

                <InputError :message="form.errors?.owner" class="mt-2"/>
              </dd>
            </div>
          </dl>
        </Card>

        <Card :has-error="form.errors?.length > 0">
          <template #header>
            {{ __('Add Team Member') }}
          </template>

          <template #description>
            {{ __('Add a new team member to your team, allowing them to collaborate with you.') }}
          </template>

          <template #footer>
            <PrimaryButton :class="{ 'opacity-75': addTeamMemberForm.processing || addTeamMemberForm.email === '' }"
                           :disabled="addTeamMemberForm.processing || addTeamMemberForm.email === ''"
                           :loading="addTeamMemberForm.processing"
                           @click="addTeamMember">
              {{ __('Save') }}
            </PrimaryButton>
          </template>

          <!--          <div class="col-span-6">
                      <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Please provide the email address of the person you would like to add to this team.') }}
                      </div>
                    </div>-->

          <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="space-y-3 sm:col-span-2">
              <label class="text-sm font-medium text-gray-600" for="email">
                {{ __('Email') }}
                <span class="text-red-600">*</span>
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <Input id="email" v-model="addTeamMemberForm.email" class="w-full"/>
                <InputError :message="addTeamMemberForm.errors?.email" class="mt-2"/>
              </dd>
            </div>

            <!-- Role -->
            <div v-if="availableRoles.length > 0" class="space-y-3 col-span-6 lg:col-span-4">
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
          </dl>
        </Card>

        <Card v-if="team.team_invitations.length > 0" :has-error="form.errors?.length > 0">
          <template #header>
            {{ __('Pending Invitations') }}
          </template>

          <template #description>
            {{ __('Add a new team member to your team, allowing them to collaborate with you.') }}
          </template>

          <div class="space-y-6">
            <div v-for="invitation in team.team_invitations" :key="invitation.id"
                 class="flex items-center justify-between">
              <div class="text-gray-600 text-sm dark:text-gray-400">
                {{ invitation.email }}
              </div>

              <div class="flex items-center">
                <!-- Cancel Team Invitation -->
                <button
                    class="cursor-pointer ms-6 text-sm text-red-500 focus:outline-hidden"
                    @click="cancelTeamInvitation(invitation)"
                >
                  {{ __('Cancel') }}
                </button>
              </div>
            </div>
          </div>
        </Card>

        <Card v-if="team.users.length > 0" :has-error="form.errors?.length > 0">
          <template #header>
            {{ __('Team Members') }}
          </template>

          <template #description>
            {{ __('All of the people that are part of this team.') }}
          </template>

          <div class="space-y-6">
            <div v-for="user in team.users" :key="user.id" class="flex items-center justify-between">
              <div class="flex items-center">
                <img :alt="user.name" :src="user.profile_photo_url" class="size-8 rounded-full object-cover">
                <div class="ms-4 text-sm dark:text-white">
                  {{ user.name }}
                </div>
              </div>

              <div class="flex items-center">
                <!-- Manage Team Member Role -->
                <button
                    v-if="availableRoles.length"
                    class="ms-2 text-sm text-gray-600 underline"
                    @click="manageRole(user)"
                >
                  {{ displayableRole(user.membership.role) }}
                </button>

                <div v-else-if="availableRoles.length" class="ms-2 text-sm text-gray-400">
                  {{ displayableRole(user.membership.role) }}
                </div>

                <!-- Remove Team Member -->
                <button
                    class="cursor-pointer ms-6 text-sm text-red-500"
                    @click="confirmTeamMemberRemoval(user)"
                >
                  {{ __('Remove') }}
                </button>
              </div>
            </div>
          </div>
        </Card>

        <Card :has-error="form.errors?.length > 0">
          <template #header>
            {{ __('Delete Team') }}
          </template>

          <template #description>
            {{ __('Permanently delete this team.') }}
          </template>

          <template #footer>
            <DangerButton @click="deleteTeam">{{ __('Delete Team') }}</DangerButton>
          </template>

          <p class="text-sm text-gray-600">
            {{
              __('Once a team is deleted, all of its resources and data will be permanently deleted. Before deleting this team, please download any data or information regarding this team that you wish to retain.')
            }}
          </p>
        </Card>
      </div>
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
  </AdminLayout>
</template>

<style scoped></style>
