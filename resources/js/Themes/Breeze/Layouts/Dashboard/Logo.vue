<script setup>

import {usePage, Link} from "@inertiajs/vue3";

const page = usePage();

</script>

<template>
  <div class="flex shrink-0 items-center">
    <Link :href="route('dashboard.index')">
      <img v-if="page.props.front?.logo_path" id="dashboard-logo" :src="page.props.front?.logo_path"
           alt="Logo" class="h-[30px] w-auto"/>
    </Link>
  </div>
</template>

<style scoped>

</style>