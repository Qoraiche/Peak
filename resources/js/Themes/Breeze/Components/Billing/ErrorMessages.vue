<template>
    <div>
        <div v-if="errors.length > 0"
             class="px-6 py-4 bg-red-100 bg-opacity-25 text-red-900 border border-red-200 sm:rounded-lg shadow-xs">

            {{ __('Whoops! Something went wrong.') }}

            <div v-if="typeof errors === 'string'" class="mt-4 ml-5 text-sm">
                {{ errors }}
            </div>

            <ul v-else class="mt-4 ltr:ml-3 rtl:mr-3 text-sm list-disc">
                <li v-for="(error, index) in errors" :key="index">
                    {{ typeof error === 'object' ? error[0] : error }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: ['errors']
}
</script>
