<script setup>

import DialogModal from "@peak/Components/Admin/DialogModal.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {inject, onMounted, ref} from "vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import TextInput from "@peak/Components/Admin/Input.vue";
import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import {useForm} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const addMailChimp = ref(false);
const emitter = inject('emitter');

const props = defineProps({
  mailchimp: Object | null
});

const form = useForm({
  mailchimp_api_key: props.mailchimp.mailchimp_api_key,
  mailchimp_list_subscribers_id: props.mailchimp.mailchimp_list_subscribers_id,
  mailchimp_default_list_name: props.mailchimp.mailchimp_default_list_name,
  active: props.mailchimp.active
});

onMounted(() => {
  emitter.on('newsletter:add-mailchimp', () => {
    addMailChimp.value = true;
  });
});

const toast = useToast();

const save = () => {
  form.put(route('admin.settings.marketing.newsletter.update.mailchimp'), {
    onSuccess: () => {
      toast.success(__('Mailchimp settings saved'));
      addMailChimp.value = false;
    },
    preserveScroll: false
  });
};
</script>

<template>
  <DialogModal :show="addMailChimp" max-width="md" @close="addMailChimp = false">
    <template #title>
      {{ __('Connect MailChimp') }}
    </template>
    <template #content>
      <div class="grid grid-cols-1 gap-5">
        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('API Key')" for="key"/>
            <TextInput id="key" v-model="form.mailchimp_api_key"
                       class="w-full block"/>
            <InputError :message="form.errors?.mailchimp_api_key" class="mt-1"/>
          </div>
        </div>
        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Audience List ID')" for="subscribers_id"/>
            <TextInput id="subscribers_id" v-model="form.mailchimp_list_subscribers_id"
                       class="w-full block"/>
            <InputError :message="form.errors?.mailchimp_list_subscribers_id" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Default List Name')" for="subscribers_name"/>
            <TextInput id="subscribers_name" v-model="form.mailchimp_default_list_name"
                       class="w-full block"/>
            <InputError :message="form.errors?.mailchimp_default_list_name" class="mt-1"/>
          </div>
        </div>
      </div>
    </template>

    <template #footer>
      <PrimaryButton :class="{'opacity-75':form.processing || !form.isDirty}"
                     :disabled="form.processing || !form.isDirty"
                     @click="save">
        {{ __('Save') }}
      </PrimaryButton>
    </template>
  </DialogModal>
</template>

<style scoped>

</style>
