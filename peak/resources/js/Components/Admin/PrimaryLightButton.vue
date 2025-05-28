<script setup>

import {PlusIcon} from "@heroicons/vue/24/solid";

defineProps({
    type: {
        type: String,
        default: 'submit',
    },
    loading: {
        type: Boolean,
        default: false
    },

    processing: {
        type: Boolean,
        default: false
    },
    newIcon: {
        type: Boolean,
        default: false
    },
});
</script>

<template>
    <button :type="type"
            :class="{'opacity-75': processing}"
            :disabled="processing"
            class="gap-x-2 hover:text-white transition-all duration-200 py-2.5 px-5 text-sm inline-flex items-center focus:outline-none focus:ring-4 font-medium rounded-lg border border-blue-100 bg-blue-50 text-blue-500 hover:bg-blue-500 focus:ring-blue-200">

        <PlusIcon v-if="newIcon && !(loading || processing)" class="-ml-1 ltr:mr-3 rtl:ml-3 h-5 w-5 text-white"/>

        <svg v-if="loading || processing" class="animate-spin -ml-1 ltr:mr-3 rtl:ml-3 h-5 w-5 text-white"
             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>

        <slot/>
    </button>
</template>
