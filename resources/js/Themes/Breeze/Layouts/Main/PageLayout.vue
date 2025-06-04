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
  <div class="bg-gradient-to-br from-yellow-50 via-amber-100 to-orange-100">
    <AnnouncementBanner/>

    <header class="mx-auto max-w-6xl">
      <nav aria-label="Global" class="flex items-center justify-between py-2 px-4 lg:px-0">
        <div class="flex lg:flex-1">
          <Link :href="route('home')" class="-m-1.5 p-1.5">
            <span class="sr-only">
              {{ __('Logo') }}
            </span>
            <img :src="logoImagePath" alt="Logo" class="h-10 w-auto"/>
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
            <a href="/#features"
               class="text-sm/6 font-semibold text-gray-900">
              Features
            </a>
          </div>

          <div>
            <a href="/#testimonials"
               class="text-sm/6 font-semibold text-gray-900">
              Testimonials
            </a>
          </div>
          <div>
            <a href="/#pricing"
               class="text-sm/6 font-semibold text-gray-900">
              Pricing
            </a>
          </div>
          <div>
            <a href="/#faqs"
               class="text-sm/6 font-semibold text-gray-900">
              FAQs
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
                  <a href="/#features"
                     class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                    Features
                  </a>
                </div>

                <div>
                  <a href="/#testimonials"
                     class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                    Testimonials
                  </a>
                </div>

                <div>
                  <a href="/#pricing"
                     class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                    Pricing
                  </a>
                </div>

                <div>
                  <a href="/#faqs"
                     class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                    FAQs
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