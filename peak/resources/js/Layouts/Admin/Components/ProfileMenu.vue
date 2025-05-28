<script setup>
import {ChevronDownIcon} from "@heroicons/vue/20/solid/index.js";
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import DropdownLink from "@peak/Components/Admin/DropdownLink.vue";
import {router, usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import DynamicLucideIcon from "@peak/Components/Admin/DynamicLucideIcon.vue";

const logout = () => {
  router.post(route('logout'));
}

const page = usePage();

const navigation = computed(() => page.props.admin.menus.profile_menu);
</script>

<template>
  <Menu as="div" class="relative">
    <MenuButton class="-m-1.5 flex items-center p-1.5">
      <span class="sr-only">Open user menu</span>
      <img :src="$page.props.auth.user.profile_photo_url"
           alt=""
           class="h-10 w-10 object-cover rounded-full bg-gray-50"/>
      <span class="hidden lg:flex lg:items-center">
                  <ChevronDownIcon aria-hidden="true" class="ltr:ml-2 rtl:mr-2 h-5 w-5 text-gray-400"/>
                </span>
    </MenuButton>
    <transition enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
      <MenuItems
          class="absolute ltr:right-0 rtl:left-0 z-10 mt-2.5 w-[220px] ltr:origin-top-right rtl:origin-top-left rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
        <MenuItem v-for="item in navigation" :key="item.slug">
          <DropdownLink :as="item.target === '_blank' || !item.route.includes('admin.') ? 'a' : ''"
                        :href="route(item.route)"
                        :target="item.target === '_blank'">
            <DynamicLucideIcon v-if="item.icon" :icon="item.icon" class="w-4"/>
            {{ item.title }}
          </DropdownLink>
        </MenuItem>

        <div class="border-t border-zinc-100"/>

        <MenuItem>
          <form @submit.prevent="logout">
            <DropdownLink as="button">
              {{ __('Log Out') }}
            </DropdownLink>
          </form>
        </MenuItem>
      </MenuItems>
    </transition>
  </Menu>
</template>

<style scoped>

</style>
