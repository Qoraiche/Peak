<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button :type="type" class="inline-flex font-bold font-header items-center justify-center px-6 py-2 bg-red-600 border border-transparent rounded-md text-xs text-white uppercase whitespace-nowrap text-center hover:bg-red-500 active:bg-red-700 focus:outline-hidden focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
        <slot />
    </button>
</template>
