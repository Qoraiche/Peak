<script setup>
import {inject, ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import ActionSection from '@/Themes/Breeze/Components/ActionSection.vue';
import DangerButton from '@/Themes/Breeze/Components/DangerButton.vue';
import DialogModal from '@/Themes/Breeze/Components/DialogModal.vue';
import InputError from '@/Themes/Breeze/Components/InputError.vue';
import SecondaryButton from '@/Themes/Breeze/Components/SecondaryButton.vue';
import TextInput from '@/Themes/Breeze/Components/TextInput.vue';

const route = inject('route');

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
  password: '',
});

const confirmUserDeletion = () => {
  confirmingUserDeletion.value = true;

  setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
  form.delete(route('current-user.destroy'), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => passwordInput.value.focus(),
    onFinish: () => form.reset(),
  });
};

const closeModal = () => {
  confirmingUserDeletion.value = false;

  form.reset();
};
</script>

<template>
  <ActionSection>
    <template #title>
      {{ __("Delete Account") }}
    </template>

    <template #description>
      {{ __("Permanently delete your account.") }}
    </template>

    <template #content>
      <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
        {{
          __("Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.")
        }}
      </div>

      <div class="mt-5">
        <DangerButton @click="confirmUserDeletion">
          {{ __("Delete Account") }}
        </DangerButton>
      </div>

      <!-- Delete Account Confirmation Modal -->
      <DialogModal :show="confirmingUserDeletion" max-width="lg" @close="closeModal">
        <template #title>
          {{ __("Delete Account") }}
        </template>

        <template #content>
          {{
            __("Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.")
          }}

          <div class="mt-4">
            <TextInput
                ref="passwordInput"
                v-model="form.password"
                :placeholder="__('Password')"
                autocomplete="current-password"
                class="mt-1 block w-full"
                type="password"
                @keyup.enter="deleteUser"
            />

            <InputError :message="form.errors.password" class="mt-2"/>
          </div>
        </template>

        <template #footer>
          <SecondaryButton @click="closeModal">
            {{ __('Cancel') }}
          </SecondaryButton>

          <DangerButton
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
              class="md:ms-3"
              @click="deleteUser"
          >
            {{ __('Delete Account') }}
          </DangerButton>
        </template>
      </DialogModal>
    </template>
  </ActionSection>
</template>
