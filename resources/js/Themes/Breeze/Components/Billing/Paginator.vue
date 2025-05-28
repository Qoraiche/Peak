<template>
    <div v-if="links.length > 3" v-cloak class="flex select-none flex-wrap items-center justify-center" v-bind="$attrs">
        <template v-for="(link, key) in links">
            <span v-if="isPreviousLink(link)" :key="`${key}-prev`" class="mr-3">
                <Link v-if="link.url" :preserve-scroll="preserveScroll"
                      class="mx-1 flex h-10 w-7 items-center justify-center rounded-sm text-xs text-gray-600 hover:text-teal-400 focus:text-teal-400"
                      :href="link.url">
                    <chevron-left-icon class="h-4"/>
                </Link>
                <span v-else
                      class="mx-1 flex h-10 w-7 cursor-not-allowed items-center justify-center rounded-sm text-xs text-gray-400">
                    <chevron-left-icon class="h-4 rotate-180 transform"/>
                </span>
            </span>
            <span v-else-if="isNextLink(link)" :key="`${key}-next`" class="ml-3">
                <Link v-if="link.url" :preserve-scroll="preserveScroll"
                      class="mx-1 flex h-10 w-7 items-center justify-center rounded-sm text-xs text-gray-600 hover:text-teal-400 focus:text-teal-400"
                      :href="link.url">
                    <chevron-right-icon class="h-4"/>
                </Link>
                <span v-else
                      class="mx-1 flex h-10 w-7 cursor-not-allowed items-center justify-center rounded-sm text-xs text-gray-400">
                    <chevron-right-icon class="h-4"/>
                </span>
            </span>
            <div v-else-if="link.active" :key="key"
                 class="bg-gray-10 mx-1 flex h-10 w-7 cursor-not-allowed items-center justify-center rounded-sm border border-gray-200 text-xs text-gray-600 dark:border-gray-400 dark:bg-gray-700 dark:text-gray-300"
                 v-html="link.label"/>
            <Link v-else-if="link.url" :key="`${key}-link`" :preserve-scroll="preserveScroll"
                  class="mx-1 flex h-10 w-7 items-center justify-center rounded-sm text-xs text-gray-600 hover:text-teal-400"
                  :href="link.url" v-html="link.label"/>
            <span v-else :key="`${key}-more`"
                  class="mx-1 flex h-10 w-7 items-center justify-center rounded-sm text-xs text-gray-400">...</span>
        </template>

        <div v-if="defaultPerPage" class="ml-2">
            <select v-model="perPage" class="forge-input">
                <option v-if="!availablePageOption">{{ perPage }}</option>
                <option v-for="option in pageOptions" :key="option">{{ option }}</option>
            </select>
        </div>
    </div>
</template>

<script>
import {Link} from '@inertiajs/vue3'
import {ChevronRightIcon, ChevronLeftIcon} from "@heroicons/vue/24/solid";

export default {
    components: {
        ChevronLeftIcon,
        ChevronRightIcon,
        Link,
    },

    inheritAttrs: false,

    props: {
        defaultPerPage: Number,
        links: Array,
        preserveScroll: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            perPage: null,
            pageOptions: [10, 20, 50, 100],
        }
    },

    computed: {
        pageUrl() {
            let url = new URL(window.location.href)
            let searchParams = url.searchParams

            searchParams.set('per_page', this.perPage)

            url.search = searchParams.toString()

            return `${url.pathname}${url.search}`
        },
        availablePageOption() {
            return this.pageOptions.indexOf(this.perPage) > -1
        },
    },

    watch: {
        perPage(value, oldValue) {
            if (value && !oldValue) {
                this.perPage = this.defaultPerPage

                return
            }

            if (value && oldValue) {
                this.$inertia.visit(this.pageUrl)
            }
        },
    },

    mounted() {
        this.perPage = this.defaultPerPage
    },

    methods: {
        isPreviousLink(link) {
            return link.label === '&laquo; Previous'
        },
        isNextLink(link) {
            return link.label === 'Next &raquo;'
        },
    },
}
</script>
