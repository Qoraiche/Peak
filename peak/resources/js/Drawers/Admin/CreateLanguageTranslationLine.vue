<script setup>
import {inject, onMounted, ref, watch} from 'vue'
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue';

import {XMarkIcon} from '@heroicons/vue/24/outline';
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import Input from "@peak/Components/Admin/Input.vue";
import {useToast} from "vue-toastification";
import AlertInfo from "@peak/Components/Admin/Alerts/AlertInfo.vue";
import {__} from "@peak/Composables/useTranslate.js";
import TextArea from "@peak/Components/Admin/TextArea.vue";

const emitter = inject('emitter');
const open = ref(false);
const language = ref(null);
const groups = ref({});

onMounted(() => {
  emitter.on('translation-line:create', (event) => {
    open.value = true;
    language.value = event.language;
    groups.value = event.groups;
  });
});

const newGroup = ref(false);

const toast = useToast();

const form = useForm({
  group: null,
  translation_key: null,
  translation_value: null,
});

const addNewGroup = ref(false);

watch(addNewGroup, addNewGroupVal => {
  if (addNewGroupVal) {
    form.group = '';
  } else {
    form.group = groups.value[0] ?? null;
  }
});

watch(groups, newGroupsVal => {
  form.group = newGroupsVal[0] ?? null;
});

const errors = ref({});

const close = () => {
  open.value = false;
  form.clearErrors();
  form.reset();
};

const save = () => {
  form.post(route('admin.languages.translation.store', language.value.id), {
    onSuccess: () => {
      toast.success(__('Translation created'));
      close();
    },
    onError: () => {
      toast.error(__('Something went wrong.'));
    },
  });
};
</script>

<template>
  <TransitionRoot :show="open" as="template">
    <Dialog as="div" class="relative z-40" @close="open = false">
      <div class="fixed inset-0"/>

      <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
          <div
              class="pointer-events-none fixed inset-y-0 ltr:right-0 rtl:left-0 flex max-w-full ltr:pl-10 ltr:sm:pl-16">
            <TransitionChild as="template"
                             enter="transform transition ease-in-out duration-500 sm:duration-700"
                             enter-from="ltr:translate-x-full rtl:-translate-x-full"
                             enter-to="ltr:translate-x-0 rtl:translate-x-0"
                             leave="transform transition ease-in-out duration-500 sm:duration-700"
                             leave-from="ltr:translate-x-0 rtl:translate-x-0"
                             leave-to="ltr:translate-x-full rtl:-translate-x-full">
              <DialogPanel class="pointer-events-auto w-screen max-w-md">
                <div class="flex h-full flex-col divide-y divide-gray-200 bg-white shadow-xl">
                  <div class="h-0 flex-1 overflow-y-auto">
                    <div class="bg-blue-600 px-4 py-6 sm:px-6">
                      <div class="flex items-center justify-between">
                        <DialogTitle
                            class="text-base font-semibold leading-6 text-white capitalize">
                          {{ __('Translations') }}
                        </DialogTitle>
                        <div class="ml-3 flex h-7 items-center">
                          <button class="relative rounded-md bg-blue-700 text-blue-200 hover:text-white"
                                  type="button"
                                  @click="open = false">
                            <span class="absolute -inset-2.5"/>
                            <span class="sr-only">
                              {{ __('Close panel') }}
                            </span>
                            <XMarkIcon aria-hidden="true" class="h-6 w-6"/>
                          </button>

                        </div>
                      </div>
                      <div class="mt-1">
                        <p class="text-sm text-white">
                          {{ __('Add a new translation key and value.') }}
                        </p>
                      </div>
                    </div>
                    <div class="flex flex-1 flex-col justify-between">

                      <div class="divide-y divide-gray-200 px-4 sm:px-6">
                        <div class="space-y-6 pb-5 pt-6">
                          <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900"
                                   for="current">
                              {{ __('Group') }}
                            </label>
                            <div v-if="addNewGroup" class="mt-2 flex flex-col space-y-2">
                              <Input id="current" v-model="form.group"
                                     class="block w-full"
                                     placeholder="frontend" required
                                     type="text"/>

                              <SecondaryButton
                                  class="text-sm flex justify-center items-center hover:text-blue-600 text-left"
                                  @click="addNewGroup = false">
                                {{ __('Select existing group') }}
                              </SecondaryButton>
                            </div>

                            <div v-else class="mt-2 flex flex-col space-y-2">

                              <AlertInfo v-if="!addNewGroup && groups.length < 1">
                                {{ __('You don\'t have any groups yet.') }}
                              </AlertInfo>

                              <select v-if="groups.length > 0" id="default_lang"
                                      v-model="form.group"
                                      class="block w-full min-w-0 flex-1 rounded-md border border-gray-300 text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm sm:leading-6">
                                <option v-for="group in groups" :value="group">
                                  {{ group }}
                                </option>
                              </select>

                              <SecondaryButton
                                  class="text-sm flex justify-center items-center hover:text-blue-600 text-left"
                                  @click="addNewGroup = true">
                                {{ __('Add new group') }}
                              </SecondaryButton>
                            </div>

                            <InputError :message="errors.group" class="mt-2"/>
                          </div>

                          <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900"
                                   for="current">
                              {{ __('Key') }}
                            </label>
                            <div class="mt-2">
                              <Input id="current" v-model="form.translation_key"
                                     class="block w-full"
                                     placeholder="user.greeting" required
                                     type="text"/>
                            </div>

                            <InputError :message="errors.translation_key" class="mt-2"/>
                          </div>

                          <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900"
                                   for="current">
                              {{ __('Value') }}
                            </label>
                            <div class="mt-2">
                              <TextArea id="current" v-model="form.translation_value"
                                        class="block w-full"
                                        placeholder="Welcome back :user" required
                                        type="text"/>
                            </div>

                            <InputError :message="errors.translation_value" class="mt-2"/>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="flex flex-shrink-0 justify-end px-4 py-4">
                    <SecondaryButton @click="open = false">
                      {{ __('Cancel') }}
                    </SecondaryButton>
                    <PrimaryButton :processing="form.processing" class="ltr:ml-2 rtl:mr-2"
                                   @click="save">
                      {{ __('Create') }}
                    </PrimaryButton>
                  </div>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<style scoped>

</style>
