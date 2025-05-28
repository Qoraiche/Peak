<script setup>
import {XMarkIcon} from "@heroicons/vue/24/outline/index.js";

import {Dialog, DialogPanel, TransitionChild, TransitionRoot} from "@headlessui/vue";
import {inject} from "vue";
import Sidebar from "./AdminSidebar.vue";

defineProps({
  sidebarOpen: {
    default: false,
    type: Boolean
  }
});

const emitter = inject('emitter');

const closeSidebar = () => {
  emitter.emit('closeMobileSidebar');
};

</script>

<template>
  <TransitionRoot :show="sidebarOpen" as="template">
    <Dialog as="div" class="relative z-50 lg:hidden" @close="closeSidebar">
      <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                       enter-from="opacity-0" enter-to="opacity-100"
                       leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                       leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-900/80"/>
      </TransitionChild>

      <div class="fixed inset-0 flex">
        <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                         enter-from="ltr:-translate-x-full rtl:translate-x-full"
                         enter-to="translate-x-0"
                         leave="transition ease-in-out duration-300 transform"
                         leave-from="translate-x-0"
                         leave-to="ltr:-translate-x-full rtl:translate-x-full">
          <DialogPanel class="relative ltr:mr-16 rtl:ml-16 flex w-full max-w-xs flex-1">
            <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0"
                             enter-to="opacity-100" leave="ease-in-out duration-300"
                             leave-from="opacity-100" leave-to="opacity-0">
              <div class="absolute ltr:left-full rtl:right-full top-0 flex w-16 justify-center pt-5">
                <button class="-m-2.5 p-2.5" type="button" @click="closeSidebar">
                  <span class="sr-only">Close sidebar</span>
                  <XMarkIcon aria-hidden="true" class="h-6 w-6 text-white"/>
                </button>
              </div>
            </TransitionChild>
            <!-- Sidebar component, swap this element with another sidebar if you like -->
            <Sidebar :mobile="true"/>
          </DialogPanel>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>

</template>

<style scoped>

</style>
