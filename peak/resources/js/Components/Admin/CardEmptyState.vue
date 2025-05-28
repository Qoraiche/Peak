<script setup>

import {ExclamationCircleIcon} from "@heroicons/vue/24/solid/index.js";
</script>

<template>
    <div
        class="relative flex flex-col items-center justify-center space-y-2 py-5 bg-zinc-100/50 px-8">
                        <span class="text-sm">
                            <ExclamationCircleIcon class="h-5 w-5 text-zinc-600/70"/>
                        </span>

        <span class="text-xs text-zinc-600">{{ __('No items found') }}</span>
    </div>
</template>

<style scoped>

</style>
