<script setup>
import Card from "@peak/Components/Admin/Card.vue";
import {inject} from "vue";
import {Link} from "@inertiajs/vue3";
import CardEmptyState from "@peak/Components/Admin/CardEmptyState.vue";
import {CircleCheckBig, List, MessageCircleMore} from 'lucide-vue-next';
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
  recentSupportTickets: {
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

function getUniqueComments(comments) {
  const seen = new Set();
  return comments.filter((comment) => {
    if (seen.has(comment.user.id)) {
      return false;
    }
    seen.add(comment.user.id);
    return true;
  });
}

</script>

<template>
  <Card :collapsible="collapsible" :deferred="lazy" :has-content-padding="false" :has-shadow="false"
        deferred-data="recentSupportTickets">
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

    <ul v-if="recentSupportTickets.length > 0" class="divide-y divide-gray-100 overflow-x-hidden"
        role="list">
      <li v-for="ticket in recentSupportTickets" :key="ticket.id"
          class="flex flex-wrap items-center truncate ltr:pl-6 rtl:pr-6 justify-between gap-y-4 py-5 sm:flex-nowrap">
        <div>
          <p class="text-sm/6 font-semibold line-clamp-1 text-gray-900 truncate">
            <Link :href="route('admin.support.tickets.show', ticket.uuid)"
                  class="hover:underline">
              {{ ticket.subject }}
            </Link>
          </p>
          <div class="mt-1 flex items-center gap-x-2 text-xs/5 text-gray-500">
            <p>
              <Link
                  :href="route('admin.user-management.users.edit', ticket.user.id)"
                  class="truncate hover:underline">
                {{ __('Opened by :user', {user: ticket.user.name}) }}
              </Link>
            </p>
            <svg class="size-0.5 fill-current" viewBox="0 0 2 2">
              <circle cx="1" cy="1" r="1"/>
            </svg>
            <p>
              <time :datetime="ticket.created_at">
                {{ ticket.created_at }}
              </time>
            </p>
          </div>
        </div>
        <dl class="flex w-full flex-none justify-start md:justify-between gap-x-8 sm:w-auto">
          <div v-if="ticket.comments.length > 0" class="flex -gap-x-0.5">
            <dt class="sr-only">Commenters</dt>
            <dd v-for="comment in getUniqueComments(ticket.comments)"
                :key="comment.id" v-tooltip="comment.user.name">
              <img :alt="comment.user.name"
                   :src="comment.user.profile_photo_url"
                   class="size-7 object-cover rounded-full bg-gray-50 ring-2 ring-white"/>
            </dd>
          </div>
          <div class="flex items-center w-16 gap-x-2.5">
            <dt class="flex items-center">
              <span class="sr-only">Total comments</span>
              <CircleCheckBig v-if="ticket.status === 3" aria-hidden="true"
                              class="size-4 text-gray-400"/>
              <MessageCircleMore v-else aria-hidden="true" class="size-4 text-gray-400"/>
            </dt>
            <dd class="text-xs/6 text-gray-900">{{ ticket.comments_count }}</dd>
          </div>
        </dl>
      </li>
    </ul>

    <div v-else>
      <CardEmptyState/>
    </div>

  </Card>
</template>

<style scoped>

</style>
