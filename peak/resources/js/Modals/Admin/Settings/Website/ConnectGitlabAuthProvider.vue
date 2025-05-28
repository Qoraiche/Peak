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

const gitlabAuthProvider = ref(false);
const emitter = inject('emitter');

const props = defineProps({
  gitlab: Object | null
});

const form = useForm({
  gitlab_active: props.gitlab.active,
  gitlab_client_id: props.gitlab.client_id,
  gitlab_client_secret: props.gitlab.client_secret,
});

onMounted(() => {
  emitter.on('gitlabAuthProvider', () => {
    gitlabAuthProvider.value = true;
  });
});

const toast = useToast();

const save = () => {
  form.put(route('admin.settings.website.auth-providers.update.gitlab'), {
    onSuccess: () => {
      toast.success(__('Auth provider saved'));
      gitlabAuthProvider.value = false;
    },
    preserveScroll: false
  });
};

const close = () => {
  form.reset();
  gitlabAuthProvider.value = false;
};

</script>

<template>
  <DialogModal :show="gitlabAuthProvider" max-width="md" @close="close">
    <template #title>
      {{ __('Connect Gitlab') }}
    </template>
    <template #content>
      <div class="grid grid-cols-1 gap-5">
        <div class="p-2 bg-gray-50 rounded-md">
          <SwitchGroup as="div" class="flex items-center justify-between">
                        <span class="flex grow flex-col">
                          <SwitchLabel as="span" class="text-sm/6 font-medium text-gray-900"
                                       passive>{{ __('Active') }}</SwitchLabel>
                          <SwitchDescription as="span" class="text-sm text-gray-500">
                          {{ __('Enable authentication via this provider.') }}
                          </SwitchDescription>
                        </span>
            <Switch v-model="form.gitlab_active"
                    :class="[form.gitlab_active ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                            <span
                                :class="[form.gitlab_active ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block size-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                aria-hidden="true"/>
            </Switch>
          </SwitchGroup>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel for="key" value="Client ID"/>
            <TextInput id="key" v-model="form.gitlab_client_id"
                       :placeholder="__('Client ID')"
                       class="w-full block"/>
            <InputError :message="form.errors?.gitlab_client_id" class="mt-1"/>
          </div>
        </div>
        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel for="secret" value="Client Secret"/>
            <TextInput id="secret" v-model="form.gitlab_client_secret"
                       :placeholder="__('Client Secret')"
                       class="w-full block"/>
            <InputError :message="form.errors?.gitlab_client_secret" class="mt-1"/>
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
