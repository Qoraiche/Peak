<script setup>

import {useForm} from "@inertiajs/vue3";
import {onMounted, ref} from "vue";
import {__} from "@peak/Composables/useTranslate.js";
import {useToast} from "vue-toastification";
import SuccessAlert from "@/Themes/Breeze/Components/Alerts/SuccessAlert.vue";
import {useToastification} from "@peak/Composables/useToast.js";

const props = defineProps({
  listName: String | null,
  position: {
    type: String,
    default: 'center'
  }
});

let toast = null;

onMounted(async () => {
  toast = await useToastification()
});

const form = useForm({
  email: '',
  list_name: props.listName,
});

const subscribed = ref(false);

const submit = () => {
  form.post(route('newsletter.subscribe'), {
    preserveScroll: true,
    onSuccess: () => {
      subscribed.value = true;
    },
    onError: () => {
      toast.error(__('An error occurred while subscribing to our newsletter. Please try again.'));
    },
  })
};

</script>

<template>
  <div>
    <form v-if="subscribed === false"
          :class="{'justify-start': position === 'start', 'justify-center': position === 'center'}" class="flex mt-6"
          @submit.prevent="submit">
      <h2 class="sr-only">{{ __('Subscribe via email') }}</h2>

      <div class="relative w-64 shrink">
        <label class="sr-only" for="subscribe-email">{{ __('Email address') }}</label>
        <input id="subscribe-email" v-model="form.email"
               class="block w-full h-10 pl-12 pr-3 bg-white rounded-md shadow-md text-slate-900 ring-1 shadow-black/5 ring-slate-900/5 placeholder:text-slate-400 focus:ring-2 focus:ring-blue-500 focus:outline-hidden sm:text-sm/6"
               name="email_address" :placeholder="__('Subscribe via email')" type="email">
        <svg class="absolute pointer-events-none top-2 left-3 size-6 stroke-slate-400" fill="none"
             stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
             xmlns="http://www.w3.org/2000/svg">
          <path
              d="M5 7.92C5 6.86 5.865 6 6.931 6h10.138C18.135 6 19 6.86 19 7.92v8.16c0 1.06-.865 1.92-1.931 1.92H6.931A1.926 1.926 0 0 1 5 16.08V7.92Z"></path>
          <path d="m6 7 6 5 6-5"></path>
        </svg>
      </div>
      <button :class="{'opacity-75': form.processing || !form.isDirty}" :disabled="form.processing || !form.isDirty"
              class="inline-flex justify-center rounded-lg text-sm font-semibold py-2.5 px-4 bg-slate-900 text-white hover:bg-slate-700 ltr:ml-4 rtl:mr-4 flex-none"
              type="submit">
        <span>
          {{ __('Join Newsletter') }}
        </span>
      </button>
    </form>

    <SuccessAlert v-if="subscribed === true" class="mt-6">
      {{ __('You are Subscribed to our newsletter! Thank you') }}
    </SuccessAlert>
  </div>
</template>

<style scoped>

</style>
