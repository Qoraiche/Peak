<script setup>
import Card from "@peak/Components/Admin/Card.vue";
import {inject} from "vue";
import {Link} from "@inertiajs/vue3";
import CardEmptyState from "@peak/Components/Admin/CardEmptyState.vue";
import {Eye, List, SquarePen} from 'lucide-vue-next';

defineProps({
  title: {
    type: String,
    default: null,
  },
  lazy: {
    type: Boolean,
    default: true,
  },
  description: {
    type: String,
    default: null,
  },
  recentBlogPosts: {
    type: [Object, Array],
    default: [],
  },
  viewMoreRouteName: {
    type: String,
    default: null,
  },
  collapsible: {
    type: Boolean,
    default: true,
  }
});

const dayjs = inject('dayJS');
</script>

<template>
  <Card :collapsible="collapsible" :deferred="lazy" :has-content-padding="false" :has-shadow="false"
        deferred-data="recentBlogPosts">
    <template #header>
      {{ __(title) }}
    </template>

    <template v-if="description" #description>
      {{ __(description) }}
    </template>

    <template #actions>
      <Link v-if="viewMoreRouteName" v-tooltip="__('View more')"
            :href="route(viewMoreRouteName)"
            class="rounded-full size-8 bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-gray-100 ring-blue-600 focus:ring-blue-600">
        <List class="size-4"/>
      </Link>
    </template>

    <ul v-if="recentBlogPosts.length > 0" class="divide-y divide-gray-100 px-5" role="list">
      <li v-for="post in recentBlogPosts" class="flex items-center justify-between gap-x-6 py-5">
        <div  class="flex min-w-0 gap-x-4 items-center">
          <img v-if="post.locale_image" :alt="post.title"
               :src="post.locale_image"
               class="size-12 flex-none rounded-md bg-gray-50 object-cover">
          <div class="min-w-0">
            <div class="flex items-start gap-x-3">
              <Link :href="route('admin.blog.articles.edit', post.id)"
                    class="hover:underline text-sm/6 line-clamp-1 font-semibold text-gray-900">
                {{ post.title }}
              </Link>
            </div>
            <div class="mt-1 flex items-center gap-x-2 text-xs/5 text-gray-500">
              <p class="whitespace-nowrap"> {{ __('Published on') }}
                <time :datetime="post.published_at ?? post.created_at">
                  {{ post.published_at ?? post.created_at }}
                </time>
              </p>

              <svg class="size-0.5 fill-current" viewBox="0 0 2 2">
                <circle cx="1" cy="1" r="1"/>
              </svg>

              <p class="truncate">
                {{ __('Created by') }}
                <Link :href="route('admin.user-management.users.edit', post.user.id)"
                      class="hover:underline">
                  {{ post.user.name }}
                </Link>
              </p>
            </div>
          </div>
        </div>

        <div class="flex flex-none items-center gap-x-4">
          <div class="flex gap-x-2 items-center relative flex-none">
            <Link :href="route('admin.blog.articles.edit', post.id)">
              <SquarePen class="h-4 w-4 text-gray-400 hover:text-gray-600"/>
            </Link>

            <a target="_blank"
               :href="route('blog.show', post.slug)">
              <Eye class="h-4 w-4 text-gray-400 hover:text-gray-600"/>
            </a>
          </div>
        </div>
      </li>
    </ul>

    <div v-else>
      <CardEmptyState/>
    </div>
  </Card>
</template>

<style scoped>

</style>
