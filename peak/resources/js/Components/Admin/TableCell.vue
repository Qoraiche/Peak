<template>
    <td class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
        <component
            :is="href ? Link : 'div'"
            v-bind="href ? { href, class: 'flex w-full disabled:pointer-events-none justify-start text-start' } : {}"
        >
            <div class="grid w-full gap-y-1 px-3 py-4">
                <div class="flex">
                    <div class="flex max-w-max">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </component>
    </td>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: String, // Optional Inertia link
});
</script>
