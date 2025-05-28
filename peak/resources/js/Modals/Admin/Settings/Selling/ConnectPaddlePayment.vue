<script setup>

import DialogModal from "@peak/Components/Admin/DialogModal.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {inject, onMounted, ref} from "vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import TextInput from "@peak/Components/Admin/Input.vue";
import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import {useForm} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";
import {Switch, SwitchDescription, SwitchGroup, SwitchLabel} from "@headlessui/vue";
import {__} from "@peak/Composables/useTranslate.js";

const connectPaddlePayments = ref(false);
const emitter = inject('emitter');

const props = defineProps({
  paddle: Object | null
});

const form = useForm({
  label: props.paddle.label,
  description: props.paddle.description,
  active: props.paddle.active,
  sandbox: props.paddle.sandbox,
  client_side_token: props.paddle.client_side_token,
  api_key: props.paddle.api_key,
  retain_key: props.paddle.retain_key,
  webhook_secret: props.paddle.webhook_secret
});

onMounted(() => {
  emitter.on('payments:connect-paddle', () => {
    connectPaddlePayments.value = true;
  });
});

const toast = useToast();

const save = () => {
  form.put(route('admin.settings.selling.payments.update.paddle'), {
    onSuccess: () => {
      toast.success(__('Payment method saved'));
      connectPaddlePayments.value = false;
    },
    preserveScroll: false
  });
};

const close = () => {
  connectPaddlePayments.value = false;
  form.reset();
  form.clearErrors();
}
</script>

<template>
  <DialogModal :show="connectPaddlePayments" max-width="md" @close="close">
    <template #title>
      {{ __('Connect Paddle') }}
    </template>

    <template #content>
      <div class="grid grid-cols-1 gap-5">
        <div class="p-2 bg-gray-50 rounded-md">
          <SwitchGroup as="div" class="flex items-center justify-between">
                        <span class="flex grow flex-col">
                          <SwitchLabel as="span" class="text-sm/6 font-medium text-gray-900"
                                       passive>
                            {{ __('Active') }}
                          </SwitchLabel>
                          <SwitchDescription as="span" class="text-sm text-gray-500">
                            {{
                              __('Toggle to activate or deactivate Paddle payment gateway.')
                            }}
                          </SwitchDescription>
                        </span>
            <Switch v-model="form.active"
                    :class="[form.active ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                            <span
                                :class="[form.active ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                aria-hidden="true"/>
            </Switch>
          </SwitchGroup>

          <InputError :message="form.errors?.active" class="mt-1"/>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Label')" for="key"/>
            <TextInput id="key" v-model="form.label" class="w-full block"/>
            <InputError :message="form.errors?.label" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Description')" for="key"/>
            <TextInput id="key" v-model="form.description" class="w-full block"/>
            <InputError :message="form.errors?.description" class="mt-1"/>
          </div>
        </div>

        <div class="p-2 bg-gray-50 rounded-md">
          <SwitchGroup as="div" class="flex items-center justify-between">
                        <span class="flex grow flex-col">
                          <SwitchLabel as="span" class="text-sm/6 font-medium text-gray-900"
                                       passive>{{ __('Sandbox') }}</SwitchLabel>
                          <SwitchDescription as="span" class="text-sm text-gray-500">
                          {{
                              __('Enable Sandbox mode to test payments using Paddle without processing real transactions.')
                            }}
                          </SwitchDescription>
                        </span>
            <Switch v-model="form.sandbox"
                    :class="[form.sandbox ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                            <span
                                :class="[form.sandbox ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                aria-hidden="true"/>
            </Switch>
          </SwitchGroup>

          <InputError :message="form.errors?.sandbox" class="mt-1"/>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Client Side Token')" for="key"/>
            <TextInput id="key" v-model="form.client_side_token" class="w-full block"/>
            <InputError :message="form.errors?.client_side_token" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('API Key')" for="secret"/>
            <TextInput id="secret" v-model="form.api_key" class="w-full block"/>
            <InputError :message="form.errors?.api_key" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Retain Key (optional)')" for="webhook_secret"/>
            <TextInput id="webhook_secret" v-model="form.retain_key" class="w-full block"/>
            <InputError :message="form.errors?.retain_key" class="mt-1"/>

          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Webhook Secret')" for="webhook_secret"/>
            <TextInput id="webhook_secret" v-model="form.webhook_secret" class="w-full block"/>
            <InputError :message="form.errors?.webhook_secret" class="mt-1"/>
          </div>
        </div>
      </div>
    </template>
    <template #footer>
      <PrimaryButton :class="{'opacity-75':form.processing || !form.isDirty}"
                     :disabled="form.processing || !form.isDirty"
                     @click="save">
        {{ __('Save Changes') }}
      </PrimaryButton>
    </template>
  </DialogModal>
</template>

<style scoped>

</style>
