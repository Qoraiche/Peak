<script setup>
import {InformationCircleIcon} from '@heroicons/vue/20/solid'

</script>

<template>
    <div class="rounded-md bg-blue-50 p-4">
        <div class="flex">
            <div class="shrink-0">
                <InformationCircleIcon class="h-5 w-5 text-blue-400" aria-hidden="true"/>
            </div>
            <div class="ltr:ml-3 rtl:mr-3 text-blue-600 text-sm">
                <slot/>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
