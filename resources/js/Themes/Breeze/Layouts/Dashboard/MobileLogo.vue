<script setup>

import {usePage} from "@inertiajs/vue3";

const page = usePage();

</script>

<template>
  <div class="flex shrink-0 items-center px-4">
    <img v-if="page.props.front?.logo_path" :src="page.props.front?.logo_path"
         alt="Your Company" class="h-[30px] w-auto"/>
  </div>
</template>

<style scoped>

</style>