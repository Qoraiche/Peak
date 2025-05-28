<template>
    <div>
        <div class="bg-zinc-50 border sm:rounded-lg divide-y divide-gray-100">
            <div class="flex items-center px-6 py-4" v-for="invoice in invoicesData()" :key="invoice.id">
                <div class="text-xs sm:text-sm w-full">
                    {{ invoice.date }}
                </div>

                <div class="text-xs sm:text-sm w-full">
                    <div class="px-2">
                        {{ invoice.amount }}
                    </div>
                </div>

                <div class="text-sm w-full">
                    <span v-if="invoice.status === 'open'"
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        {{ __('Unpaid') }}
                    </span>
                    <span v-else-if="invoice.status === 'pending'"
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                          :title="__('This payment was initiated, but the funds have not been received yet. This can take up to 14 days.')">
                        {{ __('Pending') }}

                        <svg class="ml-1 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                        </svg>
                    </span>
                    <span v-else
                          class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ __('Paid') }}
                    </span>
                </div>

                <div class="text-sm text-gray-700 shrink-0 flex items-center justify-end">
                    <div class="sm:w-52 text-right">
                        <span v-if="invoice.status === 'open'">
                            <button
                                @click="$emit('payment-retried', invoice)"
                                class="underline hover:text-gray-500"
                                type="button"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:mr-1 inline" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>

                                <span class="hidden sm:inline">{{ __('Retry Payment') }}</span>
                            </button>

                            <span class="mx-2">|</span>
                        </span>

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
        </div>

        <CursorPaginator v-if="hasPaginator()" class="mt-4" :preserve-scroll="true" :paginator="invoices"
                         :reload-key="reloadKey"/>
    </div>
</template>

<script>

import CursorPaginator from "@/Themes/Breeze/Components/Billing/CursorPaginator.vue";

export default {
    components: {CursorPaginator},

    props: ['invoices', 'reloadKey'],

    methods: {

        hasPaginator() {
            return 'data' in this.invoices;
        },

        invoicesData() {
            return this.invoices.data ?? this.invoices;
        },
    },
}
</script>
