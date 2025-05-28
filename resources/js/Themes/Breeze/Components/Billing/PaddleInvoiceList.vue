<template>
    <div>
        <div class="bg-zinc-50 border sm:rounded-lg divide-y divide-gray-100">
            <div class="flex items-center px-6 py-4" v-for="invoice in invoices.data" :key="invoice.id">
                <div class="text-sm w-full">
                    {{ invoice.billed_at }}
                </div>

                <div class="text-sm w-full">
                    <div class="px-2">
                        {{ invoice.total }}
                    </div>
                </div>

                <div class="text-sm w-full">
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ __('Paid') }}
                    </span>
                </div>

                <div class="text-right w-full">
                    <a class="underline hover:text-gray-500" :href="invoice.invoice_url" target="_blank"
                       :title="__('Download Invoice')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <paginator class="mt-4" :preserve-scroll="true" :links="invoices.links"/>
    </div>
</template>
<script>

import Paginator from '@/Themes/Breeze/Components/Billing/Paginator.vue';

export default {
    components: {
        Paginator,
    },

    props: ['invoices'],
}
</script>
