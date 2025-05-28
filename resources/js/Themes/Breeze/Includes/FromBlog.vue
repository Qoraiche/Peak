<script setup>
import {limitText} from '@peak/Composables/Utils.js';
import {Link, usePage} from "@inertiajs/vue3";
import {inject} from "vue";

const dayjs = inject('dayJS');

const page = usePage();
const front = page.props.front;

const posts = front.getFromBlogPosts;

import { useScrollReveal } from '@peak/Composables/useScrollReveal';

useScrollReveal();

</script>

<template>
  <div v-if="posts.length > 0" id="posts" class="bg-white py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="mx-auto max-w-2xl text-center">
        <h2 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">
          {{ __('From the blog') }}
        </h2>
        <p class="mt-8 text-pretty text-base/7 text-slate-700 md:text-lg">
          {{ __('Get the latest updates, insights, and tips from our blog. Stay informed and inspired.') }}
        </p>
      </div>
      <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-3">
        <article v-for="article in posts" :key="article.id" class="sr-fade-up flex flex-col items-start justify-between">
          <Link :href="article.url" class="relative w-full">
            <img v-if="article.image_url" :alt="article.title" :src="article.image_url"
                 class="aspect-video w-full rounded-2xl bg-gray-100 object-cover sm:aspect-2/1 lg:aspect-3/2">
            <div class="absolute inset-0 rounded-2xl ring-1 ring-inset ring-gray-900/10"></div>
          </Link>
          <div class="max-w-xl">
            <div class="mt-8 flex items-center gap-x-4 text-xs">
              <time :datetime="article.pulished_at ?? article.created_at" class="text-gray-500">
                {{ article.pulished_at ?? article.created_at }}
              </time>

              <div class="flex items-center gap-x-2">
                <Link v-for="topic in article.topics" :href="route('blog.topic.index', topic.slug)"
                      class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">
                  {{ topic.name }}
                </Link>
              </div>
            </div>
            <div class="group relative">
              <h3 class="mt-3 text-lg/6 font-semibold text-gray-900 group-hover:text-gray-600">
                <Link :href="route('blog.show', article.slug)">
                  <span class="absolute inset-0"></span>
                  {{ article.title }}
                </Link>
              </h3>
              <p class="mt-5 line-clamp-3 text-sm/6 text-gray-600">
                {{ limitText(article.excerpt, 200) }}
              </p>
            </div>

            <div class="relative mt-8 flex items-center gap-x-4">
              <img
                  :alt="article.user.name"
                  :src="article.user.profile_photo_url" class="size-10 rounded-full bg-gray-100">
              <div class="text-sm/6">
                <p class="font-semibold text-gray-900">
                  <Link :href="route('blog.index', {filter: {user_id: article.user.id}})">
                    <span class="absolute inset-0"></span>
                    {{ article.user.name }}
                  </Link>
                </p>
                <p v-if="article.user?.username" class="text-gray-600">
                  @{{ article.user.username }}
                </p>
              </div>
            </div>
          </div>
        </article>
      </div>
    </div>
  </div>
</template>

<style>

</style>
