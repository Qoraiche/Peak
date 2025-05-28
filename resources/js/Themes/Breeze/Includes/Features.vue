<script setup>

import {Link, usePage} from '@inertiajs/vue3';

const page = usePage();
const front = page.props.front;
const features = front.features;

import {useScrollReveal} from '@peak/Composables/useScrollReveal';

useScrollReveal();

</script>

<template>
  <div id="features" class="relative mx-auto overflow-hidden bg-white py-24 sm:py-12">
    <div class="bg-white px-6 py-24 sm:py-12 lg:px-8">
      <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">
          Features
        </h2>
        <p class="mt-8 text-slate-700 text-pretty text-base/7 md:text-lg">
          Discover the key features that make our platform powerful, user-friendly, and designed to meet your needs.
        </p>
      </div>
    </div>

    <!-- Feature 1 -->
    <div class="sr-fade-up relative">
      <div class="lg:mx-auto lg:grid lg:max-w-6xl lg:grid-flow-col-dense lg:grid-cols-2 lg:gap-24 lg:px-8">
        <div class="mx-auto max-w-xl px-6 lg:mx-0 lg:max-w-none lg:px-0 lg:py-16">
          <h2 class="text-3xl font-bold tracking-tight text-gray-900">
            Easy User Onboarding
          </h2>
          <p class="mt-4 prose-h3:mb-4 max-w-none prose-pre:rounded-lg prose-pre:px-4 text-base prose-a:font-semibold prose-a:text-blue-500 prose-a:no-underline prose-h3:text-base/6 prose prose-sm prose-slate prose-code:text-[13px]/[1.692] prose-pre:py-[0.875rem] hover:prose-a:text-blue-600">
            Streamline the registration process and guide new users with interactive onboarding steps to get started
            quickly.
          </p>
          <div class="mt-6">
            <a href="/register"
               class="inline-flex rounded-full px-6 text-base font-semibold leading-7 text-white py-1.5 hover:opacity-80"
               style="color: white; background-color: #3B82F6;">
              Get Started
            </a>
          </div>
        </div>
        <div class="mt-20 sm:mt-20 lg:mt-0">
          <div class="lg:relative lg:m-0 lg:h-full lg:px-0">
            <img src="https://placehold.co/600x400?text=Onboarding" alt="Feature image"
                 class="w-full rounded-xl object-cover lg:absolute lg:left-0 lg:h-full">
          </div>
        </div>
      </div>
    </div>

    <!-- Feature 2 -->
    <div class="sr-fade-up mt-20">
      <div class="lg:mx-auto lg:grid lg:max-w-6xl lg:grid-flow-col-dense lg:grid-cols-2 lg:gap-24 lg:px-8">
        <div class="mx-auto max-w-xl px-6 lg:col-start-2 lg:mx-0 lg:max-w-none lg:px-0 lg:py-16">
          <h2 class="text-3xl font-bold tracking-tight text-gray-900">
            Real-time Analytics
          </h2>
          <p class="mt-4 prose-h3:mb-4 max-w-none prose-pre:rounded-lg prose-pre:px-4 prose-a:font-semibold prose-a:text-blue-500 prose-a:no-underline prose-h3:text-base/6 prose prose-sm prose-slate prose-code:text-[13px]/[1.692] prose-pre:py-[0.875rem] hover:prose-a:text-blue-600">
            Monitor your business metrics in real time with intuitive dashboards and actionable insights.
          </p>
          <div class="mt-6">
            <a href="/register"
               class="inline-flex rounded-full px-6 text-base font-semibold leading-7 text-white py-1.5 hover:opacity-80"
               style="color: white; background-color: #3B82F6;">
              Sign Up Free
            </a>
          </div>
        </div>
        <div class="mt-20 sm:mt-20 lg:col-start-1 lg:mt-0">
          <div class="lg:relative lg:m-0 lg:h-full lg:px-0">
            <img src="https://placehold.co/600x400?text=Analytics" alt="Feature image"
                 class="w-full rounded-xl object-cover lg:absolute lg:left-0 lg:h-full">
          </div>
        </div>
      </div>
    </div>

    <!-- Feature 3 -->
    <div class="sr-fade-up mt-20">
      <div class="lg:mx-auto lg:grid lg:max-w-6xl lg:grid-flow-col-dense lg:grid-cols-2 lg:gap-24 lg:px-8">
        <div class="mx-auto max-w-xl px-6 lg:mx-0 lg:max-w-none lg:px-0 lg:py-16">
          <h2 class="text-3xl font-bold tracking-tight text-gray-900">
            Seamless Integrations
          </h2>
          <p class="mt-4 prose-h3:mb-4 max-w-none prose-pre:rounded-lg prose-pre:px-4 text-base prose-a:font-semibold prose-a:text-blue-500 prose-a:no-underline prose-h3:text-base/6 prose prose-sm prose-slate prose-code:text-[13px]/[1.692] prose-pre:py-[0.875rem] hover:prose-a:text-blue-600">
            Connect with your favorite tools like Slack, Zapier, and Stripe to automate and extend functionality.
          </p>
          <div class="mt-6">
            <a href="/register"
               class="inline-flex rounded-full px-6 text-base font-semibold leading-7 text-white py-1.5 hover:opacity-80"
               style="color: white; background-color: #3B82F6;">
              Try it Now
            </a>
          </div>
        </div>
        <div class="mt-20 sm:mt-20 lg:mt-0">
          <div class="lg:relative lg:m-0 lg:h-full lg:px-0">
            <img src="https://placehold.co/600x400?text=Integrations" alt="Feature image"
                 class="w-full rounded-xl object-cover lg:absolute lg:left-0 lg:h-full">
          </div>
        </div>
      </div>
    </div>

    <!-- Feature 4 -->
    <div class="sr-fade-up mt-20">
      <div class="lg:mx-auto lg:grid lg:max-w-6xl lg:grid-flow-col-dense lg:grid-cols-2 lg:gap-24 lg:px-8">
        <div class="mx-auto max-w-xl px-6 lg:col-start-2 lg:mx-0 lg:max-w-none lg:px-0 lg:py-16">
          <h2 class="text-3xl font-bold tracking-tight text-gray-900">
            24/7 Customer Support
          </h2>
          <p class="mt-4 prose-h3:mb-4 max-w-none prose-pre:rounded-lg prose-pre:px-4 prose-a:font-semibold prose-a:text-blue-500 prose-a:no-underline prose-h3:text-base/6 prose prose-sm prose-slate prose-code:text-[13px]/[1.692] prose-pre:py-[0.875rem] hover:prose-a:text-blue-600">
            Our dedicated team is here to help you anytime via chat, email, or phone support.
          </p>
          <div class="mt-6">
            <a href="/register"
               class="inline-flex rounded-full px-6 text-base font-semibold leading-7 text-white py-1.5 hover:opacity-80"
               style="color: white; background-color: #3B82F6;">
              Join Us
            </a>
          </div>
        </div>
        <div class="mt-20 sm:mt-20 lg:col-start-1 lg:mt-0">
          <div class="lg:relative lg:m-0 lg:h-full lg:px-0">
            <img src="https://placehold.co/600x400?text=Support" alt="Feature image"
                 class="w-full rounded-xl object-cover lg:absolute lg:left-0 lg:h-full">
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<style scoped>

</style>
