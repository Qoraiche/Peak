<script setup>
import {inject, onMounted, onUnmounted, ref} from 'vue';
import MenuItems from "@/Themes/Breeze/Layouts/Docs/MenuItems.vue";
import {usePage} from "@inertiajs/vue3";
import {__} from "@peak/Composables/useTranslate.js";
import docsearch from '@docsearch/js';
import '@docsearch/css';

const leftSidebar = ref(false); // Control the state of the sidebar

const scrolled = ref(false);

const page = usePage();

onMounted(() => {
  docsearch({
    container: '#searchinputme',
    appId: page.props.front.api_keys.algolia.app_id,
    apiKey: page.props.front.api_keys.algolia.api_key,
    indexName: page.props.front.api_keys.algolia.index_name,
    placeholder: __('Search docs...')
  })
});

const emitter = inject('emitter');

// Detect scroll and update the `scrolled` state
const handleScroll = () => {
  scrolled.value = window.scrollY > 100; // Adjust threshold as needed
};

// Add and remove scroll event listener
onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});

const quickSearch = () => {
  emitter.emit('searchDocs');
};
</script>

<template>
  <div
      class="hidden lg:block sticky top-[0px] z-20 inset-0 left-[max(0px,calc(50%-45rem))] right-auto w-[19rem] pb-10 pl-8 pr-6 overflow-y-auto">
    <nav id="nav" class="lg:text-sm lg:leading-6 relative pb-20">
      <div class="sticky top-0 -ml-0.5">
        <div class="h-6 dark:bg-slate-900"></div>

        <div v-if="$page.props.front.settings.docs.enabled" id="searchinputme"></div>

        <div class="h-8"></div>
      </div>
      <MenuItems/>
    </nav>
  </div>
</template>

<style>
.DocSearch-Button {
  padding: 0 35px;
  margin: 0;
}
</style>
