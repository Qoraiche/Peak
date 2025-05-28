<template>
  <TransitionRoot :show="open" appear as="template" @after-leave="query = ''">
    <Dialog class="relative z-50" @close="open = false">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                       leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-500/25 transition-opacity"/>
      </TransitionChild>

      <div class="fixed inset-0 z-10 w-screen overflow-y-auto p-4 sm:p-6 md:p-20">
        <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 scale-95"
                         enter-to="opacity-100 scale-100" leave="ease-in duration-200"
                         leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
          <DialogPanel
              class="mx-auto max-w-xl transform divide-y divide-gray-100 overflow-hidden rounded-xl bg-white shadow-2xl transition-all">
            <Combobox v-if="items.length > 0" @update:modelValue="onSelect">
              <div class="grid grid-cols-1">
                <ComboboxInput
                    :modelValue="query"
                    :placeholder="__('Search')"
                    class="col-start-1 border-0 row-start-1 h-12 w-full ltr:pl-11 rtl:pr-11 block appearance-none bg-transparent py-4 text-base text-slate-900 placeholder:text-slate-600 focus:outline-hidden sm:text-sm"
                    @blur="query = ''"
                    @change="query = $event.target.value"
                    @update:modelValue="query = $event.target.value"
                />

                <MagnifyingGlassIcon
                    aria-hidden="true"
                    class="pointer-events-none col-start-1 row-start-1 ltr:ml-4 rtl:mr-4 size-5 self-center text-gray-400"/>
              </div>
              <ComboboxOptions v-if="query !== ''" class="max-h-96 transform-gpu scroll-py-3 overflow-y-auto p-3"
                               static>
                <ComboboxOption v-for="item in items" :key="item.slug" v-slot="{ active }" :value="item"
                                as="template">
                  <li :class="['flex cursor-pointer select-none rounded-xl p-3', active && 'bg-gray-100 outline-none']">
                    <div
                        :class="['flex size-9 flex-none items-center justify-center rounded-lg', item.colorClass]">
                      <DynamicLucideIcon :icon="item.icon" class="size-3 text-white"/>
                    </div>
                    <div class="ltr:ml-4 rtl:mr-4 flex-auto items-center flex-col">
                      <p :class="['text-sm font-medium', active ? 'text-gray-900' : 'text-gray-700']">
                        {{ item.title }}
                      </p>

                      <p :class="['text-sm', active ? 'text-gray-700' : 'text-gray-500']"
                         v-html="__('search.view_results', {query: query, title: item.title})">
                      </p>
                    </div>
                  </li>
                </ComboboxOption>
              </ComboboxOptions>
            </Combobox>

            <div v-else>
              <AlertWarning>
                {{ __('No search resources registered') }}
              </AlertWarning>
            </div>
          </DialogPanel>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import {computed, inject, onMounted, ref} from 'vue'
import {MagnifyingGlassIcon} from '@heroicons/vue/20/solid';

/*import {
    UsersIcon,
} from '@heroicons/vue/24/outline'*/
import {
  Combobox,
  ComboboxInput,
  ComboboxOption,
  ComboboxOptions,
  Dialog,
  DialogPanel,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue';
import {router, usePage} from "@inertiajs/vue3";
import DynamicLucideIcon from "@peak/Components/Admin/DynamicLucideIcon.vue";
import AlertWarning from "@peak/Components/Admin/Alerts/AlertWarning.vue";

const emitter = inject('emitter');

const open = ref(false);
const query = ref('');
const page = usePage();

const items = computed(() => page.props.admin.searchResources);

function onSelect(item) {
  if (item) {
    router.visit(route(item.resourceRoute, {
      filter: {[item.searchParam]: query.value},
    }));
  }
}

onMounted(() => {
  emitter.on('search:init', (queryVal) => {
    query.value = queryVal;
    open.value = true;
  });
});

</script>
