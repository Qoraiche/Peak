<template>
  <nav v-if="breadcrumbs.length" aria-label="Breadcrumb" class="flex px-2 py-2 border rounded-md mb-7 bg-zinc-50">
    <ol class="flex items-center gap-x-3" role="list">
      <!-- Home breadcrumb -->
      <li>
        <div>
          <Link :href="route('admin.dashboard')" class="text-gray-400 hover:text-gray-500">
            <HomeIcon aria-hidden="true" class="size-5 shrink-0"/>
            <span class="sr-only">
              {{ __('Home') }}
            </span>
          </Link>
        </div>
      </li>

      <!-- Dynamic breadcrumbs -->
      <li v-for="(breadcrumb, index) in breadcrumbs" :key="index">
        <div class="flex items-center">
          <!-- If not the last breadcrumb, show the separator -->
          <ChevronRightIcon aria-hidden="true" class="text-gray-400 size-4 rtl:hidden shrink-0"/>
          <ChevronLeftIcon aria-hidden="true" class="text-gray-400 size-4 ltr:hidden shrink-0"/>
          <template v-if="breadcrumb.url && index !== breadcrumbs.length - 1">
            <Link :href="breadcrumb.url"
                  class="text-sm font-medium text-gray-500 ltr:ml-3 rtl:mr-3 hover:text-gray-700">
              {{ __(breadcrumb.title) }}
            </Link>
          </template>
          <template v-else>
                        <span class="text-sm font-medium text-gray-500 ltr:ml-3 rtl:mr-3">
                            {{ __(breadcrumb.title) }}
                        </span>
          </template>
        </div>
      </li>
    </ol>
  </nav>
</template>

<script setup>

import {ChevronLeftIcon, ChevronRightIcon, HomeIcon} from '@heroicons/vue/24/solid';
import {Link, usePage} from '@inertiajs/vue3';

const breadcrumbs = usePage().props.admin.breadcrumbs;

</script>

<style scoped></style>
