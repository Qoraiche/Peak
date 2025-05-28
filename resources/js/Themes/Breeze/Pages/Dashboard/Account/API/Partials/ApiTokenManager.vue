<script setup>
import {inject, ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import ActionMessage from '@/Themes/Breeze/Components/ActionMessage.vue';
import ActionSection from '@/Themes/Breeze/Components/ActionSection.vue';
import Checkbox from '@/Themes/Breeze/Components/Checkbox.vue';
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

const route = inject('route');

const props = defineProps({
  tokens: Array,
  availablePermissions: Array,
  defaultPermissions: Array,
});

const createApiTokenForm = useForm({
  name: '',
  permissions: props.defaultPermissions,
});

const updateApiTokenForm = useForm({
  permissions: [],
});

const deleteApiTokenForm = useForm({});

const displayingToken = ref(false);
const managingPermissionsFor = ref(null);
const apiTokenBeingDeleted = ref(null);

const createApiToken = () => {
  createApiTokenForm.post(route('dashboard.account.api-tokens.store'), {
    preserveScroll: true,
    onSuccess: () => {
      displayingToken.value = true;
      createApiTokenForm.reset();
    },
  });
};

const manageApiTokenPermissions = (token) => {
  updateApiTokenForm.permissions = token.abilities;
  managingPermissionsFor.value = token;
};

const updateApiToken = () => {
  updateApiTokenForm.put(route('dashboard.account.api-tokens.update', managingPermissionsFor.value), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => (managingPermissionsFor.value = null),
  });
};

const confirmApiTokenDeletion = (token) => {
  apiTokenBeingDeleted.value = token;
};

const deleteApiToken = () => {
  deleteApiTokenForm.delete(route('dashboard.account.api-tokens.destroy', apiTokenBeingDeleted.value), {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => (apiTokenBeingDeleted.value = null),
  });
};
</script>

<template>
  <div>
    <!-- Generate API Token -->
    <FormSection @submitted="createApiToken">
      <template #title>
        {{ __('Create API Token') }}
      </template>

      <template #description>
        {{ __('API tokens allow third-party services to authenticate with our application on your behalf.') }}
      </template>

      <template #form>
        <!-- Token Name -->
        <div class="col-span-6 sm:col-span-4">
          <InputLabel :value="__('Name')" for="name"/>
          <TextInput
              id="name"
              v-model="createApiTokenForm.name"
              autofocus
              class="mt-1 block w-full"
              type="text"
          />
          <InputError :message="createApiTokenForm.errors.name" class="mt-2"/>
        </div>

        <!-- Token Permissions -->
        <div v-if="availablePermissions.length > 0" class="col-span-6">
          <InputLabel :value="__('Permissions')" for="permissions"/>

          <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="permission in availablePermissions" :key="permission">
              <label class="flex items-center">
                <Checkbox v-model:checked="createApiTokenForm.permissions" :value="permission"/>
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ permission }}</span>
              </label>
            </div>
          </div>
        </div>
      </template>

      <template #actions>
        <ActionMessage :on="createApiTokenForm.recentlySuccessful" class="me-3">
          {{ __('Created.') }}
        </ActionMessage>

        <PrimaryButton :class="{ 'opacity-25': createApiTokenForm.processing }"
                       :disabled="createApiTokenForm.processing">
          {{ __('Create') }}
        </PrimaryButton>
      </template>
    </FormSection>

    <div v-if="tokens.length > 0">
      <SectionBorder/>

      <!-- Manage API Tokens -->
      <div class="mt-10 sm:mt-0">
        <ActionSection>
          <template #title>
            {{ __('Manage API Tokens') }}
          </template>

          <template #description>
            {{ __('You may delete any of your existing tokens if they are no longer needed.') }}
          </template>

          <!-- API Token List -->
          <template #content>
            <div class="space-y-6">
              <div v-for="token in tokens" :key="token.id" class="flex items-center justify-between">
                <div class="break-all dark:text-white">
                  {{ token.name }}
                </div>

                <div class="flex items-center ms-2">
                  <div v-if="token.last_used_ago" class="text-sm text-gray-400">
                    {{ __('Last used') }} {{ token.last_used_ago }}
                  </div>

                  <SecondaryButton
                      v-if="availablePermissions.length > 0"
                      class="cursor-pointer ms-4 py-1! text-sm"
                      @click="manageApiTokenPermissions(token)"
                  >
                    {{ __('Permissions') }}
                  </SecondaryButton>

                  <DangerButton class="cursor-pointer ms-4 text-sm text-red-500"
                                @click="confirmApiTokenDeletion(token)">
                    {{ __('Delete') }}
                  </DangerButton>
                </div>
              </div>
            </div>
          </template>
        </ActionSection>
      </div>
    </div>

    <!-- Token Value Modal -->
    <DialogModal :show="displayingToken" @close="displayingToken = false">
      <template #title>
        {{ __('API Token') }}
      </template>

      <template #content>
        <div>
          {{ __("Please copy your new API token. For your security, it won't be shown again.") }}
        </div>

        <div v-if="$page.props.jetstream.flash.token"
             class="mt-4 bg-gray-100 dark:bg-gray-900 px-4 py-2 rounded-sm font-mono text-sm text-gray-500 break-all">
          {{ $page.props.jetstream.flash.token }}
        </div>
      </template>

      <template #footer>
        <SecondaryButton @click="displayingToken = false">
          {{ __("Close") }}
        </SecondaryButton>
      </template>
    </DialogModal>

    <!-- API Token Permissions Modal -->
    <DialogModal :show="managingPermissionsFor != null" @close="managingPermissionsFor = null">
      <template #title>
        {{ __('API Token Permissions') }}
      </template>

      <template #content>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div v-for="permission in availablePermissions" :key="permission">
            <label class="flex items-center">
              <Checkbox v-model:checked="updateApiTokenForm.permissions" :value="permission"/>
              <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ permission }}</span>
            </label>
          </div>
        </div>
      </template>

      <template #footer>
        <SecondaryButton @click="managingPermissionsFor = null">
          {{ __('Cancel') }}
        </SecondaryButton>

        <PrimaryButton
            :class="{ 'opacity-25': updateApiTokenForm.processing }"
            :disabled="updateApiTokenForm.processing"
            class="ms-3"
            @click="updateApiToken"
        >
          {{ __('Save') }}
        </PrimaryButton>
      </template>
    </DialogModal>

    <!-- Delete Token Confirmation Modal -->
    <ConfirmationModal :show="apiTokenBeingDeleted != null" @close="apiTokenBeingDeleted = null">
      <template #title>
        {{ __('Delete API Token') }}
      </template>

      <template #content>
        {{ __('Are you sure you would like to delete this API token?') }}
      </template>

      <template #footer>
        <SecondaryButton @click="apiTokenBeingDeleted = null">
          {{ __('Cancel') }}
        </SecondaryButton>

        <DangerButton
            :class="{ 'opacity-25': deleteApiTokenForm.processing }"
            :disabled="deleteApiTokenForm.processing"
            class="ms-3"
            @click="deleteApiToken"
        >
          {{ __('Delete') }}
        </DangerButton>
      </template>
    </ConfirmationModal>
  </div>
</template>
