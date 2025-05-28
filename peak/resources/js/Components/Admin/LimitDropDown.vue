<script setup>

import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {ChevronDownIcon} from "@heroicons/vue/24/outline/index.js";
import {router} from "@inertiajs/vue3";
import {onMounted, ref} from "vue";

const selectedLimit = ref('25');

onMounted(() => {

  // check if route().params has a limit value, if yes pass it to selectedLimit value
  if (route().params.hasOwnProperty('limit')) {
    const supportedLimitValues = ['25', '50', '100'];
    const routeLimitParam = route().params.limit;

    if (supportedLimitValues.includes(routeLimitParam)) {
      selectedLimit.value = routeLimitParam;
    }
  }
})

const selectLimit = limit => {

  const params = {
    limit: limit
  };

  let options = Object.assign(route().params, params);

  router.visit(route(route().current(), options), {
    method: 'get',
    preserveState: false,
    preserveScroll: true,
  });
}

</script>

<template>
  <Menu as="div" class="relative inline-block text-left">
    <div>
      <MenuButton
          class="inline-flex items-center border border-gray-200 font-medium text-gray-500 py-2.5 px-5 bg-gray-50 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 rounded-lg text-sm">
        {{ selectedLimit }}
        <ChevronDownIcon aria-hidden="true" class="w-2.5 h-2.5 ltr:ml-2.5 rtl:mr-2.5"/>
      </MenuButton>
    </div>

    <transition enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
      <MenuItems
          class="absolute left-0 z-10 mt-2 w-36 origin-top-left rounded-md bg-white shadow-lg ring-opacity-5 focus:outline-none">
        <div class="py-1">
          <MenuItem v-slot="{ active }" @click="selectLimit(25)">
            <a :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']" href="#"
               @click="selectLimit(25)">25</a>
          </MenuItem>

          <MenuItem v-slot="{ active }" @click="selectLimit(50)">
            <a :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']" href="#"
               @click="selectLimit(50)">50</a>
          </MenuItem>

          <MenuItem v-slot="{ active }" @click="selectLimit(100)">
            <a :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']" href="#"
               @click="selectLimit(100)">100</a>
          </MenuItem>
        </div>
      </MenuItems>
    </transition>
  </Menu>
</template>

<style scoped>

</style>
