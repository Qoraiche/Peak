<script setup>
import {Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue';
import {MinusSmallIcon, PlusSmallIcon} from '@heroicons/vue/24/outline';

import {usePage} from "@inertiajs/vue3";

const page = usePage();

const front = page.props.front;
const faqs = front.faqs;

import { useScrollReveal } from '@peak/Composables/useScrollReveal';
useScrollReveal();
</script>

<template>
  <div id="faqs" class="bg-white">
    <div class="px-6 py-24 mx-auto max-w-7xl lg:px-8">
      <div class="max-w-4xl mx-auto divide-y divide-gray-900/10">
        <div>
          <h2 class="text-2xl font-bold leading-10 tracking-tight text-gray-900 sm:text-4xl">
            Frequently Asked Questions
          </h2>

          <p class="mt-6 pb-4 text-pretty text-base/7 text-slate-700 md:text-lg">
            Have questions? We’ve got answers. Browse our most frequently asked questions.
          </p>
        </div>

        <dl class="mt-5 space-y-6 divide-y divide-gray-900/10">

          <!-- FAQ 1 -->
          <Disclosure v-slot="{ open }" as="div" class="sr-fade-up pt-3">
            <dt>
              <DisclosureButton class="flex items-start justify-between w-full text-left text-gray-900">
              <span class="text-base font-semibold pb-2 leading-7">
                How do I start using the platform?
              </span>
                <span class="flex items-center ml-6 h-7">
                <PlusSmallIcon v-if="!open" class="w-6 h-6" aria-hidden="true" />
                <MinusSmallIcon v-else class="w-6 h-6" aria-hidden="true" />
              </span>
              </DisclosureButton>
            </dt>
            <DisclosurePanel as="dd" class="my-2 ltr:pr-12 rtl:pl-12">
              <p class="leading-7 text-gray-600 text-sm/5">
                Simply sign up, verify your email, and you’re ready to go. Start by creating your first project or post.
              </p>
            </DisclosurePanel>
          </Disclosure>

          <!-- FAQ 2 -->
          <Disclosure v-slot="{ open }" as="div" class="sr-fade-up pt-3">
            <dt>
              <DisclosureButton class="flex items-start justify-between w-full text-left text-gray-900">
              <span class="text-base font-semibold pb-2 leading-7">
                Is there a free trial available?
              </span>
                <span class="flex items-center ml-6 h-7">
                <PlusSmallIcon v-if="!open" class="w-6 h-6" aria-hidden="true" />
                <MinusSmallIcon v-else class="w-6 h-6" aria-hidden="true" />
              </span>
              </DisclosureButton>
            </dt>
            <DisclosurePanel as="dd" class="my-2 ltr:pr-12 rtl:pl-12">
              <p class="leading-7 text-gray-600 text-sm/5">
                Yes! You can try all premium features free for 14 days—no credit card required.
              </p>
            </DisclosurePanel>
          </Disclosure>

          <!-- FAQ 3 -->
          <Disclosure v-slot="{ open }" as="div" class="sr-fade-up pt-3">
            <dt>
              <DisclosureButton class="flex items-start justify-between w-full text-left text-gray-900">
              <span class="text-base font-semibold pb-2 leading-7">
                Can I cancel at any time?
              </span>
                <span class="flex items-center ml-6 h-7">
                <PlusSmallIcon v-if="!open" class="w-6 h-6" aria-hidden="true" />
                <MinusSmallIcon v-else class="w-6 h-6" aria-hidden="true" />
              </span>
              </DisclosureButton>
            </dt>
            <DisclosurePanel as="dd" class="my-2 ltr:pr-12 rtl:pl-12">
              <p class="leading-7 text-gray-600 text-sm/5">
                Absolutely. You can cancel your subscription anytime from your account dashboard.
              </p>
            </DisclosurePanel>
          </Disclosure>

        </dl>
      </div>
    </div>
  </div>

</template>

<style>

</style>
