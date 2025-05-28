<script setup>
import Input from "@peak/Components/Admin/Input.vue";
// import {MagnifyingGlassIcon, XMarkIcon} from "@heroicons/vue/24/outline/index.js";
import {Search, X} from 'lucide-vue-next';
import {onMounted, ref} from "vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
  searchDataRouteName: String,
  placeholder: String,
  filterName: {
    type: String,
    default: 'search'
  }
});

const searchQuery = ref(null);
const routeParams = Object.assign(route().params, {filter: {[props.filterName]: searchQuery.value}});

const search = () => {

  if (!searchQuery.value || searchQuery.value === '') {
    return;
  }

  const encodedQuery = encodeURIComponent(searchQuery.value);

  const params = {...route().params, ...{filter: {[props.filterName]: encodedQuery}}};

  router.visit(route(route().current(), params), {
    method: 'get',
    preserveState: false,
    preserveScroll: true
  });
}

const cleanSearch = () => {

  const encodedQuery = encodeURIComponent(searchQuery.value);

  const params = {...route().params, ...{filter: {[props.filterName]: encodedQuery}}};

  const {filter, ...restParams} = params;

  router.visit(route(route().current(), restParams), {
    method: 'get',
    preserveState: false,
    preserveScroll: true
  });
}

const searchActive = ref(false);

onMounted(() => {
  const routeParams = route().params;

  if (routeParams.hasOwnProperty('filter')) {
    // Decode the value before setting it in the input field
    searchQuery.value = decodeURIComponent(routeParams.filter[props.filterName]);

    if (searchQuery.value !== null) {
      searchActive.value = true;
    }
  }
});

</script>

<template>
  <div>
    <label class="sr-only" for="table-search">Search</label>
    <form class="relative"
          @submit.prevent="search">
      <div class="absolute inset-y-0 left-0 flex items-center pl-3 cursor-pointer" @click="search">
        <Search class="w-5 h-5 text-gray-500"/>
      </div>

      <div
          v-if="searchActive"
          class="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer text-gray-500 hover:text-gray-700"
          @click="cleanSearch">
        <X class="w-5 h-5 stroke-current"/>
      </div>

      <Input v-model="searchQuery" :class="{'ltr:pl-10 rtl:pr-10': searchActive}"
             :placeholder="placeholder"
             class="pl-10 py-2.5 bg-gray-50/60"/>
    </form>
  </div>
</template>

<style scoped>

</style>
