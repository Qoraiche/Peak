<script setup>
import {inject, nextTick, ref} from 'vue';
import {useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Themes/Breeze/Components/AuthenticationCard.vue';
import InputError from '@/Themes/Breeze/Components/InputError.vue';
import InputLabel from '@/Themes/Breeze/Components/InputLabel.vue';
import PrimaryButton from '@/Themes/Breeze/Components/PrimaryButton.vue';
import TextInput from '@/Themes/Breeze/Components/TextInput.vue';

const route = inject('route');

const recovery = ref(false);

const form = useForm({
  code: '',
  recovery_code: '',
});

const recoveryCodeInput = ref(null);
const codeInput = ref(null);

const toggleRecovery = async () => {
  recovery.value ^= true;

  await nextTick();

  if (recovery.value) {
    recoveryCodeInput.value.focus();
    form.code = '';
  } else {
    codeInput.value.focus();
    form.recovery_code = '';
  }
};

const submit = () => {
  form.post(route('two-factor.login'));
};
</script>

<template>
  <AuthenticationCard :title="__('Two-factor Confirmation')">

    <template #title>
      {{ __('Two-factor Confirmation') }}
    </template>

    <div class="mb-4 text-sm text-gray-600">
      <template v-if="! recovery">
        {{
          __('Please confirm access to your account by entering the authentication code provided by your authenticator application.')
        }}
      </template>

      <template v-else>
        {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
      </template>
    </div>

    <form @submit.prevent="submit">
      <div v-if="! recovery">
        <InputLabel :value="__('Code')" for="code"/>
        <TextInput
            id="code"
            ref="codeInput"
            v-model="form.code"
            autocomplete="one-time-code"
            autofocus
            class="mt-1 block w-full"
            inputmode="numeric"
            type="text"
        />
        <InputError :message="form.errors.code" class="mt-2"/>
      </div>

      <div v-else>
        <InputLabel :value="__('Recovery Code')" for="recovery_code"/>
        <TextInput
            id="recovery_code"
            ref="recoveryCodeInput"
            v-model="form.recovery_code"
            autocomplete="one-time-code"
            class="mt-1 block w-full"
            type="text"
        />
        <InputError :message="form.errors.recovery_code" class="mt-2"/>
      </div>

      <div class="flex items-center justify-end mt-4">
        <button class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer" type="button"
                @click.prevent="toggleRecovery">
          <template v-if="! recovery">
            {{ __('Use a recovery code') }}
          </template>

          <template v-else>
            {{ __('Use an authentication code') }}
          </template>
        </button>

        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                       class="w-full justify-center">
          {{ __('Log in') }}
        </PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
