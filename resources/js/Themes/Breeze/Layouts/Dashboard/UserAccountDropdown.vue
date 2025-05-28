<script setup>

import {Link, router} from "@inertiajs/vue3";
import {ChevronUpDownIcon} from '@heroicons/vue/20/solid';
import {Menu, MenuButton, MenuItem, MenuItems,} from '@headlessui/vue';
import {useAuth} from "@peak/Composables/useAuth.js";
import {useFeature} from "@peak/Composables/useFeature.js";

const {featureEnabled} = useFeature();
const {hasRole, isAdmin, user} = useAuth();

const switchToTeam = (team) => {
  router.put(route('current-team.update'), {
    team_id: team.id,
  }, {
    preserveState: false,
  });
};

</script>

<template>
  <Menu as="div" class="relative inline-block px-3 text-left">
    <div>
      <MenuButton
          class="group w-full rounded-md border border-zinc-200 bg-zinc-100 px-3.5 py-2 text-left text-sm font-medium text-gray-700 hover:bg-zinc-200/70 focus:outline-hidden focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 focus:ring-offset-gray-100">
              <span class="flex w-full items-center justify-between">
                <span class="flex min-w-0 items-center justify-between gap-x-3">
                  <img :alt="user.name" :src="user.profile_photo_url"
                       class="size-10 shrink-0 rounded-full bg-gray-300 object-cover"/>
                  <span class="flex min-w-0 flex-1 rtl:pr-1 flex-col">
                    <span class="truncate text-sm rtl:text-right font-medium text-gray-900">
                      {{ user.name }}
                    </span>
                    <span v-if="user.username"
                          class="truncate text-sm rtl:text-right text-gray-500">
                      @{{ user.username }}
                    </span>
                  </span>
                </span>

                <ChevronUpDownIcon aria-hidden="true" class="size-4 shrink-0 text-gray-600 group-hover:text-gray-500"/>
              </span>
      </MenuButton>
    </div>

    <transition enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
      <MenuItems
          class="absolute left-0 right-0 z-10 mx-3 mt-1 origin-top divide-y divide-gray-200 rounded-md bg-white shadow-lg focus:outline-hidden">
        <div class="py-1">
                <span
                    class="font-bold text-ellipsis overflow-hidden whitespace-nowrap text-sm block rtl:text-right px-4 py-2">
                                    {{ user.email }}
                </span>
        </div>

        <div class="py-1">
          <MenuItem v-if="isAdmin" v-slot="{ active }">
            <a
                :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700', 'block rtl:text-right px-4 py-2 text-sm']"
                :href="route('admin.dashboard')">
              {{ __('Administration') }}
            </a>
          </MenuItem>

          <MenuItem v-slot="{ active }">
            <Link
                :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700', 'block rtl:text-right px-4 py-2 text-sm']"
                :href="route('dashboard.account.profile')">
              {{ __('Account Settings') }}
            </Link>
          </MenuItem>

          <MenuItem v-if="featureEnabled('user_notifications')" v-slot="{ active }">
            <Link
                :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700', 'block rtl:text-right px-4 py-2 text-sm']"
                :href="route('dashboard.account.notifications.index')">
              {{ __('Notifications') }}
            </Link>
          </MenuItem>
        </div>

        <div v-if="featureEnabled('teams') && $page.props.front.teams_management_enabled" class="py-1">
          <div class="block px-4 py-2 ltr:text-left rtl:text-right text-xs text-gray-400">
            {{ __('Manage Team') }}
          </div>

          <MenuItem v-if="user.current_team" v-slot="{ active }">
            <Link
                :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700', 'block rtl:text-right px-4 py-2 text-sm']"
                :href="route('teams.show', user.current_team)">
              {{ __('Team Settings') }}
            </Link>
          </MenuItem>

          <MenuItem v-if="$page.props.jetstream.canCreateTeams" v-slot="{ active }">
            <Link
                :class="[active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700', 'block rtl:text-right px-4 py-2 text-sm']"
                :href="route('teams.create')">
              {{ __('Create New Team') }}
            </Link>
          </MenuItem>

          <template v-if="user.all_teams.length > 1">
            <div class="border-t border-gray-200 dark:border-gray-600"/>

            <div class="block px-4 ltr:text-left rtl:text-right py-2 text-xs text-gray-400">
              {{ __('Switch Teams') }}
            </div>

            <template v-for="team in user.all_teams"
                      :key="team.id">
              <form @submit.prevent="switchToTeam(team)">
                <MenuItem as="button" class="text-gray-700 block rtl:text-right px-4 py-2 text-sm">
                  <div class="flex items-center">
                    <svg
                        v-if="team.id == user.current_team_id"
                        class="me-2 h-5 w-5 text-green-400"
                        fill="none" stroke="currentColor"
                        stroke-width="1.5" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                      <path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                            stroke-linejoin="round"/>
                    </svg>

                    <div>
                      {{ team.name }}
                    </div>
                  </div>
                </MenuItem>
              </form>
            </template>
          </template>
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
</template>

<style scoped>

</style>