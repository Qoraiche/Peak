<script setup>

import {ExclamationTriangleIcon} from "@heroicons/vue/24/solid";
</script>

<template>
    <div class="rounded-md bg-red-50 p-4">
        <div class="flex">
            <div class="shrink-0">

                <ExclamationTriangleIcon class="h-5 w-5 text-red-400"/>

            </div>
            <div class="ltr:ml-3 rtl:mr-3">
                <p class="text-sm font-medium text-red-800">
                    <slot/>
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
