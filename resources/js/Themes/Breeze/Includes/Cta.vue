<script setup>

import {Link, usePage} from "@inertiajs/vue3";

const page = usePage();
const front = page.props.front.hero;

import { useScrollReveal } from '@peak/Composables/useScrollReveal';
useScrollReveal();

</script>

<template>
  <div class="bg-blue-100">
    <div class="mx-auto max-w-7xl px-6 py-24 sm:py-32 lg:flex lg:items-center lg:justify-between lg:px-8">
      <h2 class="max-w-2xl text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">Ready to dive in? <br>Start your free trial today.</h2>
      <div class="mt-10 flex items-center gap-x-6 lg:mt-0 lg:shrink-0">
        <a href="#" class="rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">Get started</a>
        <a href="#" class="text-sm/6 font-semibold text-gray-900">Learn more <span aria-hidden="true">→</span></a>
      </div>
    </div>
  </div>
</template>

<style>

</style>
