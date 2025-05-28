<script setup>

</script>

<template>
    <div class="rounded-md bg-green-50 p-4">
        <div class="flex">
            <div class="shrink-0">
                <svg aria-hidden="true" class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path clip-rule="evenodd"
                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                          fill-rule="evenodd"/>
                </svg>
            </div>
            <div class="ltr:ml-3 rtl:mr-3">
                <p class="text-sm font-medium text-green-800">
                    <slot/>
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
