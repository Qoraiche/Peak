<script setup>

import {Menu, MenuButton, MenuItems} from "@headlessui/vue";
import {router, usePage} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";

const props = defineProps({
  title: {
    type: String,
    default: null,
  },
  icon: {
    type: String,
    default: null,
  },
  currentLanguage: {
    type: [Object],
    default: null,
  },
  languages: {
    type: [Object, Array],
    default: []
  }
});

const toast = useToast();
const page = usePage();
const languages = props.languages;
const currentLanguageCollection = props.currentLanguage;
const filteredLanguages = currentLanguageCollection?.code ? languages?.filter(language => language.code !== currentLanguageCollection.code) : languages;

const switchLanguage = (newLanguageVal) => {
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
  <Menu v-if="languages.length > 1" as="div" class="relative inline-block">
    <MenuButton
        v-tooltip="__(title)"
        class="-m-2.5 w-11 h-11 p-1 text-gray-400 hover:text-gray-500 rounded-full bg-red-50 hover:bg-red-100 transition duration-300"
        type="button">
      <span class="sr-only">
        {{ __('View notifications') }}
      </span>

      <span class="rounded-full w-full emoji">
                {{ currentLanguageCollection?.flag_emoji ?? '🏳️' }}
            </span>

    </MenuButton>

    <transition v-if="filteredLanguages.length > 0" enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
      <MenuItems
          class="absolute flex flex-col ltr:right-0 rtl:left-0 z-10 mt-2 w-48 origin-top-left rounded-md bg-white shadow-lg border focus:outline-none">
        <div class="flex flex-col justify-center items-start w-full">
          <button v-for="language in filteredLanguages"
                  class="flex gap-x-3 items-center p-2.5 hover:bg-blue-50/50 w-full text-sm"
                  @click="switchLanguage(language)">
            <span class="emoji">{{ language.flag_emoji }}</span>
            <span>{{ __(language.name) }}</span>
          </button>
        </div>
      </MenuItems>
    </transition>
  </Menu>
</template>

<style scoped>

</style>
