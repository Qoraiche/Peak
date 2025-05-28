<template>
    <div v-if="(paginator.prev_page_url !== null || paginator.next_page_url !== null)" v-cloak
         class="flex select-none flex-wrap items-center justify-center" v-bind="$attrs">
        <span class="mr-3">
          <Link v-if="(paginator.prev_page_url !== null)" :preserve-scroll="preserveScroll" :only="[this.reloadKey]"
                class="mx-1 flex h-10 w-7 items-center justify-center rounded-sm text-xs text-gray-600 hover:text-blue-400 focus:text-blue-400"
                :href="paginator.prev_page_url">
              <ChevronLeftIcon class="h-4"/>
          </Link>
          <span v-else
                class="mx-1 flex h-10 w-7 cursor-not-allowed items-center justify-center rounded-sm text-xs text-gray-400">
              <ChevronLeftIcon class="h-4"/></span>
        </span>
        <span class="ml-3">
          <Link v-if="(paginator.next_page_url !== null)" :preserve-scroll="preserveScroll" :only="[this.reloadKey]"
                class="mx-1 flex h-10 w-7 items-center justify-center rounded-sm text-xs text-gray-600 hover:text-blue-400 focus:text-blue-400"
                :href="paginator.next_page_url">
              <ChevronRightIcon class="h-4"/>
          </Link>
          <span v-else
                class="mx-1 flex h-10 w-7 cursor-not-allowed items-center justify-center rounded-sm text-xs text-gray-400">
              <ChevronRightIcon class="h-4"/>
          </span>
        </span>
    </div>
</template>

<script>
import {Link} from '@inertiajs/vue3';
import {ChevronRightIcon, ChevronLeftIcon} from "@heroicons/vue/24/solid";

export default {
    components: {
        Link,
        ChevronLeftIcon,
        ChevronRightIcon
    },

    inheritAttrs: false,

    props: {
        paginator: Object,
        reloadKey: String,
        preserveScroll: {
            type: Boolean,
            default: false,
        },
    },
}
</script>
