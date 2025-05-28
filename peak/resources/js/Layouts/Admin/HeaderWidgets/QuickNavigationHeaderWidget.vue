<script setup>
import {Menu, MenuButton, MenuItems} from "@headlessui/vue";
import DynamicLucideIcon from "@peak/Components/Admin/DynamicLucideIcon.vue";
import DropdownLink from "@peak/Components/Admin/DropdownLink.vue";

const props = defineProps({
  title: String | null,
  icon: String | null,
  navigationMenu: Object | null | Array
});

</script>

<template>
  <Menu as="div" class="relative hidden md:flex">
    <MenuButton
        v-tooltip="__(title)"
        class="-m-2.5 w-11 h-11 flex justify-center items-center p-3 text-gray-400 hover:text-gray-500 rounded-full bg-gray-50 hover:bg-gray-100 transition duration-300">
      <span class="sr-only">{{ __(title) }}</span>
      <DynamicLucideIcon v-if="icon" :icon="icon" size="size-5"/>
    </MenuButton>

    <transition enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
      <MenuItems
          class="absolute flex flex-col ltr:right-0 rtl:left-0 z-10 mt-8 w-80 ltr:origin-top-right rtl:origin-top-left rounded-md bg-white shadow-lg focus:outline-none">
        <div class="p-4 bg-blue-500 justify-center items-center flex flex-col text-white rounded-t-md">
          <span class="font-bold text-sm">{{ __(title) }}</span>
        </div>

        <div id="grid-title" class="grid grid-cols-1 divide-y divide-x divide-gray-200">
          <DropdownLink v-for="item in navigationMenu" :key="item.slug" :as="item.target === '_blank' ? 'a':''"
                        :href="route(item.route)"
                        :target="item.target" class="group">
                        <span v-if="item.icon" :class="{'text-black': item.active}" class="group-hover:text-blue-600">
                            <DynamicLucideIcon :icon="item.icon" size="size-4"/>
                        </span>

            <span :class="{'font-semibold': item.active}" class="group-hover:text-blue-600">
                            {{ __(item.title) }}
                        </span>
          </DropdownLink>
        </div>
      </MenuItems>
    </transition>
  </Menu>
</template>

<style scoped>

</style>
