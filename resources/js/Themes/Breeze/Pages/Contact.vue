<script setup>
import PageLayout from "@/Themes/Breeze/Layouts/Main/PageLayout.vue";
import {usePage} from "@inertiajs/vue3";
import {inject} from "vue";

const page = usePage();
const route = inject('route');

const contact = page.props?.front.contact;

</script>

<template>

  <PageLayout :title="__('Contact us')">
    <div class="bg-white py-24 sm:py-32">
      <div class="mx-auto max-w-6xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl space-y-16 divide-y divide-gray-100 lg:mx-0 lg:max-w-none">
          <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-3">
            <div>
              <h2 class="text-3xl font-bold tracking-tight text-gray-900">{{ __('Get in touch') }}</h2>
              <p class="mt-4 leading-7 text-gray-600">
                {{
                  __('We would love to hear from you! Whether you have a question, a suggestion, or just want to say hello, feel free to reach out. Our team is here to assist you.')
                }}
              </p>
            </div>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:col-span-2 lg:gap-8">
              <div class="rounded-2xl bg-gray-50 p-10">
                <h3 class="text-base font-semibold leading-7 text-gray-900">{{ __('Contact us') }}</h3>
                <dl class="mt-3 space-y-1 text-sm leading-6 text-gray-600">
                  <div v-if="contact?.contact_email_address">
                    <dt class="sr-only">{{ __('Email') }}</dt>
                    <dd><a :href="'mailto:'+ contact?.contact_email_address"
                           class="font-semibold text-blue-600">
                      {{ contact?.contact_email_address }}
                    </a>
                    </dd>
                  </div>
                  <div v-if="contact?.company_phone" class="mt-1">
                    <dt class="sr-only">{{ __('Phone Number') }}</dt>
                    <dd>{{ contact?.company_phone }}</dd>
                  </div>
                </dl>
              </div>
              <div v-if="contact?.company_address" class="rounded-2xl bg-gray-50 p-10">
                <h3 class="text-base font-semibold leading-7 text-gray-900">{{ __('Address') }}</h3>
                <dl class="mt-3 space-y-1 text-sm leading-6 text-gray-600">
                  <div class="mt-1">
                    <dt class="sr-only">{{ __('Company Name') }}</dt>
                    <dd>{{ contact?.company_name }}</dd>
                  </div>
                  <div class="mt-1">
                    <dt class="sr-only">{{ __('Company Address') }}</dt>
                    <dd>{{ contact?.company_address }}</dd>
                  </div>
                </dl>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </PageLayout>

</template>

<style>

</style>
