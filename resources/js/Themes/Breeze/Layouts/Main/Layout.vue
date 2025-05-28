<script setup>

import Header from "@/Themes/Breeze/Includes/Header.vue";
import Footer from "@/Themes/Breeze/Includes/Footer.vue";
import Hero from "@/Themes/Breeze/Includes/Hero.vue";
import AnnouncementBanner from "@/Themes/Breeze/Includes/AnnouncementBanner.vue";
import {Head} from "@inertiajs/vue3";

defineProps({
  title: String,
});

</script>

<template>
  <Head :title="title"/>

  <div id="hero-section" class="bg-yellow-300">
    <AnnouncementBanner/>

    <Header/>
    <Hero/>
  </div>

  <slot/>

  <Footer/>
</template>

<style scoped>

</style>
