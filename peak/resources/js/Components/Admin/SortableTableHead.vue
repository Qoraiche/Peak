<script setup>

import {router} from "@inertiajs/vue3";
import {onMounted, ref} from "vue";

import {ChevronUpIcon} from "@heroicons/vue/24/outline";
import {ChevronDownIcon} from "@heroicons/vue/24/outline";

const props = defineProps({
    title: {
        default: '',
        type: String,
    },
    sortable: {
        type: Boolean,
        default: false
    },
    sortKey: String | null
});

const hasFilterName = ref(false);
const filterName = ref(null);
const sortType = ref('asc');

const sortBy = by => {

    if (!props.sortable) {
        return;
    }

    let sortBy = sortType.value === 'asc' ? '-' + by : by;

    const params = {
        sort: sortBy
    };

    if (hasFilterName.value) {
        params.filter = {
            name: filterName.value
        };
    }

    let options = Object.assign(route().params, params);

    router.visit(route(route().current(), options), {
        method: 'get',
        preserveState: false,
        preserveScroll: true,
    });

}

onMounted(() => {
    // Get the query string from the URL
    const queryString = window.location.search;

    // Parse the query string into an object
    const queryParams = new URLSearchParams(queryString);

    // Check if the 'filter[name]' parameter exists
    if (queryParams.has('filter[name]')) {
        hasFilterName.value = true;
        filterName.value = queryParams.get('filter[name]');
    }

    /** get sort value **/
    const getSortValue = () => {
        const queryString = window.location.search;

        // Parse the query string into an object
        const queryParams = new URLSearchParams(queryString);

        // Check if the 'filter[name]' parameter exists
        if (queryParams.has('sort')) {
            return queryParams.get('sort');
        }

        return null;
    }

    /** get sort type **/
    const getSortType = () => {
        if (getSortValue()) {
            return getSortValue().startsWith('-') ? 'desc' : 'asc';
        }

        return sortType.value;
    }

    if (getSortValue()) {
        const cleanSortValue = getSortValue().replace(/^-/, '');

        if (cleanSortValue === props.sortKey) {
            sortType.value = getSortType();
        }
    }
});

</script>

<template>
    <th scope="col"
        class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 fi-table-header-cell-name"
        @click="sortBy(sortKey)">
        <div :class="{'cursor-pointer' : sortable}" class="flex group items-center uppercase">
            {{ title }}
            <div v-if="sortable">
                <ChevronUpIcon class="h-3 w-3 group-hover:text-gray-800 text-gray-500 ltr:ml-1.5 rtl:mr-1.5"
                               v-if="sortType === 'asc'"/>
                <ChevronDownIcon class="h-3 w-3 group-hover:text-gray-800 text-gray-500 ml-1.5 rtl:mr-1.5" v-else/>
            </div>
        </div>
    </th>
</template>

<style scoped>

</style>
