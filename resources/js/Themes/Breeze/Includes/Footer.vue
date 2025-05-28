<script setup>

import {Menu, MenuButton, MenuItems} from "@headlessui/vue";
import {Link, router, usePage} from "@inertiajs/vue3";
import {useFeature} from "@peak/Composables/useFeature.js";
import {inject} from "vue";

const route = inject('route');


const {featureEnabled, featureDisabled} = useFeature();

const page = usePage();

defineProps({
  theme: {
    type: String,
    default: 'dark',
  }
})
</script>

<template>
  <footer :class="[theme === 'dark' ? 'bg-gray-900' : 'bg-white']">
    <div class="flex flex-col px-6 py-16 mx-auto gap-y-10 overflow-hidden max-w-7xl sm:py-20 lg:px-8">
      <nav aria-label="Footer"
           class="-mb-6 columns-1 sm:flex sm:justify-center sm:gap-x-12">

        <div class="pb-6">
          <a href="#menu"
             class="text-sm font-medium leading-6 text-gray-300 hover:text-gray-400">
            Menu 1
          </a>
        </div>
      </nav>

      <p class="text-xs leading-5 text-center text-zinc-300">
        Footer copyright text
      </p>
    </div>
  </footer>
</template>

<style scoped>

</style>
