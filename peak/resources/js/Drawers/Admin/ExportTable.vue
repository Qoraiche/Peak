<script setup>

import {inject, onMounted, ref, watch} from 'vue';

import {
  Dialog,
  DialogPanel,
  DialogTitle,
  RadioGroup,
  RadioGroupLabel,
  RadioGroupOption,
  TransitionChild,
  TransitionRoot
} from '@headlessui/vue';

import {XMarkIcon} from '@heroicons/vue/24/outline';
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import InputError from "@peak/Layouts/Admin/Components/InputError.vue";
import Input from "@peak/Components/Admin/Input.vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');
const open = ref(false);
const type = ref('');
const format = ref('excel');
const exportableRoute = ref('');
const form = useForm({});

const props = defineProps({
  notifyLaterButton: {default: true, type: Boolean},
});

const formats = [
  {name: 'xlsx'},
  {name: 'csv'},
  {name: 'xls'},
  {name: 'pdf'},
  {name: 'ods'},
  {name: 'tsv'}
]

const mem = ref(formats[0]);

const canExport = ref(true);

const selectedIds = ref([]);
const selectedExportableData = ref([]);
const exportableData = ref([]);

watch(selectedExportableData, (newVal, oldVal) => {
  if (newVal && newVal.length < 1) {
    canExport.value = false;
    // prevent unselecting all elements
    // selectedExportableData.value = oldVal;
    // alert('you need to select at least one exportable data')
  } else {
    canExport.value = true;
  }
});

const toast = useToast();

const exportData = async (instantly) => {
  let routeData = {
    name: __('Untitled Export'),
    format: mem.value.name.toLowerCase(),
    selectedIds: selectedIds.value,
    selectedExportableData: selectedExportableData.value
  };

  if (!instantly) {
    routeData.queue = 1;

    const result = await window.swal.fire({
      title: __("Name your export"),
      input: "text",
      inputLabel: __("Export name"),
      inputValue: '',
      showCancelButton: true,
      confirmButtonText: __("Export"),
      cancelButtonText: __("Cancel"),
      inputPlaceholder: __("Enter a name (optional)")
    });

    if (!result.isConfirmed || !result.value) {
      return; // User cancelled or did not enter a name
    }

    routeData.name = result.value;
  }

  const routeUrl = route(exportableRoute.value, routeData);

  if (instantly) {
    const link = document.createElement('a');
    link.href = routeUrl;
    link.download = '';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  } else {
    const toastId = toast.info(__('Submitting export request...'), {
      timeout: false,
      closeOnClick: false
    });

    axios.get(routeUrl)
        .then(() => {
          toast.dismiss(toastId);
          toast.success(__("Your export is being processed. You’ll be notified once it’s ready."));
          open.value = false;
        })
        .catch(() => {
          toast.dismiss(toastId);
          toast.error(__('Something went wrong. Please try again.'));
        });
  }
};

onMounted(() => {
  emitter.on('export:start', (event) => {
    open.value = true;
    type.value = event.type;
    exportableRoute.value = event.exportableRoute;
    exportableData.value = event.exportableData;
    selectedIds.value = event.selectedIds;
    selectedExportableData.value = exportableData.value;
  });
});

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
                          {{ __('Export :type', {type: type}) }}
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
                          {{ __('Export the current table data to a file.') }}
                        </p>
                      </div>

                      <div class="mt-1">
                        <p v-if="selectedIds.length > 0" class="text-sm text-white"
                           v-html="__('export.selected', { count: selectedIds.length })"></p>
                        <p v-else class="text-sm text-white">
                          {{ __('Export All') }}
                        </p>
                      </div>
                    </div>
                    <div class="flex flex-1 flex-col justify-between">
                      <div class="divide-y divide-gray-200 px-4 sm:px-6">
                        <div class="space-y-6 pb-5 pt-6">
                          <div>
                            <div class="flex items-center justify-between">
                              <h2 class="text-sm font-medium leading-6 text-gray-900">
                                {{ __('Format') }}
                              </h2>
                            </div>

                            <RadioGroup v-model="mem" class="mt-2">
                              <RadioGroupLabel class="sr-only">
                                {{ __('Choose format') }}
                              </RadioGroupLabel>
                              <div class="grid grid-cols-3 gap-6 sm:grid-cols-6">
                                <RadioGroupOption v-for="option in formats"
                                                  :key="option.name"
                                                  v-slot="{ active, checked }" :value="option"
                                                  as="template">
                                  <div
                                      :class="[active ? 'ring-2 ring-blue-600 ring-offset-2' : '', checked ? 'bg-blue-600 text-white hover:bg-blue-500' : 'ring-1 ring-inset ring-gray-300 bg-white text-gray-900 hover:bg-gray-50', 'cursor-pointer focus:outline-none flex items-center justify-center rounded-md py-3 px-8 text-xs font-semibold uppercase sm:flex-1']">
                                    <RadioGroupLabel as="span">
                                      {{ option.name }}
                                    </RadioGroupLabel>
                                  </div>
                                </RadioGroupOption>
                              </div>
                            </RadioGroup>
                          </div>

                          <fieldset>
                            <legend class="text-sm font-medium leading-6 text-gray-900">
                              {{ __('Columns') }}
                            </legend>
                            <div class="mt-2 space-y-4">
                              <fieldset>
                                <legend class="sr-only">
                                  {{ __('Data columns') }}
                                </legend>
                                <div class="space-y-1">
                                  <div v-for="(value, key) in exportableData"
                                       :key="key"
                                       class="relative flex items-start">
                                    <div class="flex h-6 items-center">
                                      <input :id="'column-' + key"
                                             v-model="selectedExportableData"
                                             :value="value"
                                             class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600"
                                             type="checkbox"/>
                                    </div>

                                    <div
                                        class="ltr:ml-3 rtl:mr-3 text-sm leading-6">
                                      <label :for="'column-' + key"
                                             class="font-medium text-gray-900">
                                        {{ value }}
                                      </label>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                            </div>
                          </fieldset>

                          <InputError class="mt-1"/>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="flex flex-shrink-0 justify-end px-4 py-4">
                    <SecondaryButton @click="open = false">
                      {{ __('Cancel') }}
                    </SecondaryButton>

                    <PrimaryButton :class="{'opacity-60': !canExport}" :disabled="!canExport"
                                   class="ltr:ml-2 rtl:mr-2"
                                   @click="exportData(true)">
                      {{ __('Export Now') }}
                    </PrimaryButton>

                    <PrimaryButton v-if="notifyLaterButton" :class="{'opacity-60': !canExport}" :disabled="!canExport"
                                   class="ltr:ml-2 rtl:mr-2"
                                   @click="exportData(false)">
                      {{ __('Export Now, Notify When Done') }}
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
