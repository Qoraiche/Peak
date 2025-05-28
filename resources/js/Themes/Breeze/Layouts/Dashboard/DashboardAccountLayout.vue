<template>
  <div class="min-h-screen mx-auto max-w-7xl lg:flex lg:gap-x-10 rounded-md">
    <h1 class="sr-only">{{ __('General Settings') }}</h1>
    <aside
        class="flex overflow-x-auto border-r pl-3 border-gray-200 bg-zinc-50 py-4 lg:block lg:w-64 lg:flex-none">
      <nav class="flex-none px-4 sm:px-6 lg:px-0">
        <ul class="flex gap-x-3 gap-y-1 whitespace-nowrap lg:flex-col" role="list">
          <li v-if="featureEnabled('user_account_profile') && $page.props.front.profile_management_enabled">
            <Link
                :class="[route().current('dashboard.account.profile') ? 'font-semibold bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50']"
                :href="route('dashboard.account.profile')"
                class="group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm/6 font-medium">

              <UserCircle class="size-5 shrink-0"/>

              {{ __('Profile') }}
            </Link>
          </li>

          <li v-if="featureEnabled('user_account_security')">
            <Link
                :class="[route().current('dashboard.account.security') ? 'font-semibold bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50']"
                :href="route('dashboard.account.security')"
                class="group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm/6 font-medium">

              <Fingerprint class="size-5 shrink-0"/>

              {{ __('Security') }}
            </Link>
          </li>

          <li v-if="featureEnabled('api')">
            <Link
                :class="[route().current('dashboard.account.api-tokens.index') ? 'font-semibold bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50']"
                :href="route('dashboard.account.api-tokens.index')"
                class="group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm/6 font-medium">
              <CloudCog class="size-5 shrink-0"/>
              {{ __('API') }}
            </Link>
          </li>

          <li v-if="user.current_team && featureEnabled('teams') && $page.props.front.teams_management_enabled">
            <Link
                :class="[route().current('teams.show') ? 'font-semibold bg-gray-50 text-blue-600' : 'text-gray-700 hover:text-blue-600 hover:bg-gray-50']"
                :href="route('teams.show', user.current_team)"
                class="group flex gap-x-3 rounded-md py-2 pl-2 pr-3 text-sm/6 font-medium">

              <Users class="size-5 shrink-0"/>
              {{ __('Team') }}
            </Link>
          </li>
        </ul>
      </nav>
    </aside>

    <main class="sm:px-6 py-6 lg:flex-auto">
      <slot/>
    </main>
  </div>
</template>

<script setup>
import {CloudCog, Fingerprint, LucideCreditCard, Package, Share2, UserCircle, Users} from 'lucide-vue-next';
import {Link} from "@inertiajs/vue3";
import {useAuth} from "@peak/Composables/useAuth.js";
import {useFeature} from "@peak/Composables/useFeature.js";

const {hasRole, isAdmin, user} = useAuth();
const {featureEnabled, featureDisabled} = useFeature();

</script>