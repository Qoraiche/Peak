<script setup>
import Card from "@peak/Components/Admin/Card.vue";
import {inject} from "vue";
import {Link} from "@inertiajs/vue3";
import CardEmptyState from "@peak/Components/Admin/CardEmptyState.vue";
import {List} from 'lucide-vue-next';
import {limitText} from "@peak/Composables/Utils.js";

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
  recentRoadmaps: {
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
        deferred-data="recentRoadmaps">
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

    <ul v-if="recentRoadmaps.length > 0" class="divide-y divide-gray-100" role="list">
      <li v-for="roadmap in recentRoadmaps" :key="roadmap.id"
          class="relative flex justify-between gap-x-6 px-4 py-5 hover:bg-gray-50">
        <div class="flex min-w-0 gap-x-4">
          <img v-if="roadmap.user" :src="roadmap.user?.profile_photo_url"
               alt=""
               class="size-8 flex-none rounded-full bg-gray-50">
          <div class="min-w-0 flex-auto">
            <p class="text-sm/6 font-semibold line-clamp-1 text-gray-900">
              <Link :href="route('admin.roadmaps.edit', roadmap.id)">
                <span class="absolute inset-x-0 -top-px bottom-0"></span>
                {{ roadmap.title }}
              </Link>
            </p>
            <p class="mt-1 flex text-xs/5 line-clamp-1 text-gray-500 truncate" v-html="roadmap.body">
            </p>
          </div>
        </div>
        <div class="flex shrink-0 items-center gap-x-4">
          <div class="hidden sm:flex sm:flex-col sm:items-end">
            <p class="text-xs text-gray-900 font-semibold">
              {{ __(':total Upvotes', {total: roadmap.upvoters_count}) }}
            </p>
            <p class="mt-1 text-xs/5 text-gray-500">
              {{ __('Created by :user', {user: roadmap.user?.name ?? 'Visitor'}) }}
            </p>
          </div>

          <svg aria-hidden="true" class="size-5 flex-none text-gray-400 rtl:rotate-180"
               data-slot="icon"
               fill="currentColor" viewBox="0 0 20 20">
            <path clip-rule="evenodd"
                  d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                  fill-rule="evenodd"/>
          </svg>
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
