<script setup>
import {MagnifyingGlassIcon} from "@heroicons/vue/20/solid/index.js";
import {inject, onUnmounted, ref} from "vue";
import debounce from "lodash/debounce";

const emitter = inject("emitter");
const query = ref('');

// Debounced search function
const search = debounce(() => {
  if (query.value !== '') {
    emitter.emit("search:init", query.value);
  }
}, 600); // Adjust debounce delay as needed (300ms here)

// Ensure to cancel debounce when the component is destroyed
onUnmounted(() => {
  search.cancel();
});

</script>

<template>
  <div class="relative flex flex-1">
    <label class="sr-only" for="search-field">
      {{ __('Search') }}
    </label>
    <MagnifyingGlassIcon
        aria-hidden="true"
        class="pointer-events-none absolute inset-y-0 ltr:left-0 rtl:right-0 h-full w-5 text-gray-400"/>

    <input
        id="search-field"
        v-model="query"
        :placeholder="__('Search')"
        class="block h-full w-full border-0 py-0 ltr:pl-8 ltr:pr-0 rtl:pr-8 text-gray-900 placeholder:text-gray-500 focus:ring-0 sm:text-sm"
        name="search"
        type="search"
        @input="search"
    />
  </div>
</template>

<style scoped></style>
