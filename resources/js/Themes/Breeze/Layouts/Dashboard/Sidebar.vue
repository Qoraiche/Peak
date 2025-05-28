<script setup>

import UserAccountDropdown from "@/Themes/Breeze/Layouts/Dashboard/UserAccountDropdown.vue";
import SearchForm from "@/Themes/Breeze/Layouts/Dashboard/SearchForm.vue";
import Navigation from "@/Themes/Breeze/Layouts/Dashboard/Navigation.vue";
</script>

<template>
  <div class="mt-5 flex h-0 flex-1 flex-col overflow-y-auto pt-1">
    <!-- User account dropdown -->
    <UserAccountDropdown/>

    <!-- Sidebar Search -->
    <SearchForm/>

    <!-- Navigation -->
    <Navigation/>
  </div>
</template>

<style scoped>

</style>