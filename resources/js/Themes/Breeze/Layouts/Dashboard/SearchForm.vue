<script setup>

import {MagnifyingGlassIcon} from "@heroicons/vue/20/solid/index.js";
</script>

<template>
  <div class="mt-6 grid grid-cols-1 px-3">
    <input :aria-label="__('Search')"
           :placeholder="__('Search')"
           class="col-start-1 row-start-1 block w-full py-2 ltr:pl-9 rtl:pr-9 text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 text-sm border rounded-lg bg-zinc-200/70 focus:bg-white duration-50 dark:bg-zinc-950 ease border-zinc-200 dark:border-zinc-700/70 dark:ring-zinc-700/70 focus:ring-3 dark:text-zinc-200 dark:focus:ring-zinc-700/70 dark:focus:border-zinc-700 focus:ring-zinc-200 focus:border-zinc-300 dark:placeholder-zinc-400"
           name="search"
           type="search"/>
    <MagnifyingGlassIcon
        aria-hidden="true"
        class="pointer-events-none col-start-1 row-start-1 ltr:ml-3 rtl:mr-3 size-5 self-center text-gray-600"/>
  </div>
</template>

<style scoped>

</style>