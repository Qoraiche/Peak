<template>

  <Head :title="title"/>

  <ImpersonationBanner/>

  <div class="min-h-full">

    <!-- Mobile sidebar -->
    <MobileNavigation/>

    <!-- Static sidebar for desktop -->
    <div
        class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col lg:rtl:border-l lg:ltr:border-r lg:border-gray-200 lg:bg-zinc-50 lg:pb-4 lg:pt-5">
      <div class="flex justify-between items-center px-3">
        <Logo/>
        <HeaderWidgets/>
      </div>

      <!-- Sidebar component, swap this element with another sidebar if you like -->
      <Sidebar/>
    </div>

    <!-- Main column -->
    <div class="flex flex-col lg:rtl:pr-64 lg:ltr:pl-64">
      <!-- Search header -->
      <div class="sticky top-0 z-10 flex h-16 shrink-0 border-b border-gray-200 bg-white lg:hidden">
        <button
            class="border-r border-gray-200 px-4 text-gray-500 focus:outline-hidden focus:ring-2 focus:ring-inset focus:ring-zinc-500 lg:hidden"
            type="button"
            @click="openSidebar">
          <span class="sr-only">
            {{ __('Open sidebar') }}
          </span>
          <Bars3CenterLeftIcon aria-hidden="true" class="size-6"/>
        </button>

        <div class="flex flex-1 justify-between px-4 sm:px-6 lg:px-8">
          <form action="#" class="grid w-full flex-1 grid-cols-1" method="GET">
            <input :placeholder="__('Search')"
                   aria-label="Search"
                   class="col-start-1 row-start-1 block size-full rounded-md bg-white py-2 ltr:pl-8 rtl:pr-8 pr-3 text-base border-0 focus:outline-0 focus:ring-0 text-gray-900 outline-hidden placeholder:text-gray-600 sm:text-sm/6"
                   name="search"
                   type="search"/>
            <MagnifyingGlassIcon
                aria-hidden="true"
                class="pointer-events-none col-start-1 row-start-1 size-5 self-center text-gray-600"/>
          </form>
          <div class="flex items-center">

            <HeaderWidgets/>

            <!-- Profile dropdown -->
            <Menu as="div" class="relative ps-3">
              <div>
                <MenuButton
                    class="relative flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-hidden focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2">
                  <span class="absolute -inset-1.5"/>
                  <span class="sr-only">
                    {{ __('Open user menu') }}
                  </span>
                  <img :alt="usePage().props.auth?.user?.name"
                       :src="usePage().props.auth?.user?.profile_photo_url"
                       class="size-8 rounded-full"/>
                </MenuButton>
              </div>
              <transition enter-active-class="transition ease-out duration-100"
                          enter-from-class="transform opacity-0 scale-95"
                          enter-to-class="transform opacity-100 scale-100"
                          leave-active-class="transition ease-in duration-75"
                          leave-from-class="transform opacity-100 scale-100"
                          leave-to-class="transform opacity-0 scale-95">
                <MenuItems
                    class="absolute ltr:right-0 rtl:left-0 z-10 mt-2 w-48 ltr:origin-top-right rtl:origin-top-left divide-y divide-gray-200 rounded-md bg-white shadow-lg focus:outline-hidden">

                  <div class="py-1">
                    <MenuItem v-if="isAdmin" v-slot="{ active }">
                      <a
                          :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700', 'block rtl:text-right px-4 py-2 text-sm']"
                          :href="route('admin.dashboard')">
                        {{ __('Admin Dashboard') }}
                      </a>
                    </MenuItem>
                    <MenuItem v-if="featureEnabled('user_account_profile')" v-slot="{ active }">
                      <Link
                          :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700', 'block rtl:text-right px-4 py-2 text-sm']"
                          :href="route('dashboard.account.profile')">
                        {{ __('Profile') }}
                      </Link>
                    </MenuItem>
                    <MenuItem v-if="featureEnabled('billing')" v-slot="{ active }">
                      <Link
                          :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700', 'block rtl:text-right px-4 py-2 text-sm']"
                          :href="route('dashboard.account.billing.index')">
                        {{ __('Billing') }}
                      </Link>
                    </MenuItem>
                    <MenuItem v-if="featureEnabled('user_account_security')" v-slot="{ active }">
                      <Link
                          :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700', 'block rtl:text-right px-4 py-2 text-sm']"
                          :href="route('dashboard.account.notifications.index')">
                        {{ __('Notifications') }}
                      </Link>
                    </MenuItem>
                  </div>

                  <div class="py-1">
                    <MenuItem v-if="featureEnabled('support_tickets')" v-slot="{ active }">
                      <Link
                          :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700', 'block rtl:text-right px-4 py-2 text-sm']"
                          :href="route('dashboard.support.tickets.index')">
                        {{ __('Support') }}
                      </Link>
                    </MenuItem>
                  </div>
                  <div class="py-1">
                    <MenuItem v-slot="{ active }">
                      <Link
                          :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700', 'w-full ltr:text-left rtl:text-right block rtl:text-right px-4 py-2 text-sm']"
                          :href="route('logout')"
                          method="post">
                        {{ __('Logout') }}
                      </Link>
                    </MenuItem>
                  </div>
                </MenuItems>
              </transition>
            </Menu>
          </div>
        </div>
      </div>
      <main class="flex-1">
        <!-- Page title & actions -->
        <div
            class="border-b border-gray-200 px-4 py-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-6">
          <div class="min-w-0 flex-1">
            <div class="space-y-0.5">
              <h3 class="text-lg sm:text-xl font-semibold tracking-tight dark:text-zinc-100">
                {{ title ?? __('Page title') }}</h3>
              <p v-if="description" class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">
                {{ description }}
              </p>
            </div>
          </div>
          <div v-if="$slots.actions" class="mt-4 flex sm:ltr:ml-4 sm:rtl:mr-4 sm:mt-0">
            <slot name="actions"/>
          </div>
        </div>

        <FlashBanner/>
        <Errors/>

        <div
            :class="{'py-5 sm:py-6 lg:py-8 px-5' : withSpace}">

          <slot/>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import {inject} from 'vue';
import {Menu, MenuButton, MenuItem, MenuItems,} from '@headlessui/vue';

import {Bars3CenterLeftIcon} from '@heroicons/vue/24/outline';
import {MagnifyingGlassIcon} from '@heroicons/vue/20/solid'
import {Head, Link, usePage} from "@inertiajs/vue3";
import ImpersonationBanner from "@/Themes/Breeze/Includes/ImpersonationBanner.vue";
import HeaderWidgets from "@/Themes/Breeze/Components/Dashboard/HeaderWidgets.vue";
import {useAuth} from "@peak/Composables/useAuth.js";
import Sidebar from "@/Themes/Breeze/Layouts/Dashboard/Sidebar.vue";
import Logo from "@/Themes/Breeze/Layouts/Dashboard/Logo.vue";
import MobileNavigation from "@/Themes/Breeze/Layouts/Dashboard/MobileNavigation.vue";
import Errors from "@/Themes/Breeze/Includes/Errors.vue";
import FlashBanner from "@peak/Components/Admin/FlashBanner.vue";
import {useFeature} from "@peak/Composables/useFeature.js";

const {isAdmin} = useAuth();
const {featureEnabled, featureDisabled} = useFeature();

const props = defineProps({
  title: String | null,
  description: String | null,
  withSpace: {
    default: true,
    type: Boolean
  }
});

const page = usePage();
const emitter = inject('emitter');

const openSidebar = () => {
  emitter.emit('openSidebar');
}
</script>
