<script setup>

</script>

<template>
    <div class="rounded-md bg-blue-50 p-4">
        <div class="flex">
            <div class="shrink-0">
                <svg aria-hidden="true" class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path clip-rule="evenodd"
                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z"
                          fill-rule="evenodd"/>
                </svg>
            </div>
            <div class="ltr:ml-3 rtl:mr-3 flex-1 md:flex md:justify-between">
                <p class="text-sm text-blue-700">
                    <slot/>
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
