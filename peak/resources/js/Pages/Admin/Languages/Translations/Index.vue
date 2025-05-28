<script setup>

import Layout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {CheckIcon, ChevronUpDownIcon, TrashIcon,} from "@heroicons/vue/24/outline/index.js";
import {inject, ref, watch} from "vue";
import {router} from '@inertiajs/vue3';
import AlertInfo from "@peak/Components/Admin/Alerts/AlertInfo.vue";
import {Listbox, ListboxButton, ListboxOption, ListboxOptions} from "@headlessui/vue";
import DataSearchForm from "@peak/Components/Admin/DataSearchForm.vue";
import TextArea from "@peak/Components/Admin/TextArea.vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";
import {useConfirm} from "@peak/Composables/useConfirm.js";

const props = defineProps({
  items: Object,
  language: Object,
  languages: Object,
  group: String,
  groups: Array,
  exportableData: Array
});

function flattenTranslations(obj, parentKey = '') {
  let result = {};
  for (let key in obj) {
    const newKey = parentKey ? `${parentKey}.${key}` : key;
    if (typeof obj[key] === 'object' && obj[key] !== null) {
      result = {...result, ...flattenTranslations(obj[key], newKey)}; // Recursive flatten
    } else {
      result[newKey] = obj[key];
    }
  }
  return result;
}

const flattenedTranslations = ref(flattenTranslations(props.items));

const activeGroup = ref(props.group);
const emitter = inject('emitter');

const activeLanguage = ref(props.language);

watch(activeLanguage, (newLanguage, oldLanguage) => {
  if (newLanguage.name !== oldLanguage.name) {
    router.visit(route('admin.languages.show', newLanguage.id));
  }
});

const searchableColumns = ['key', 'value'];
const pageTitle = __(':language Translations', {language: props.language.name});
const pageDescription = __('Manage language translation keys and their corresponding values.');
const indexRouteName = 'admin.languages.show';
const destroyItemRouteName = 'admin.user-management.users.destroy';
const destroyBulkItemsRouteName = 'admin.user-management.users.bulk-destroy';

const editedTranslations = ref({});
const toast = useToast();

const translationLineChanged = (key, value, event) => {
  editedTranslations.value[key] = event.target.value;
  saveTranslationLines(event.target);
}

const saveTranslationLines = target => {

  target.classList.add('opacity-60');
  target.setAttribute('disabled', 'true');

  router.put(route('admin.languages.translation.update', props.language.id), {
    data: editedTranslations.value,
    group: activeGroup.value
  }, {
    preserveScroll: true,
    preserveState: false,
    onSuccess: () => {
      toast.success(__('Translation saved'));
      target.classList.remove('opacity-60');
      target.removeAttribute('disabled');
    },
    onError: () => {
      toast.error(__('Something went wrong.'));
    }
  });
};

watch(activeGroup, newValue => {
  router.visit(route('admin.languages.show', {
    language: props.language.id,
    group: newValue
  }))
});

const newLanguageTranslationLine = () => {
  emitter.emit('translation-line:create', {language: props.language, groups: props.groups})
};

const deleteLanguageLine = async (group, key) => {
  const confirmed = await useConfirm({
    title: __("Delete Translation?"),
    text: __("Are you sure you want to delete this translation?"),
    confirmButtonText: __("Yes, delete it"),
  });

  if (confirmed) {
    router.delete(route('admin.languages.translation.delete',
        {group: group, key: key}), {
      onSuccess: () => {
        toast.success(__('Language line deleted'));
        router.reload();
      },
      onError: () => {
        toast.error(__('Something went wrong.'));
      },
    });
  }
}

</script>

<template>
  <Layout :description="pageDescription" :page-icon="false" :title="pageTitle">
    <template v-slot:actions>
      <Listbox v-model="activeLanguage" as="div">
        <div class="relative">
          <ListboxButton
              class="relative md:min-w-[120px] w-full cursor-pointer rounded-md bg-white py-2 pl-3 pr-10 text-left text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
            <div class="block truncate"><span class="font-medium">{{
                activeLanguage.name
              }}</span></div>
            <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
          <ChevronUpDownIcon aria-hidden="true" class="h-5 w-5 text-gray-400"/>
        </span>
          </ListboxButton>

          <transition leave-active-class="transition ease-in duration-100"
                      leave-from-class="opacity-100" leave-to-class="opacity-0">
            <ListboxOptions
                class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-opacity-5 focus:outline-none sm:text-sm">
              <ListboxOption v-for="language in languages" :key="language.id" v-slot="{ active, selected }"
                             :value="language"
                             as="template">
                <li :class="[active ? 'bg-blue-600 text-white' : 'text-gray-900', 'relative cursor-default select-none py-2 pl-8 pr-4']">
                                                <span
                                                    :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">{{
                                                    language.name
                                                  }}</span>

                  <span v-if="selected"
                        :class="[active ? 'text-white' : 'text-blue-600', 'absolute inset-y-0 left-0 flex items-center pl-1.5']">
                <CheckIcon aria-hidden="true" class="h-5 w-5"/>
              </span>
                </li>
              </ListboxOption>
            </ListboxOptions>
          </transition>
        </div>
      </Listbox>

      <PrimaryButton :new-icon="true" class="ltr:ml-2 rtl:mr-2" @click="newLanguageTranslationLine">
        {{ __('Add New Translation') }}
      </PrimaryButton>
    </template>

    <div class="mt-8">
      <AlertInfo class="mb-8">
        {{ __('The translation is saved automatically, just enter the value and click out of the box.') }}
      </AlertInfo>

      <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-3 bg-white">
        <div
            class="flex flex-col md:flex-row space-y-4 md:space-y-0 items-start md:items-center justify-between pb-4 bg-white dark:bg-gray-900">
          <div>
            <Listbox v-model="activeGroup" as="div">
              <div class="relative mt-2">
                <ListboxButton
                    class="relative w-full cursor-pointer rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-600 sm:text-sm sm:leading-6">
                  <div class="block truncate">{{ __('Group:') }} <span class="font-medium">{{
                      activeGroup
                    }}</span></div>
                  <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
          <ChevronUpDownIcon aria-hidden="true" class="h-5 w-5 text-gray-400"/>
        </span>
                </ListboxButton>

                <transition leave-active-class="transition ease-in duration-100"
                            leave-from-class="opacity-100" leave-to-class="opacity-0">
                  <ListboxOptions
                      class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-opacity-5 focus:outline-none sm:text-sm">
                    <ListboxOption v-for="group in groups" :key="group" v-slot="{ active, selected }" :value="group"
                                   as="template">
                      <li :class="[active ? 'bg-blue-600 text-white' : 'text-gray-900', 'relative cursor-default select-none py-2 pl-8 pr-4']">
                                                <span
                                                    :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">{{
                                                    group
                                                  }}</span>

                        <span v-if="selected"
                              :class="[active ? 'text-white' : 'text-blue-600', 'absolute inset-y-0 left-0 flex items-center pl-1.5']">
                <CheckIcon aria-hidden="true" class="h-5 w-5"/>
              </span>
                      </li>
                    </ListboxOption>
                  </ListboxOptions>
                </transition>
              </div>
            </Listbox>
          </div>

          <DataSearchForm
              :placeholder="__('Search')"
              :search-data-route-name="route('admin.languages.show', props.language.id)"
              :searchable-columns="searchableColumns"/>

        </div>

        <AlertInfo v-if="items.length < 1" class="mb-2">
          <div class="text-sm text-blue-700" v-text="__('No results found.')">
          </div>
        </AlertInfo>

        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
          <tr>
            <th class="px-6 py-3 ltr:text-left rtl:text-right" scope="col">{{ __('Key') }}</th>
            <th class="px-6 py-3 ltr:text-left rtl:text-right" scope="col">{{ __('Value') }}</th>
            <th class="px-6 py-3 ltr:text-left rtl:text-right" scope="col"></th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(value, key) in flattenedTranslations"
              :key="key"
              class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 group transition duration-150">

            <td class="px-6 py-4 w-4 text-gray-600 text-sm min-w-[300px] break-words">
              {{ key }}
            </td>

            <td class="px-6 py-4 w-4">
              <div class="flex flex-col items-start min-w-[300px] lg:min-w-[450px]">
                <TextArea :value="value" class="w-full" size="5" @change="translationLineChanged(key, value, $event)"/>
              </div>
            </td>

            <td>
              <button v-tooltip="__('Delete')"
                      class="rtl:pl-4 ltr:pr-4 hover:text-red-600 md:hidden md:group-hover:block"
                      @click="deleteLanguageLine(activeGroup, key)">
                <TrashIcon class="w-5 h-5 stroke-current"/>
              </button>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </Layout>
</template>

<style scoped>

</style>
