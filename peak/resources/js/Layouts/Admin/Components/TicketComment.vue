<script setup>

import {humanFileSize} from "@peak/Composables/Utils.js";
import {inject} from "vue";
import {router} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const props = defineProps({
  comment: Object
});

const dayJS = inject('dayJS');

const toast = useToast();

const emitter = inject('emitter');

const reportComment = () => {
  window.swal.fire({
    title: __('Report Comment'),
    input: "textarea",
    inputLabel: __('Reason for Reporting'),
    inputPlaceholder: __('Describe the issue...'),
    confirmButtonText: __('Submit Report'),
    inputValue: "",
    showCancelButton: true,
    inputValidator: (value) => {
      if (!value) {
        return __('Please enter a reason.');
      }
    }
  }).then((result) => {
    if (result.isConfirmed) {
      router.post(route('admin.support.ticket.comment.report', {
        reason: result.value,
        ticketComment: props.comment.id
      }), {}, {
        onSuccess: () => {
          toast.success(__('Comment reported successfully.'));
        },
        onError: () => {
          toast.error(__('Failed to report the comment.'));
        },
        preserveScroll: true
      });
    }
  });

};

const deleteComment = (ticketComment) => {
  window.swal.fire({
    title: __('Are you sure you want to delete this comment?'),
    showCancelButton: true,
    confirmButtonColor: 'tomato',
    confirmButtonText: __('Delete')
  }).then((result) => {
    if (result.isConfirmed) {
      router.delete(route('admin.support.ticket.comment.destroy', {ticketComment: props.comment.id}), {
        onSuccess: () => {
          toast.success(__('Comment deleted successfully.'));
        },
        onError: () => {
          toast.error(__('Failed to delete the comment.'));
        },
        preserveScroll: true
      });
    }
  });
};

const editComment = () => {
  emitter.emit('editComment', props.comment);
}

</script>

<template>
  <div :id="'comment-'+ comment.id" class="target:bg-yellow-100 ticket-comment">
    <article class="p-6 mb-3 bg-white/50 border border-zinc-200 text-base group rounded-lg dark:bg-gray-900">
      <footer class="flex flex-col justify-between md:flex-row space-y-4 md:space-y-0 md:items-center mb-2">
        <div class="flex items-center">
          <p class="inline-flex items-center ltr:mr-3 rtl:ml-3 text-sm text-gray-900 dark:text-white font-semibold">
            <img :alt="comment.user.name ?? comment.user.username" :src="comment.user.profile_photo_url"
                 class="ltr:mr-2 rtl:ml-2 w-10 h-10 rounded-full object-cover">
            {{ comment.user.name ?? comment.user.username }}
          </p>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            <time :datetime="comment.created_at" :title="comment.created_at">
              {{ comment.created_at }}
            </time>
          </p>
        </div>

        <div class="gap-x-2 items-center md:hidden flex group-hover:flex">
          <button
              class="inline-flex border border-zinc-200 group items-center p-2 text-sm font-medium text-center text-gray-500 dark:text-gray-400 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
              type="button"
              @click="deleteComment">

            <svg aria-hidden="true" class="w-4 h-4 text-red-400" data-slot="icon" fill="none"
                 stroke="currentColor"
                 stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path
                  d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                  stroke-linecap="round"
                  stroke-linejoin="round"></path>
            </svg>

            <span class="sr-only">{{ __('delete') }}</span>
          </button>

          <button
              class="inline-flex border border-zinc-200 items-center p-2 text-sm font-medium text-center text-gray-500 dark:text-gray-400 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
              type="button"
              @click="editComment">

            <svg aria-hidden="true" class="w-4 h-4" data-slot="icon" fill="none" stroke="currentColor"
                 stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path
                  d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125"
                  stroke-linecap="round"
                  stroke-linejoin="round"></path>
            </svg>

            <span class="sr-only">{{ __('edit') }}</span>
          </button>

          <button
              class="inline-flex border border-zinc-200 items-center p-2 text-sm font-medium text-center text-gray-500 dark:text-gray-400 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
              type="button"
              @click="reportComment">

            <svg aria-hidden="true" class="w-4 h-4" data-slot="icon" fill="none" stroke="currentColor"
                 stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path
                  d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"
                  stroke-linecap="round"
                  stroke-linejoin="round"></path>
            </svg>

            <span class="sr-only">{{ __('report') }}</span>
          </button>
        </div>
      </footer>

      <div
          class="text-gray-800 dark:text-gray-400 text-sm whitespace-pre-wrap break-words"
          v-text="comment.comment"/>

      <div v-if="comment.media.length"
           class="pt-6 flex flex-col space-y-2">

        <div class="flex items-center">

          <svg aria-hidden="true"
               class="-ml-1 ltr:mr-2 rtl:ml-2 h-5 w-5 text-gray-500" fill="currentColor"
               viewBox="0 0 20 20">
            <path clip-rule="evenodd"
                  d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z"
                  fill-rule="evenodd"/>
          </svg>

          <div class="text-sm text-gray-500">
                                            <span class="italic">
                                                {{ __('Attachments') }}
                                            </span>
          </div>
        </div>

        <ul class="text-sm space-y-1">
          <li v-for="(file, index) in comment.media" :key="index">
            <a :href="file.original_url" class="text-blue-700 hover:text-blue-500 break-words"
               target="_blank">{{
                file.name
              }} - ({{ file.mime_type }}) / {{ humanFileSize(file.size) }}
            </a>
          </li>
        </ul>
      </div>

    </article>
  </div>
</template>
