<script setup>
import {inject} from "vue";
import {Bars3Icon} from "@heroicons/vue/24/outline/index.js";
import ProfileMenu from "@peak/Layouts/Admin/Components/ProfileMenu.vue";
import SearchForm from "@peak/Layouts/Admin/Components/SearchForm.vue";
import HeaderWidgets from "@peak/Layouts/Admin/Components/HeaderWidgets.vue";

const emitter = inject('emitter');

const openMobileSidebar = () => {
  emitter.emit('openMobileSidebar');
};
</script>

<template>
  <div
      id="header"
      class="sticky top-0 z-40 flex h-[67px] shrink-0 items-center gap-x-4 bg-white px-4 sm:gap-x-6 sm:px-6 lg:px-8">

    <button class="-m-2.5 p-2.5 text-gray-700 lg:hidden" type="button" @click="openMobileSidebar">
      <span class="sr-only">Open sidebar</span>
      <Bars3Icon aria-hidden="true" class="h-6 w-6"/>
    </button>

    <!-- Separator -->
    <div aria-hidden="true" class="h-6 w-px bg-gray-900/10 lg:hidden"/>

    <div class="flex flex-1 gap-x-4 justify-between md:justify-start md:self-stretch lg:gap-x-6">
      <SearchForm/>

      <div class="block md:hidden"></div>

      <div class="flex items-center gap-x-4 lg:gap-x-6">
        <div class="flex gap-x-7 items-center">
          <HeaderWidgets/>
        </div>

        <!-- Separator -->
        <div aria-hidden="true" class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-900/10"/>

        <!-- Profile dropdown -->
        <ProfileMenu/>
      </div>
    </div>
  </div>
</template>

<style scoped>
#header {
  box-shadow: 0 0.46875rem 2.1875rem rgba(4, 9, 20, .01), 0 0.9375rem 1.40625rem rgba(4, 9, 20, .01), 0 0.25rem 0.53125rem rgba(4, 9, 20, .03), 0 0.125rem 0.1875rem rgba(4, 9, 20, .01);
}
</style>
