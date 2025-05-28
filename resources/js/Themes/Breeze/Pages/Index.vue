<script setup>
import Cta from "@/Themes/Breeze/Includes/Cta.vue";
import Faqs from "@/Themes/Breeze/Includes/Faqs.vue";
import Features from "@/Themes/Breeze/Includes/Features.vue";
import FromBlog from "@/Themes/Breeze/Includes/FromBlog.vue";
import LogoClouds from "@/Themes/Breeze/Includes/LogoClouds.vue";
import Pricing from "@/Themes/Breeze/Includes/Pricing.vue";
import NewsletterSubscribe from "@/Themes/Breeze/Includes/NewsletterSubscribe.vue";
import Testimonials from "@/Themes/Breeze/Includes/Testimonials.vue";
import Layout from "@/Themes/Breeze/Layouts/Main/Layout.vue";
import {useFeature} from "@peak/Composables/useFeature.js";

const {featureEnabled} = useFeature();

defineProps({
  title: {
    type: String,
    default: null,
  }
});
</script>

<template>
  <Layout :title="title">
    <LogoClouds/>
    <Features/>
    <Testimonials/>
    <Pricing/>
    <Faqs/>
    <Cta/>
  </Layout>
</template>

<style>

</style>
