<script setup>

import {
  Dialog,
  DialogPanel,
  Disclosure,
  DisclosureButton,
  DisclosurePanel,
  TransitionChild,
  TransitionRoot
} from "@headlessui/vue";

import {ChevronRightIcon} from "@heroicons/vue/20/solid/index.js";
import {XMarkIcon} from "@heroicons/vue/24/outline/index.js";
import {Link, usePage} from "@inertiajs/vue3";
import {inject, onMounted, ref} from "vue";
import {GaugeIcon, LifeBuoy} from "lucide-vue-next";
import MobileLogo from "@/Themes/Breeze/Layouts/Dashboard/MobileLogo.vue";
import {__} from "@peak/Composables/useTranslate.js";
import {useFeature} from "@peak/Composables/useFeature.js";

const {featureEnabled, featureDisabled} = useFeature();

const navigation = ref([
  {
    name: __('Dashboard'),
    route: route('dashboard.index'),
    icon: GaugeIcon,
    current: route().current('dashboard.index'),
  },
]);

const sidebarOpen = ref(false);
const page = usePage();
const emitter = inject('emitter');

onMounted(() => {
  emitter.on('openSidebar', () => {
    sidebarOpen.value = true;
  });
});

</script>

<template>
  <TransitionRoot :show="sidebarOpen" as="template">
    <Dialog class="relative z-40" @close="sidebarOpen = false">
      <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                       enter-from="opacity-0" enter-to="opacity-100"
                       leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                       leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-600/75"/>
      </TransitionChild>

      <div class="fixed inset-0 z-40 flex">
        <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                         enter-from="ltr:-translate-x-full rtl:translate-x-full" enter-to="translate-x-0"
                         leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                         leave-to="ltr:-translate-x-full rtl:translate-x-full">
          <DialogPanel class="relative flex w-full max-w-xs flex-1 flex-col bg-white pb-4 pt-5">
            <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0"
                             enter-to="opacity-100" leave="ease-in-out duration-300"
                             leave-from="opacity-100" leave-to="opacity-0">
              <div class="absolute right-0 top-0 -mr-12 pt-2">
                <button
                    class="relative ml-1 flex size-10 items-center justify-center rounded-full focus:outline-hidden focus:ring-2 focus:ring-inset focus:ring-white"
                    type="button"
                    @click="sidebarOpen = false">
                  <span class="absolute -inset-0.5"/>
                  <span class="sr-only">Close sidebar</span>
                  <XMarkIcon aria-hidden="true" class="size-6 text-white"/>
                </button>
              </div>
            </TransitionChild>

            <MobileLogo/>

            <div class="mt-5 h-0 flex-1 overflow-y-auto">
              <nav class="px-2">
                <div class="space-y-1">
                  <ul class="flex flex-1 flex-col gap-y-7" role="list">
                    <li>
                      <ul class="space-y-1.5" role="list">
                        <li v-for="item in navigation" :key="item.name">
                          <Link v-if="!item.children"
                                :aria-current="item.current ? 'page' : undefined"
                                :class="[item.current ? 'text-zinc-900 border-zinc-200 dark:border-zinc-700 shadow-xs bg-white font-medium dark:bg-zinc-700/60 dark:text-zinc-100 transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 gap-x-2 overflow-hidden group-hover:autoflow-auto items' : 'border-transparent transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 gap-x-2 overflow-hidden group-hover:autoflow-auto items']"
                                :href="item.route">
                            <component :is="item.icon"
                                       :class="[item.current ? 'text-gray-500' : 'text-gray-600 group-hover:text-gray-500', 'ltr:mr-3 rtl:ml-3 size-5 shrink-0']"
                                       aria-hidden="true"/>
                            {{ item.name }}
                          </Link>

                          <Disclosure v-else v-slot="{ open }"
                                      :default-open="item.current"
                                      as="div">
                            <DisclosureButton
                                :class="[item.current ? 'text-zinc-900 border-zinc-200 shadow-xs bg-white font-medium transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 gap-x-2 overflow-hidden group-hover:autoflow-auto items' : 'border-transparent transition-colors border px-2.5 py-2 flex rounded-lg w-full h-auto text-sm hover:bg-zinc-100 dark:hover:bg-zinc-700/60 justify-start items-center hover:text-zinc-900 dark:hover:text-zinc-100 gap-x-2 overflow-hidden group-hover:autoflow-auto items']">
                              <component :is="item.icon"
                                         aria-hidden="true"
                                         class="size-5 shrink-0 text-gray-600"/>
                              {{ item.name }}
                              <ChevronRightIcon
                                  :class="[open ? 'rotate-90 text-gray-500' : 'rtl:rotate-180 text-gray-600', 'ltr:ml-auto rtl:mr-auto size-5 shrink-0']"
                                  aria-hidden="true"/>
                              <!--                                                                <ChevronLeftIcon :class="[open ? 'rotate-90 text-gray-500' : 'text-gray-600', 'ml-auto size-5 shrink-0']" aria-hidden="true" />-->
                            </DisclosureButton>
                            <DisclosurePanel as="ul" class="mt-1.5 bg-white">
                              <li v-for="subItem in item.children"
                                  :key="subItem.name">
                                <Link
                                    :class="[subItem.current ? 'font-semibold' : 'hover:bg-gray-50', 'block rounded-md py-2 pl-9 pr-2 text-sm/6 text-gray-700']"
                                    :href="subItem.route">
                                  {{ subItem.name }}
                                </Link>
                              </li>
                            </DisclosurePanel>
                          </Disclosure>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </div>
              </nav>
            </div>
          </DialogPanel>
        </TransitionChild>
        <div aria-hidden="true" class="w-14 shrink-0">
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<style scoped>

</style>