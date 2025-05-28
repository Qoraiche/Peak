<script setup>

defineProps({
    cols: 1,
})

</script>

<template>
    <div :class="'grid grid-cols-'+ cols +' gap-3'">
        <slot />
    </div>
</template>

<style scoped>

</style>
