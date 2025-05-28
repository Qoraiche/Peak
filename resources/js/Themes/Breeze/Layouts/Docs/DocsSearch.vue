<template>
  <TransitionRoot :show="open" appear as="template" @after-leave="query = '';filteredResults = []">
    <Dialog class="relative" @close="open = false">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                       leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-500/20 backdrop-blur-xs transition-opacity"/>
      </TransitionChild>

      <div class="fixed inset-0 z-50 w-screen overflow-y-auto p-4 sm:p-6 md:p-20">
        <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 scale-95"
                         enter-to="opacity-100 scale-100" leave="ease-in duration-200"
                         leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
          <DialogPanel
              class="mx-auto max-w-xl transform divide-y divide-gray-100 overflow-hidden rounded-lg bg-white shadow-md transition-all ring-0 outline-0 border-none">
            <Combobox @update:modelValue="onSelect">
              <div class="grid grid-cols-1">

                <ComboboxInput
                    :modelValue="query"
                    class="col-start-1 border-0 row-start-1 h-12 w-full ltr:pl-11 rtl:pr-11 block appearance-none bg-transparent py-4 text-base text-slate-900 placeholder:text-slate-600 focus:outline-hidden sm:text-sm/6"
                    placeholder="Search Documentation..."
                    @change="query = $event.target.value"
                    @input="debouncedSearch"
                    @update:modelValue="query = $event.target.value"
                />
                <MagnifyingGlassIcon v-if="!searching"
                                     aria-hidden="true"
                                     class="pointer-events-none col-start-1 row-start-1 ltr:ml-4 rtl:mr-4 size-5 self-center text-gray-400"/>

                <svg v-else
                     class="animate-spin pointer-events-none col-start-1 row-start-1 ltr:ml-4 rtl:mr-4 size-5 self-center text-gray-400"
                     fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                          stroke-width="4"></circle>
                  <path class="opacity-75"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        fill="currentColor"></path>
                </svg>

              </div>

              <ComboboxOptions v-if="filteredResults.length > 0"
                               class="max-h-72 scroll-py-2 overflow-y-auto py-2 text-sm text-gray-800"
                               static>
                <ComboboxOption v-for="doc in filteredResults" :key="doc.id" v-slot="{ active }"
                                :value="doc" as="template">
                  <li :class="['cursor-default select-none px-4 py-2', active && 'bg-blue-600 text-white outline-hidden']">
                    {{ doc.category ? doc.category + ' > ' : null }} {{ doc.title }}
                  </li>
                </ComboboxOption>
              </ComboboxOptions>

              <p v-if="query !== '' && readyResults && filteredResults.length === 0"
                 class="p-4 text-sm text-gray-500">No
                results found.
              </p>
            </Combobox>
          </DialogPanel>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>

import {inject, onMounted, ref} from 'vue';
import {debounce} from 'lodash';
import {MagnifyingGlassIcon} from '@heroicons/vue/20/solid';
import {router} from "@inertiajs/vue3";

import {
  Combobox,
  ComboboxInput,
  ComboboxOption,
  ComboboxOptions,
  Dialog,
  DialogPanel,
  TransitionChild,
  TransitionRoot
} from '@headlessui/vue';

import axios from 'axios';

const route = inject('route');
const indexName = 'docs_index';

// Define state
const open = ref(false);
const query = ref('');
const filteredResults = ref([]);
const readyResults = ref(false);
const searching = ref(false);

// Debounced API search
const debouncedSearch = debounce(async () => {
  if (query.value.trim() !== '') {
    try {

      searching.value = true;

      // Make an API call to your backend search endpoint
      const response = await axios.post(route('docs.search'), {search: query.value}).finally(() => {
        readyResults.value = true;
        searching.value = false;
      });
      filteredResults.value = response.data; // Store the response
    } catch (error) {
      console.error('Search API error:', error);
    }
  } else {
    filteredResults.value = []; // Clear results if query is empty
  }
}, 500); // Debounce for 500ms

// Handle item selection from the search
function onSelect(doc) {
  if (doc) {
    router.visit(doc.url); // Redirect to the selected document
  }
}

// Event listener to open the search modal
const emitter = inject('emitter');
onMounted(() => {
  emitter.on('searchDocs', () => {
    open.value = true;
  });
});
</script>

<style scoped>
.docsearch-input {
  width: 100%;
  padding: 0.5rem;
}
</style>