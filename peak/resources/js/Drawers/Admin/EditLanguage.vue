<script setup>
import {inject, onMounted, ref, watch} from 'vue'
import {Dialog, DialogPanel, DialogTitle, Switch, SwitchGroup, TransitionChild, TransitionRoot} from '@headlessui/vue';

import {XMarkIcon} from '@heroicons/vue/24/outline';
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import Input from "@peak/Components/Admin/Input.vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');
const open = ref(false);
const language = ref(null);
const groups = ref({});

onMounted(() => {
  emitter.on('language:edit', (event) => {
    open.value = true;
    language.value = event.language;
  });
});

const toast = useToast();

const form = useForm({
  name: null,
  code: null,
  direction: 'ltr',
  flag_emoji: '🏳️',
  active: true,
  flag: null
});

watch(language, newLanguage => {
  form.name = newLanguage.name;
  form.code = newLanguage.code;
  form.flag_emoji = newLanguage.flag_emoji;
  form.direction = newLanguage.direction;
  form.active = newLanguage.active;
});

const close = () => {
  open.value = false;
  form.clearErrors();
  form.reset();
};

const save = () => {
  form.put(route('admin.languages.update', language.value.id), {
    onSuccess: () => {
      toast.success(__('Changes Saved'));
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
                          {{ __('Edit Language') }}
                        </DialogTitle>
                        <div class="ml-3 flex h-7 items-center">
                          <button class="relative rounded-md bg-blue-700 text-blue-200 hover:text-white"
                                  type="button"
                                  @click="open = false">
                            <span class="absolute -inset-2.5"/>
                            <span class="sr-only">Close panel</span>
                            <XMarkIcon aria-hidden="true" class="h-6 w-6"/>
                          </button>
                        </div>
                      </div>

                      <div class="mt-1">
                        <p class="text-sm text-blue-300">
                          {{ __('Update language settings or name.') }}
                        </p>
                      </div>

                    </div>
                    <div class="flex flex-1 flex-col justify-between">

                      <div class="divide-y divide-gray-200 px-4 sm:px-6">
                        <div class="space-y-6 pb-5 pt-6">
                          <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900"
                                   for="name">
                              {{ __('Name') }}
                            </label>

                            <div class="mt-2 flex flex-col space-y-2">
                              <Input id="name" v-model="form.name"
                                     class="block w-full"
                                     placeholder="English" required
                                     type="text"/>
                            </div>
                            <InputError :message="form.errors.name" class="mt-2"/>
                          </div>

                          <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900"
                                   for="code">
                              {{ __('Code') }}
                            </label>
                            <div class="mt-2">
                              <Input id="code" v-model="form.code"
                                     class="block w-full"
                                     placeholder="en" required
                                     type="text"/>
                            </div>

                            <InputError :message="form.errors.code" class="mt-2"/>
                          </div>

                          <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900"
                                   for="flag">
                              {{ __('Flag Emoji') }}
                            </label>
                            <div class="mt-2">
                              <Input id="flag" v-model="form.flag_emoji"
                                     class="emoji block w-full"
                                     required
                                     type="text"/>
                            </div>

                            <InputError :message="form.errors.flag_emoji" class="mt-2"/>
                          </div>

                          <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900"
                                   for="direction">
                              {{ __('Direction') }}
                            </label>
                            <div class="mt-2">
                              <select id="direction" v-model="form.direction"
                                      class="mt-2 block w-full min-w-0 flex-1 rounded-md border border-gray-300 text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm sm:leading-6">
                                <option value="ltr">
                                  {{ __('LTR') }}
                                </option>
                                <option value="rtl">
                                  {{ __('RTL') }}
                                </option>
                              </select>
                            </div>

                            <InputError :message="form.errors.direction" class="mt-2"/>
                          </div>

                          <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900"
                                   for="active">
                              {{ __('Active') }}
                            </label>
                            <div class="mt-2">
                              <SwitchGroup as="div"
                                           class="flex items-center justify-between">
                                <Switch v-model="form.active"
                                        :class="[form.active ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                                <span
                                                    :class="[form.active ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                                    aria-hidden="true"/>
                                </Switch>
                              </SwitchGroup>
                            </div>

                            <InputError :message="form.errors.active" class="mt-2"/>
                          </div>

                          <!--                                                    <div>
                                                                                  <label for="current"
                                                                                         class="block text-sm font-medium leading-6 text-gray-900">
                                                                                      Flag
                                                                                  </label>
                                                                                  <div class="mt-2">
                                                                                      <select v-model="form.direction" id="default_lang"
                                                                                              class="mt-2 block w-full min-w-0 flex-1 rounded-md border border-gray-300 text-gray-900 placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm sm:leading-6">
                                                                                          <option value="ltr">
                                                                                              LTR
                                                                                          </option>
                                                                                          <option value="rtl">
                                                                                              RTL
                                                                                          </option>
                                                                                      </select>
                                                                                  </div>

                                                                                  <InputError class="mt-2" :message="form.errors.direction"/>
                                                                              </div>-->
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="flex flex-shrink-0 justify-end px-4 py-4">
                    <SecondaryButton @click="close">{{ __('Cancel') }}</SecondaryButton>
                    <PrimaryButton :class="{'opacity-75' : form.processing}"
                                   :disabled="form.processing"
                                   :loading="form.processing" class="ltr:ml-2 rtl:mr-2" @click="save">
                      {{ __('Save Changes') }}
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
