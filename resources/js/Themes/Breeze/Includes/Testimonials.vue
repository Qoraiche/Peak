<script setup>

import {usePage} from "@inertiajs/vue3";
import {Star} from "lucide-vue-next";

const page = usePage();
const front = page.props.front;
const testimonials = front.testimonials;

import { useScrollReveal } from '@peak/Composables/useScrollReveal';

useScrollReveal();

</script>

<template>
  <div id="testimonials" class="px-6 py-12 sm:py-24 lg:px-8">
    <div class="max-w-2xl mx-auto text-center">
      <h2 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">
        Testimonials
      </h2>
      <p class="mt-8 text-pretty text-base/7 text-slate-700 md:text-lg">
        See what our users are saying. Real stories from people who trust and love our platform.
      </p>
    </div>
  </div>

  <div class="relative flex flex-col justify-center overflow-hidden px-0 md:px-8 pb-12 sm:pb-24">
    <div class="mx-auto max-w-7xl">
      <div class="grid grid-cols-1 gap-8 mt-12 sm:grid-cols-2 lg:grid-cols-3">

        <!-- Testimonial 1 -->
        <div class="p-6 rounded-2xl shadow bg-gray-50">
          <figure class="text-sm leading-6 cursor-default">
            <div class="flex gap-x-1">
              <Star v-for="n in 5" :key="n" class="w-4 fill-current text-yellow-400" />
            </div>
            <blockquote class="text-gray-900 break-words pt-3">
              <p>“This platform changed the way I connect with my audience. Absolutely love it!”</p>
            </blockquote>
            <figcaption class="flex items-center mt-6 gap-x-4">
              <img src="https://i.pravatar.cc/100?img=1" alt="reviewer" class="w-10 h-10 rounded-full bg-gray-50" />
              <div>
                <div class="font-semibold text-gray-900">Alex Johnson</div>
                <div class="text-gray-600">Content Creator</div>
              </div>
            </figcaption>
          </figure>
        </div>

        <!-- Testimonial 2 -->
        <div class="p-6 rounded-2xl shadow bg-yellow-50 ring-2 ring-yellow-400 scale-105">
          <figure class="text-sm leading-6 cursor-default">
            <div class="flex gap-x-1">
              <Star v-for="n in 5" :key="`center-${n}`" class="w-4 fill-current text-yellow-400" />
            </div>
            <blockquote class="text-gray-900 break-words pt-3">
              <p>“Super easy to use and incredibly effective. Highly recommended to all professionals.”</p>
            </blockquote>
            <figcaption class="flex items-center mt-6 gap-x-4">
              <img src="https://i.pravatar.cc/100?img=2" alt="reviewer" class="w-10 h-10 rounded-full bg-gray-50" />
              <div>
                <div class="font-semibold text-gray-900">Jamie Lee</div>
                <div class="text-gray-600">Marketing Manager</div>
              </div>
            </figcaption>
          </figure>
        </div>

        <!-- Testimonial 3 -->
        <div class="p-6 rounded-2xl shadow bg-gray-50">
          <figure class="text-sm leading-6 cursor-default">
            <div class="flex gap-x-1">
              <Star v-for="n in 5" :key="`right-${n}`" class="w-4 fill-current text-yellow-400" />
            </div>
            <blockquote class="text-gray-900 break-words pt-3">
              <p>“The customer service is fantastic, and the platform runs smoothly. A+.”</p>
            </blockquote>
            <figcaption class="flex items-center mt-6 gap-x-4">
              <img src="https://i.pravatar.cc/100?img=3" alt="reviewer" class="w-10 h-10 rounded-full bg-gray-50" />
              <div>
                <div class="font-semibold text-gray-900">Samantha Brown</div>
                <div class="text-gray-600">Business Owner</div>
              </div>
            </figcaption>
          </figure>
        </div>

      </div>
    </div>
  </div>

</template>

<style>

</style>
