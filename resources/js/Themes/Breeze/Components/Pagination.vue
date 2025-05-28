<script setup>

import {TailwindPagination} from "laravel-vue-pagination";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    data: Object,
    dataRouteName: String,
});

const getResults = (page) => {

    // check if there is a sort
    let options = {page: page};
    options = Object.assign(route().params, options);

    router.visit(route(props.dataRouteName, options), {
        method: 'get',
        preserveState: true,
        preserveScroll: true,
    });
}

</script>

<template>
    <TailwindPagination
        :data="data"
        @pagination-change-page="getResults"
    />
</template>

<style scoped>

</style>
