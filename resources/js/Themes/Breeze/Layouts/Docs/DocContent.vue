<template>
  <!--  -->
  <div id="docs-content" class="flex items-start md:ltr:ml-5 md:rtl:mr-5 w-full">
    <!-- Main Content -->
    <div class="mx-auto mt-1.5 mr-0 grow md:pr-5 lg:mr-5 lg:pr-0">
      <article
          class="w-full max-w-full pt-3.5 text-[15px] prose-a:font-semibold leading-[23px] prose break-words text-zinc-900 dark:text-neutral-300 lg:prose-md dark:prose-invert prose-a:text-blue-500 hover:prose-a:text-blue-600 prose-a:no-underline
prose-pre:px-4 break-all prose-pre:py-[0.875rem] prose-pre:bg-neutral-800 prose-pre:text-white prose-pre:rounded-lg prose-pre:p-4 prose-pre:overflow-x-auto prose-pre:shadow-md
prose-pre:max-w-full prose-pre:whitespace-pre-wrap prose-pre:break-words">
        <slot/>
      </article>
    </div>
    <DocsHeading/>
  </div>
</template>

<script setup>
import DocsHeading from "@/Themes/Breeze/Layouts/Docs/DocsHeading.vue";
</script>

<style>

</style>
