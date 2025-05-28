<template>
  <ul>

    <MenuLinks/>

    <li style="height: 30px;"></li>

    <li v-for="(parentMenu, parentIndex) in parentMenus" :key="parentIndex">
      <h5 :class="[parentIndex === 0 ? 'mb-6' : 'my-6']"
          class="lg:mb-3 font-semibold text-slate-900 dark:text-slate-200">
        {{ parentMenu.label }}
      </h5>
      <ul class="space-y-6 lg:space-y-3">
        <li v-for="(item, index) in parentMenu.items" :id="item.id" :key="index">
          <Link
              :class="{'font-semibold text-blue-600!': isActive(item.slug)}"
              :href="item.url"
              class="block pl-4 -ml-px border-l border-slate-200 hover:border-slate-400 dark:hover:border-slate-500 text-slate-700 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-300">
            {{ item.label }}
          </Link>
        </li>
      </ul>
    </li>
  </ul>
</template>

<script setup>

import {ref} from "vue";
import {Link, usePage} from "@inertiajs/vue3";
import MenuLinks from "@/Themes/Breeze/Layouts/Docs/MenuLinks.vue";

const page = usePage();

const parentMenus = ref(page.props?.categoriesWithDocs);

// Check if a route is active
const isActive = (slug) => {
  return page.props.doc?.slug === slug;
};

const toggleCollapse = (index) => {
  // Optional if you want manual toggle behavior
};
</script>

<style scoped>
/* Add custom styles if needed */
</style>
