<script setup>
import {onMounted, ref} from "vue";
import {usePage} from "@inertiajs/vue3";

const scrolled = ref(false);
const toc = ref([]);

const handleScroll = () => {
  scrolled.value = window.scrollY > 0;
};

// Map tags to numerical levels for sorting
const tagLevelMap = {
  h2: 2,
  h3: 3,
};

onMounted(() => {
  window.addEventListener("scroll", handleScroll);

  // Get the data from Inertia props
  const tocData = usePage().props.doc.headings;

  // Ensure tocData is an array before filtering
  toc.value = Array.isArray(tocData)
      ? tocData
          .filter(item => tagLevelMap[item.tag] && item.anchor !== null) // Include only h2 and h3
          .map(item => ({
            anchor: item.anchor,
            lvl: tagLevelMap[item.tag], // Assign level based on the tag
            content: item.text,
          }))
          .sort((a, b) => a.lvl - b.lvl) // Sort by level
      : []; // Default to an empty array if tocData is not an array
});

const getClassForTocItem = level =>
    level === 2 ? "ml-0" : level === 3 ? "ml-3.5" : "";
</script>

<template>
  <div
      id="toc-sidebar"
      class="sticky z-20 top-[0px] ease-out duration-300 bottom-0 right-0 w-56 shrink-0 h-dvh lg:block hidden"
  >
    <div class="md:h-dvh overflow-y-auto custom-scrollbar">
      <div v-if="toc.length > 0" class="hidden px-5 rounded-lg lg:block py-5">
        <h5 class="mb-4 text-sm font-semibold leading-6 text-neutral-900 dark:text-neutral-100">
          {{ __('On this page') }}
        </h5>

        <ul id="table-of-contents" class="text-sm leading-6 toc">
          <li v-for="tocItem in toc" :key="tocItem.slug"
              :class="getClassForTocItem(tocItem.lvl)"
          >
            {{ tocItem.label }}
            <a
                :href="'#' + tocItem.anchor"
                class="block py-1 hover:text-black dark:hover:text-neutral-100 dark:in-[.active]:text-white in-[.active]:text-black"
            >
              {{ tocItem.content }}
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
