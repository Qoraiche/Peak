<script setup>
import {ref} from 'vue';
import MenuItems from "@/Themes/Breeze/Layouts/Docs/MenuItems.vue";
import {Dialog, DialogPanel, TransitionChild, TransitionRoot} from "@headlessui/vue";
import {Bars3CenterLeftIcon, XMarkIcon} from "@heroicons/vue/24/outline/index.js";

const sidebarOpen = ref(false);
</script>

<template>

  <div class="flex flex-col relative">
    <div class="md:ltr:ml-5 md:rtl:mr-5 pt-3">
      <button class="border-r pr-2 border-gray-200 text-gray-500 focus:outline-hidden focus:ring-2 focus:ring-inset focus:ring-zinc-500 lg:hidden"
              type="button"
              @click="sidebarOpen = true">
        <span class="sr-only">Open sidebar</span>
        <Bars3CenterLeftIcon aria-hidden="true" class="size-6"/>
      </button>
    </div>
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
                  <button class="relative ml-1 flex size-10 items-center justify-center rounded-full focus:outline-hidden focus:ring-2 focus:ring-inset focus:ring-white"
                          type="button"
                          @click="sidebarOpen = false">
                    <span class="absolute -inset-0.5"/>
                    <span class="sr-only">Close sidebar</span>
                    <XMarkIcon aria-hidden="true" class="size-6 text-white"/>
                  </button>
                </div>
              </TransitionChild>

              <div class="mt-1 pl-5 h-0 flex-1 overflow-y-auto">
                <MenuItems/>
              </div>
            </DialogPanel>
          </TransitionChild>
          <div aria-hidden="true" class="w-14 shrink-0">
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </div>
</template>

<style scoped>

</style>
