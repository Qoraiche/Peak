<script setup>
import {Link} from '@inertiajs/vue3';

defineProps({
    href: String,
    as: String,
    target: String | null
});

</script>

<template>
    <div>
        <button v-if="as == 'button'" type="submit"
                class="flex items-center gap-x-2 w-full px-4 py-2 ltr:text-left rtl:text-right text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
            <slot/>
        </button>

        <a :target="target" v-else-if="as =='a'" :href="href"
           class="flex items-center w-full gap-x-2 px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
            <slot/>
        </a>

        <Link v-else :href="href"
              class="flex items-center w-full gap-x-2 px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
            <slot/>
        </Link>
    </div>
</template>
