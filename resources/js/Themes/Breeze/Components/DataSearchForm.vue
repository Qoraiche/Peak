<script setup>
import {MagnifyingGlassIcon, XMarkIcon} from "@heroicons/vue/24/outline/index.js";
import {onMounted, ref} from "vue";
import {router} from "@inertiajs/vue3";
import TextInput from "@/Themes/Breeze/Components/TextInput.vue";

const props = defineProps({
  searchDataRouteName: String,
  placeholder: String,
  searchableColumns: Array
})

const searchQuery = ref(null);
const routeParams = Object.assign(route().params, {filter: {search: searchQuery.value}});

const search = () => {

  const params = {...route().params, ...{filter: {search: searchQuery.value}}};

  router.visit(route(route().current(), params), {
    method: 'get',
    preserveState: false,
    preserveScroll: true,
  });
}

const cleanSearch = () => {

  const params = {
    ...route().params,
    ...{
      filter: {
        search: searchQuery.value
      }
    }
  };

  const {filter, ...restParams} = params;

  router.visit(route(route().current(), restParams), {
    method: 'get',
    preserveState: false,
    preserveScroll: true,
  });
}

const searchActive = ref(false);

onMounted(() => {

  const routeParams = route().params;

  if (routeParams.hasOwnProperty('filter')) {
    searchQuery.value = routeParams.filter.search;

    if (searchQuery.value !== null) {
      searchActive.value = true;
    }
  }
});

</script>

<template>
  <div>
    <label class="sr-only" for="table-search">Search</label>
    <form class="relative bg-white"
          @submit.prevent="search">
      <div class="absolute inset-y-0 left-0 flex items-center pl-3 cursor-pointer" @click="search">
        <MagnifyingGlassIcon class="w-5 h-5 text-gray-500"/>
      </div>

      <div
          v-if="searchActive"
          class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-gray-500 hover:text-gray-700"
          @click="cleanSearch">
        <XMarkIcon class="w-5 h-5 stroke-current"/>
      </div>

      <TextInput v-model="searchQuery" :placeholder="placeholder"
                 class="pl-10 bg-gray-50"/>
    </form>
  </div>
</template>

<style scoped>

</style>
