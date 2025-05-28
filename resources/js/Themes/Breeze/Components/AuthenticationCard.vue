<template>
  <PageLayout :title="title">
    <div class="flex w-full flex-col items-center py-20">
      <div v-if="$slots.logo">
        <slot name="logo"/>
      </div>

      <div
          class="w-full sm:max-w-md mt-6 rounded-lg shadow-xl px-6 py-4 bg-white border border-gray-200 overflow-hidden sm:rounded-lg md:p-12">

        <div v-if="$slots.title" class="text-2xl font-black font-heading text-gray-800 text-center pb-9">
          <slot name="title"/>
        </div>

        <slot/>
      </div>

      <slot v-if="$slots.footer" name="footer"/>

    </div>
  </PageLayout>
</template>
<script setup>

defineProps({
  title: String | null
});

import PageLayout from "@/Themes/Breeze/Layouts/Main/PageLayout.vue";
</script>
