<script setup>
import {useForm} from '@inertiajs/vue3';
import ActionMessage from '@/Themes/Breeze/Components/ActionMessage.vue';
import FormSection from '@/Themes/Breeze/Components/FormSection.vue';
import InputError from '@/Themes/Breeze/Components/InputError.vue';
import InputLabel from '@/Themes/Breeze/Components/InputLabel.vue';
import PrimaryButton from '@/Themes/Breeze/Components/PrimaryButton.vue';
import TextInput from '@/Themes/Breeze/Components/TextInput.vue';

const props = defineProps({
  team: Object,
  permissions: Object
});

const form = useForm({
  name: props.team.name,
});

const updateTeamName = () => {
  form.put(route('teams.update', props.team), {
    errorBag: 'updateTeamName',
    preserveScroll: true,
  });
};
</script>

<template>
  <FormSection @submitted="updateTeamName">
    <template #title>
      {{ __('Team Name') }}
    </template>

    <template #description>
      {{ __("The team's name and owner information.") }}
    </template>

    <template #form>
      <!-- Team Owner Information -->
      <div class="col-span-6">
        <InputLabel :value="__('Team Owner')"/>

        <div class="flex items-center mt-2">
          <img :alt="team.owner.name" :src="team.owner.profile_photo_url" class="size-12 rounded-full object-cover">

          <div class="ms-4 leading-tight">
            <div class="text-gray-900 dark:text-white">{{ team.owner.name }}</div>
            <div class="text-gray-700 dark:text-gray-300 text-sm">
              {{ team.owner.email }}
            </div>
          </div>
        </div>
      </div>

      <!-- Team Name -->
      <div class="col-span-6 sm:col-span-4">
        <InputLabel :value="__('Team Name')" for="name"/>

        <TextInput
            id="name"
            v-model="form.name"
            :disabled="! permissions.canUpdateTeam"
            class="mt-1 block w-full"
            type="text"
        />

        <InputError :message="form.errors.name" class="mt-2"/>
      </div>
    </template>

    <template v-if="permissions.canUpdateTeam" #actions>
      <ActionMessage :on="form.recentlySuccessful" class="me-3">
        {{ __('Saved.') }}
      </ActionMessage>

      <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        {{ __('Save') }}
      </PrimaryButton>
    </template>
  </FormSection>
</template>
