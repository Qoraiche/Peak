<script setup>
import {usePage} from "@inertiajs/vue3";

const page = usePage();
const logoImagePath = page.props.front.logoImagePath;

</script>

<template>
  <img :src="logoImagePath" alt="Logo"/>
</template>
