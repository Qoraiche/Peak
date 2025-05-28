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


const connectStripePayments = ref(false);
const emitter = inject('emitter');

const props = defineProps({
  stripe: Object | null
});

const form = useForm({
  label: props.stripe.label,
  description: props.stripe.description,
  active: props.stripe.active,
  key: props.stripe.key,
  secret: props.stripe.secret,
  webhook_secret: props.stripe.webhook_secret
});

onMounted(() => {
  emitter.on('payments:connect-stripe', () => {
    connectStripePayments.value = true;
  });
});

const toast = useToast();

const save = () => {
  form.put(route('admin.settings.selling.payments.update.stripe'), {
    onSuccess: () => {
      toast.success(__('Payment method saved'));
      connectStripePayments.value = false;
    },
    preserveScroll: false
  });
};

const close = () => {
  connectStripePayments.value = false;
  form.reset();
  form.clearErrors();
}
</script>

<template>
  <DialogModal :show="connectStripePayments" max-width="md" @close="close">
    <template #title>
      {{ __('Connect Stripe') }}
    </template>
    <template #content>
      <div class="grid grid-cols-1 gap-5">

        <div class="p-2 bg-gray-50 rounded-md">
          <SwitchGroup as="div" class="flex items-center justify-between">
                        <span class="flex grow flex-col">
                          <SwitchLabel as="span" class="text-sm/6 font-medium text-gray-900"
                                       passive>{{ __('Active') }}</SwitchLabel>
                          <SwitchDescription as="span" class="text-sm text-gray-500">
                          {{
                              __('Toggle to activate or deactivate Stripe payment gateway.')
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
            <TextInput id="key" v-model="form.label"
                       :placeholder="__('Label')"
                       class="w-full block"/>
            <InputError :message="form.errors?.label" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Description')" for="key"/>
            <TextInput id="key" v-model="form.description"
                       :placeholder="__('Description')"
                       class="w-full block"/>
            <InputError :message="form.errors?.description" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Key')" for="key"/>
            <TextInput id="key" v-model="form.key"
                       :placeholder="__('Key')"
                       class="w-full block"/>
            <InputError :message="form.errors?.key" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Secret')" for="secret"/>
            <TextInput id="secret" v-model="form.secret"
                       :placeholder="__('Secret')"
                       class="w-full block"/>
            <InputError :message="form.errors?.secret" class="mt-1"/>
          </div>
        </div>
        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Webhook Secret')" for="webhook_secret"/>
            <TextInput id="webhook_secret" v-model="form.webhook_secret"
                       :placeholder="__('Webhook Secret')"
                       class="w-full block"/>
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
