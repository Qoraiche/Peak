<script setup>

import Header from "@/Themes/Breeze/Includes/Header.vue";
import Footer from "@/Themes/Breeze/Includes/Footer.vue";
import AnnouncementBanner from "@/Themes/Breeze/Includes/AnnouncementBanner.vue";
import {Bars3Icon, XMarkIcon} from "@heroicons/vue/24/outline/index.js";
import {Dialog, DialogPanel} from "@headlessui/vue";
import {inject, ref} from "vue";
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import PrimaryButton from "@/Themes/Breeze/Components/PrimaryButton.vue";

const route = inject('route');
const page = usePage();
const mobileMenuOpen = ref(false);
const logoImagePath = page.props.front?.logo_path;
const headerMenu = page.props.front.headerMenu;
const footerMenu = page.props.front.footerMenu;
const front = page.props.front;
const footerSocialMediaLinks = page.props?.front.footer_social_links;
const siteTitle = page?.props.front.siteTitle;
const siteTitleSeparator = page?.props.front.siteTitleSeparator;

const props = defineProps({
  title: String | null
});

</script>

<template>
  <Head :title="title"/>

  <!-- header & hero -->
  <div class="bg-yellow-300">

    <AnnouncementBanner/>

    <header class="mx-auto max-w-6xl">
      <nav aria-label="Global" class="flex items-center justify-between py-2 px-4 lg:px-0">
        <div class="flex lg:flex-1">
          <Link :href="route('home')" class="-m-1.5 p-1.5">
            <span class="sr-only">
              {{ __('Logo') }}
            </span>
            <svg id="site-page-logo" class="fill-current text-white" height="32" width="32"
                 xmlns="http://www.w3.org/2000/svg">
              <path class="fill-gray-100" d="M13.853 18.14 1 10.643 31 1l-.019.058z"></path>
              <path class="fill-gray-200" d="M13.853 18.14 30.981 1.058 21.357 31l-7.5-12.857z"></path>
            </svg>
          </Link>
        </div>
        <div class="flex lg:hidden">
          <button class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
                  type="button"
                  @click="mobileMenuOpen = true">
            <span class="sr-only">
              {{ __('Open main menu') }}
            </span>
            <Bars3Icon aria-hidden="true" class="h-6 w-6"/>
          </button>
        </div>

        <div class="hidden lg:flex items-center lg:gap-x-10">
          <div>
            <a href="#"
               class="text-sm/6 font-semibold text-gray-900">
              Menu 1
            </a>
          </div>
        </div>

        <div class="hidden lg:flex items-center gap-x-8 lg:flex-1 lg:justify-end">

          <Link v-if="!$page.props.auth?.user" :href="route('login')"
                class="text-sm leading-6 text-gray-900 rounded-md font-semibold">
            {{ __('Login') }}
          </Link>

          <PrimaryButton v-if="!$page.props.auth?.user" @click="router.visit(route('register'))"
                         class="justify-center !rounded-full py-2.5 px-6 !shadow-md hover:shadow-lg hover:scale-105 transition-all duration-200">
            {{ __('Register') }}
          </PrimaryButton>

          <PrimaryButton v-if="$page.props.auth?.user" @click="router.visit(route('dashboard.index'))"
                         class="justify-center !rounded-full py-2.5 px-6 !shadow-md hover:shadow-lg hover:scale-105 transition-all duration-200">
            {{ __('Dashboard') }}
          </PrimaryButton>

        </div>
      </nav>
      <Dialog :open="mobileMenuOpen" as="div" class="lg:hidden" @close="mobileMenuOpen = false">
        <div class="fixed inset-0 z-50"/>
        <DialogPanel
            class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
          <div class="flex items-center justify-between">
            <a class="-m-1.5 p-1.5" href="#">
              <span class="sr-only">
                {{ __('Your Company') }}
              </span>
              <img :src="logoImagePath" alt="Logo" class="h-12 w-auto"/>
            </a>
            <button class="-m-2.5 rounded-md p-2.5 text-gray-700" type="button"
                    @click="mobileMenuOpen = false">
              <span class="sr-only">
                {{ __('Close menu') }}
              </span>
              <XMarkIcon aria-hidden="true" class="h-6 w-6"/>
            </button>
          </div>
          <div class="mt-6 flow-root">
            <div class="-my-6 divide-y divide-gray-500/10">
              <div class="space-y-2 py-6">

                <div>
                  <a href="#"
                     class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                    Menu 1
                  </a>
                </div>
              </div>
              <div class="py-6">
<!--                <Link v-if="!$page.props.auth?.user" :href="route('login')"
                      class="text-sm leading-6 text-gray-900 rounded-md font-semibold">
                  {{ __('Login') }}
                </Link>-->

                <PrimaryButton @click="router.visit(route('register'))"
                               class="justify-center !rounded-full py-2.5 px-6 !shadow-md hover:shadow-lg hover:scale-105 transition-all duration-200">
                  {{ __('Register') }}
                </PrimaryButton>

                <PrimaryButton v-if="$page.props.auth?.user" @click="router.visit(route('dashboard.index'))"
                               class="justify-center !rounded-full py-2.5 px-6 !shadow-md hover:shadow-lg hover:scale-105 transition-all duration-200">
                  {{ __('Dashboard') }}
                </PrimaryButton>
              </div>
            </div>
          </div>
        </DialogPanel>
      </Dialog>
    </header>
  </div>

  <div class="pb-12 lg:pb-24">
    <slot/>
  </div>

  <Footer/>
  <CookieConsent/>
</template>

<style scoped>

</style>