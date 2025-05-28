<script setup>

import {Link, router, usePage} from "@inertiajs/vue3";
import {Menu, MenuButton, MenuItems} from "@headlessui/vue";
import {BellIcon} from "@heroicons/vue/24/outline/index.js";

const page = usePage();
const languages = page.props?.front.appLanguages;
const currentLanguageCollection = page.props?.currentLanguageCollection;
const filteredLanguages = currentLanguageCollection?.code ? languages?.filter(language => language.code !== currentLanguageCollection.code) : languages;

const changeLanguage = (newLanguageVal) => {
  router.get(route(route().current(), {...route().params, lang: newLanguageVal.code}), {}, {
    onSuccess: () => {
      const html = document.documentElement;
      html.setAttribute('lang', newLanguageVal.code);
      html.setAttribute('dir', newLanguageVal.direction); // Right-to-left for Arabic

      router.visit(route(route().current(), {...route().params}), {
        preserveScroll: true,
        showProgress: false
      });
    }
  });
};
</script>

<template>
  <div class="flex gap-x-2 items-center">
    <Link :href="route('dashboard.account.notifications.index')"
          class="flex w-8 h-8 relative p-1.5 justify-center items-center rounded-full bg-zinc-100 text-left text-sm font-medium text-gray-700 hover:bg-zinc-200/70 focus:outline-hidden focus:ring-2 focus:ring-zinc-500 focus:ring-offset-2 focus:ring-offset-gray-100">
      <BellIcon aria-hidden="true" class="w-full"/>
      
      <span v-if="$page.props.unreadNotificationsCount > 0"
            class="flex absolute h-1.5 w-1.5 top-1.5 right-1.5 -mt-1 -mr-1">
                              <span
                                  class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-red-500"></span>
                        </span>
    </Link>
  </div>
</template>

<style scoped>

</style>
