<script setup>
import {Link, usePage} from '@inertiajs/vue3';

const page = usePage();
const logoImagePath = page.props.front.logoImagePath;

</script>

<template>
  <Link :href="'/'">
    <img :src="logoImagePath" alt="Logo" class="w-36"/>
  </Link>
</template>
