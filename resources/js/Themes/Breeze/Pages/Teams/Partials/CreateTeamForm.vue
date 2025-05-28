<script setup>
import {useForm} from '@inertiajs/vue3';
import FormSection from '@/Themes/Breeze/Components/FormSection.vue';
import InputError from '@/Themes/Breeze/Components/InputError.vue';
import InputLabel from '@/Themes/Breeze/Components/InputLabel.vue';
import PrimaryButton from '@/Themes/Breeze/Components/PrimaryButton.vue';
import TextInput from '@/Themes/Breeze/Components/TextInput.vue';
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const form = useForm({
  name: '',
});

const toast = useToast();

const createTeam = () => {
  form.post(route('teams.store'), {
    errorBag: 'createTeam',
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('Team Created'));
    },
  });
};
</script>

<template>
  <FormSection @submitted="createTeam">
    <template #title>
      {{ __('Team Details') }}
    </template>

    <template #description>
      {{ __('Create a new team to collaborate with others on projects.') }}
    </template>

    <template #form>
      <div class="col-span-6">
        <InputLabel :value="__('Team Owner')"/>

        <div class="flex items-center mt-2">
          <img :alt="$page.props.auth.user.name" :src="$page.props.auth.user.profile_photo_url"
               class="object-cover size-12 rounded-full">

          <div class="ms-4 leading-tight">
            <div class="text-gray-900 dark:text-white">{{ $page.props.auth.user.name }}</div>
            <div class="text-sm text-gray-700 dark:text-gray-300">
              {{ $page.props.auth.user.email }}
            </div>
          </div>
        </div>
      </div>

      <div class="col-span-6 sm:col-span-4">
        <InputLabel :value="__('Team Name')" for="name"/>
        <TextInput
            id="name"
            v-model="form.name"
            autofocus
            class="block w-full mt-1"
            type="text"
        />
        <InputError :message="form.errors.name" class="mt-2"/>
      </div>
    </template>

    <template #actions>
      <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
        {{ __('Create') }}
      </PrimaryButton>
    </template>
  </FormSection>
</template>
