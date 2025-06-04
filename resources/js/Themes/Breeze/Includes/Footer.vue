<script setup>

import {Menu, MenuButton, MenuItems} from "@headlessui/vue";
import {Link, router, usePage} from "@inertiajs/vue3";
import {useFeature} from "@peak/Composables/useFeature.js";
import {inject} from "vue";

const route = inject('route');


const {featureEnabled, featureDisabled} = useFeature();

const page = usePage();

defineProps({
  theme: {
    type: String,
    default: 'dark',
  }
})
</script>

<template>
  <footer :class="[theme === 'dark' ? 'bg-gray-900' : 'bg-white']">
    <div class="flex flex-col px-6 py-16 mx-auto gap-y-10 overflow-hidden max-w-7xl sm:py-20 lg:px-8">
      <nav aria-label="Footer"
           class="-mb-6 pb-6 columns-1 sm:flex sm:justify-center sm:gap-x-12">

        <div class="pb-6">
          <a href="#menu"
             class="text-sm font-medium leading-6 text-gray-300 hover:text-gray-400">
            Terms of service
          </a>
        </div>

        <div class="pb-6">
          <Link :href="route('contact.index')"
             class="text-sm font-medium leading-6 text-gray-300 hover:text-gray-400">
            Contact
          </Link>
        </div>

        <div class="pb-6">
          <a href="#"
                class="text-sm font-medium leading-6 text-gray-300 hover:text-gray-400">
            About Us
          </a>
        </div>

        <div class="pb-6">
          <a href="#"
                class="text-sm font-medium leading-6 text-gray-300 hover:text-gray-400">
            Privacy Policy
          </a>
        </div>
      </nav>

      <div class="flex justify-center gap-x-10">
        <a href="#"
           class="text-gray-400 hover:text-gray-500"
           target="_blank">
          <span class="sr-only">Facebook</span>
          <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path clip-rule="evenodd"
                  d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                  fill-rule="evenodd"/>
          </svg>
        </a>
        <a href="#"
           class="text-gray-400 hover:text-gray-500">
          <span class="sr-only">Instagram</span>
          <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path clip-rule="evenodd"
                  d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                  fill-rule="evenodd"/>
          </svg>
        </a>
        <a href="#"
           class="text-gray-400 hover:text-gray-500">
          <span class="sr-only">X</span>
          <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M13.6823 10.6218L20.2391 3H18.6854L12.9921 9.61788L8.44486 3H3.2002L10.0765 13.0074L3.2002 21H4.75404L10.7663 14.0113L15.5685 21H20.8131L13.6819 10.6218H13.6823ZM11.5541 13.0956L10.8574 12.0991L5.31391 4.16971H7.70053L12.1742 10.5689L12.8709 11.5655L18.6861 19.8835H16.2995L11.5541 13.096V13.0956Z"/>
          </svg>
        </a>
        <a href="#"
           class="text-gray-400 hover:text-gray-500">
          <span class="sr-only">TikTok</span>
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M412.19,118.66a109.27,109.27,0,0,1-9.45-5.5,132.87,132.87,0,0,1-24.27-20.62c-18.1-20.71-24.86-41.72-27.35-56.43h.1C349.14,23.9,350,16,350.13,16H267.69V334.78c0,4.28,0,8.51-.18,12.69,0,.52-.05,1-.08,1.56,0,.23,0,.47-.05.71,0,.06,0,.12,0,.18a70,70,0,0,1-35.22,55.56,68.8,68.8,0,0,1-34.11,9c-38.41,0-69.54-31.32-69.54-70s31.13-70,69.54-70a68.9,68.9,0,0,1,21.41,3.39l.1-83.94a153.14,153.14,0,0,0-118,34.52,161.79,161.79,0,0,0-35.3,43.53c-3.48,6-16.61,30.11-18.2,69.24-1,22.21,5.67,45.22,8.85,54.73v.2c2,5.6,9.75,24.71,22.38,40.82A167.53,167.53,0,0,0,115,470.66v-.2l.2.2C155.11,497.78,199.36,496,199.36,496c7.66-.31,33.32,0,62.46-13.81,32.32-15.31,50.72-38.12,50.72-38.12a158.46,158.46,0,0,0,27.64-45.93c7.46-19.61,9.95-43.13,9.95-52.53V176.49c1,.6,14.32,9.41,14.32,9.41s19.19,12.3,49.13,20.31c21.48,5.7,50.42,6.9,50.42,6.9V131.27C453.86,132.37,433.27,129.17,412.19,118.66Z"/>
          </svg>
        </a>

        <a href="#"
           class="text-gray-400 hover:text-gray-500">
          <span class="sr-only">YouTube</span>
          <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path clip-rule="evenodd"
                  d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z"
                  fill-rule="evenodd"/>
          </svg>
        </a>
      </div>

      <p class="text-xs leading-5 text-center text-zinc-300">
        &copy; 2025 {{ $page.props.app.name }}. All rights reserved.
      </p>
    </div>
  </footer>
</template>

<style scoped>

</style>
