<script setup>
import {ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import ActionSection from '@/Themes/Breeze/Components/ActionSection.vue';
import ConfirmationModal from '@/Themes/Breeze/Components/ConfirmationModal.vue';
import DangerButton from '@/Themes/Breeze/Components/DangerButton.vue';
import SecondaryButton from '@/Themes/Breeze/Components/SecondaryButton.vue';

const props = defineProps({
  team: Object,
});

const confirmingTeamDeletion = ref(false);
const form = useForm({});

const confirmTeamDeletion = () => {
  confirmingTeamDeletion.value = true;
};

const deleteTeam = () => {
  form.delete(route('teams.destroy', props.team), {
    errorBag: 'deleteTeam',
  });
};
</script>

<template>
  <ActionSection>
    <template #title>
      {{ __('Delete Team') }}
    </template>

    <template #description>
      {{ __('Permanently delete this team.') }}
    </template>

    <template #content>
      <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
        {{
          __('Once a team is deleted, all of its resources and data will be permanently deleted. Before deleting this team, please download any data or information regarding this team that you wish to retain.')
        }}
      </div>

      <div class="mt-5">
        <DangerButton @click="confirmTeamDeletion">
          {{ __('Delete Team') }}
        </DangerButton>
      </div>

      <!-- Delete Team Confirmation Modal -->
      <ConfirmationModal :show="confirmingTeamDeletion" @close="confirmingTeamDeletion = false">
        <template #title>
          {{ __('Delete Team') }}
        </template>

        <template #content>
          {{
            __('Are you sure you want to delete this team? Once a team is deleted, all of its resources and data will be permanently deleted.')
          }}
        </template>

        <template #footer>
          <SecondaryButton @click="confirmingTeamDeletion = false">
            {{ __('Cancel') }}
          </SecondaryButton>

          <DangerButton
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
              class="ms-3"
              @click="deleteTeam"
          >
            {{ __('Delete Team') }}
          </DangerButton>
        </template>
      </ConfirmationModal>
    </template>
  </ActionSection>
</template>
