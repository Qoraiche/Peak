<script setup>

import {Dialog, DialogPanel} from "@headlessui/vue";
import {Bars3Icon, XMarkIcon} from "@heroicons/vue/24/outline/index.js";
import {Link, router, usePage} from "@inertiajs/vue3";
import {inject, ref} from "vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";

const page = usePage();
const route = inject('route');

const mobileMenuOpen = ref(false);

defineProps({});

</script>

<template>
  <header class="max-w-6xl mx-auto">
    <nav aria-label="Global" class="flex items-center justify-between p-6 lg:px-8">

      <div class="flex lg:flex-1">
        <Link :href="route('home')" class="-m-1.5 p-1.5">
          <span class="sr-only">Logo</span>

          <img v-if="$page.props.front.logo_path" class="h-12 w-auto max-w-[160px]" id="logo"
               :src="$page.props.front.logo_path" alt="Logo"/>
        </Link>
      </div>

      <div class="flex lg:hidden">
        <button class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
                type="button"
                @click="mobileMenuOpen = true">
          <span class="sr-only">Open main menu</span>
          <Bars3Icon aria-hidden="true" class="w-6 h-6"/>
        </button>
      </div>
      <div class="hidden lg:flex items-center lg:gap-x-10">
        <div>
          <a href="#menu-1"
             class="text-sm/6 font-semibold text-gray-900">
            Menu 1
          </a>
        </div>
      </div>

      <div class="items-center hidden lg:flex gap-x-8 lg:flex-1 lg:justify-end">
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


        <!--        <Link v-if="$page.props.auth?.user" :href="route('dashboard.index')"
                      class="rounded-full px-6 py-2.5 text-sm font-semibold text-white shadow-md hover:shadow-lg hover:scale-105 transition-all duration-200">
                  {{ __('Dashboard') }}
                </Link>-->
      </div>
    </nav>

    <Dialog :open="mobileMenuOpen" as="div" class="lg:hidden" @close="mobileMenuOpen = false">
      <div class="fixed inset-0 z-50"/>
      <DialogPanel
          class="fixed inset-y-0 right-0 z-50 w-full px-6 py-6 overflow-y-auto bg-white sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
        <div class="flex items-center justify-between">
          <a class="-m-1.5 p-1.5" href="#">
            <span class="sr-only">Your Company</span>

            <svg class="h-12 self-end text-white fill-current"
                 xmlns="http://www.w3.org/2000/svg">
              <path d="M13.853 18.14 1 10.643 31 1l-.019.058z"></path>
              <path class="fill-gray-200" d="M13.853 18.14 30.981 1.058 21.357 31l-7.5-12.857z"></path>
            </svg>
          </a>
          <button class="-m-2.5 rounded-md p-2.5 text-gray-700" type="button"
                  @click="mobileMenuOpen = false">
            <span class="sr-only">Close menu</span>
            <XMarkIcon aria-hidden="true" class="w-6 h-6"/>
          </button>
        </div>
        <div class="flow-root mt-6">
          <div class="-my-6 divide-y divide-gray-500/10">
            <div class="py-6 space-y-2">
              <div>
                <a href="#menu-1"
                   class="block px-3 py-2 -mx-3 text-base font-medium leading-7 text-gray-900 rounded-lg hover:bg-gray-50">
                  Menu 1
                </a>
              </div>
            </div>
            <div class="py-6">
              <Link v-if="!$page.props.auth?.user" :href="route('register')"
                    class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                {{ __('Register') }}
              </Link>
              <Link v-if="!$page.props.auth?.user" :href="route('login')"
                    class="-mx-3 block rounded-lg px-3 py-2.5 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50">
                {{ __('Login') }}
              </Link>
            </div>
          </div>
        </div>
      </DialogPanel>
    </Dialog>
  </header>
</template>

<style scoped>

</style>
