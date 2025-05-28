<script setup>

import {BellIcon} from "@heroicons/vue/24/outline/index.js";
import {Menu, MenuButton, MenuItems} from "@headlessui/vue";
</script>

<template>
  <Menu as="div" class="relative inline-block mt-2">
    <MenuButton
        class="-m-2.5 p-2.5 text-gray-400 relative hover:text-gray-500 rounded-full bg-gray-100 hover:bg-gray-100 transition duration-300">
      <span class="sr-only">View notifications</span>
      <BellIcon aria-hidden="true" class="h-6 w-6"/>

      <span class="flex absolute h-2 w-2 top-1.5 right-1.5 -mt-1 -mr-1">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
    </span>
    </MenuButton>

    <transition enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
      <MenuItems
          class="absolute flex flex-col right-0 z-10 mt-2 w-80 origin-top-right rounded-md bg-white shadow-lg focus:outline-hidden">

        <div
            class="p-2 bg-blue-500 justify-center items-center space-y-1 flex flex-col text-white rounded-t-md">
          <span class="font-bold text-md">Notifications</span>
          <span class="text-sm">You have <b class="font-bold">21</b> unread messages</span>
        </div>

        <div class="relative flex-1">
          <div class="divide-y divide-gray-100 dark:divide-gray-700 overflow-y-auto max-h-96">
            <a class="flex px-2 py-3 bg-gray-50 hover:bg-gray-100"
               href="#">
              <div class="shrink-0">
                <img alt="Joseph image"
                     class="rounded-full w-11 h-11"
                     src="https://flowbite.com/docs/images/people/profile-picture-2.jpg">
              </div>
              <div class="w-full pl-3">
                <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400"><span
                    class="font-semibold text-gray-900 dark:text-white">Joseph Mcfall</span>
                  and <span
                      class="font-medium text-gray-900 dark:text-white">5 others</span>
                  started following
                  you.
                </div>
                <div class="text-xs text-blue-600 dark:text-blue-500">
                  10 minutes ago
                </div>
              </div>
            </a>
          </div>
        </div>
      </MenuItems>
    </transition>
  </Menu>
</template>

<style scoped>

</style>
