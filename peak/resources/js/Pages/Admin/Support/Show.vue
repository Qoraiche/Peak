<script setup>
import {inject, onMounted, ref} from "vue";
import {humanFileSize, scrollTo} from "@peak/Composables/Utils.js";
import InputError from "@peak/Components/Admin/InputError.vue";
import {Link, useForm} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";
import TicketComment from "@peak/Layouts/Admin/Components/TicketComment.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AlertInfo from "@peak/Components/Admin/Alerts/AlertInfo.vue";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import {__} from "@peak/Composables/useTranslate.js";

const props = defineProps({
  ticket: Object,
  mediaUrls: Object,
  errors: Object
});

const statuses = [
  {id: 1, name: __('Open')},
  {id: 2, name: __('Pending')},
  {id: 3, name: __('Solved')},
  {id: 4, name: __('Closed')},
  {id: 5, name: __('Draft')},
];

const selectedStatus = ref(statuses.find(status => status.id === props.ticket.status));

const editingComment = ref(false);
const editedCommentId = ref(null);

const newCommentForm = useForm({
  comment: '',
  type: 'comment',
  files: null,
  _method: 'post'
});

const cancelEditingComment = () => {
  editingComment.value = false;
  newCommentForm.reset('comment', 'files', '_method');
};

// Watch for changes in `newCommentForm.comment`
/*watch(() => newCommentForm.comment, (newValue, oldValue) => {
    if (newValue === '' || newValue === null) {
        editingComment.value = false;
    }
});*/

const toast = useToast();

const addComment = () => {
  if (!editingComment.value) {
    return newCommentForm.post(route('admin.support.ticket.comment.store', props.ticket.uuid), {
      preserveScroll: true,
      onSuccess: () => {
        toast.success(__('Reply added'));
        newCommentForm.reset();
      },
      onError: () => {
        toast.error(__('Something went wrong.'));
      },
    });
  }

  // set method to fake put to handle uploads
  if (editedCommentId.value) {

    newCommentForm._method = 'put';

    newCommentForm.post(route('admin.support.ticket.comment.update', editedCommentId.value), {
      onSuccess: () => {
        toast.success(__('Reply saved'));
        newCommentForm.reset();
      },
      preserveScroll: true
    });
  }
}

const commentInput = ref(null);

const adjustTextarea = () => {
  commentInput.value.style.height = 'auto';
  commentInput.value.style.height = `${commentInput.value.scrollHeight}px`;
};

const fileInput = (event) => {
  if (event.target.files?.length > 10) {
    toast.warning(__('Only maximum of 10 files allowed to attach, use file compression instead (zip, rar)'));
    return;
  }

  // newCommentForm.files = event.target.files;
  newCommentForm.files = event.target?.files;
}

const removeFile = () => {
  if (newCommentForm.files?.length > 0) {
    newCommentForm.files = null;
  }
}

const dayJS = inject('dayJS');
const emitter = inject('emitter');

const formattedTotalPrice = (price) => {
  return price.toLocaleString('en-US', {style: 'currency', currency: 'USD'});
};

const priorityNameById = id => {
  if (id === 1) {
    return __('Low');
  } else if (id === 2) {
    return __('Normal');
  } else if (id === 3) {
    return __('High');
  } else if (id === 4) {
    return __('Urgent');
  }
};

const statusClassNameById = id => {
  if (id === 1 || id === 5) {
    return 'bg-white';
  } else if (id === 2) {
    return 'bg-orange-50/60';
  } else if (id === 3) {
    return 'bg-green-50/60';
  } else if (id === 4) {
    return 'bg-red-50/60';
  }
};

onMounted(() => {
  emitter.on('editComment', function (comment) {
    scrollTo('#add-ticket-comment-form');
    newCommentForm.comment = comment.comment;
    newCommentForm.files = null;
    editingComment.value = true;
    editedCommentId.value = comment.id;

    if (commentInput.value) {
      // alert('hi');
      const element = commentInput.value.$el || commentInput.value; // Handles both Vue component and native elements
      element.focus();
    }
  });
});

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
  <AdminLayout :description="__('View Ticket #:id details', {id: ticket.id})" :page-icon="false"
               :title="__('Ticket #:id', {id: ticket.id})">
    <div class="max-w-7xl mx-auto flex flex-col space-y-4">
      <div :class="[statusClassNameById(ticket.status), 'bg-zinc-50 rounded-xl py-4 px-4 border border-blue-100']">
        <div class="grid grid-cols-1 lg:grid-cols-3">
          <div class="xl:col-span-2 xl:border-r xl:border-gray-200 xl:pr-8">
            <div>
              <div>
                <div
                    class="md:flex md:items-center md:justify-between md:gap-x-4 xl:border-b xl:pb-6">
                  <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ ticket.subject }}</h1>
                    <p class="mt-2 text-sm text-gray-500">
                      {{ __('Ticket') }} <span class="font-semibold text-black">#{{ ticket.id }}</span>
                      {{ __('Created on :date', {date: ticket.created_at}) }}
                    </p>
                  </div>
                </div>

                <div class="flex flex-col w-full">
                  <h2 class="sr-only">
                    {{ __('Description') }}
                  </h2>
                  <div
                      class="mt-4 md:mt-0 prose-sm bg-white/60 pt-4 px-4 rounded-md prose prose-slate w-full max-w-none prose-a:font-semibold prose-a:text-blue-600 prose-a:hover:text-blue-700">
                    <Link :href="route('admin.support.tickets.edit', ticket.uuid)">
                      {{ __('Edit Ticket') }}
                    </Link>
                    <p v-if="ticket.description" class="break-words pb-4 bg-transparent"
                       v-html="ticket.description"></p>
                    <span v-else class="italic text-sm">
                      {{ __('No ticket description.') }}
                    </span>
                  </div>

                  <div v-if="mediaUrls.length > 0" class="p-3 mt-3 flex flex-col space-y-2 border">
                    <div class="flex items-center">
                      <svg aria-hidden="true"
                           class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="currentColor"
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
                      <li v-for="(file, index) in mediaUrls">
                        <a :href="file.url" class="text-blue-700 hover:text-blue-500"
                           target="_blank">
                          {{ file.name }} - ({{ file.mime_type }}) /
                          {{ humanFileSize(file.size) }}
                        </a>
                      </li>
                    </ul>
                  </div>

                </div>
              </div>
            </div>
            <section aria-labelledby="comment-title" class="mt-8 xl:mt-10">
              <div>
                <div>
                  <div class="border-b border-gray-200">
                    <h2 class="text-lg font-semibold mb-3">
                      {{ __('Comments') }}
                    </h2>
                  </div>

                  <div class="pt-6">
                    <!-- Comment feed-->

                    <div v-for="comment in ticket.comments" :key="comment.id">
                      <TicketComment :comment="comment"/>
                    </div>

                    <div class="mt-6">
                      <div class="flex gap-3">
                        <div class="flex-shrink-0">
                          <div class="relative">
                            <img
                                :src="$page.props.auth.user.profile_photo_url"
                                alt="profile"
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-400 ring-8 ring-white">
                            <span
                                class="absolute -bottom-0.5 -right-1 rounded-tl bg-white px-0.5 py-px">
                                                                                    <svg aria-hidden="true"
                                                                                         class="h-5 w-5 text-gray-400"
                                                                                         fill="currentColor"
                                                                                         viewBox="0 0 20 20">
                                                      <path clip-rule="evenodd"
                                                            d="M10 2c-2.236 0-4.43.18-6.57.524C1.993 2.755 1 4.014 1 5.426v5.148c0 1.413.993 2.67 2.43 2.902.848.137 1.705.248 2.57.331v3.443a.75.75 0 001.28.53l3.58-3.579a.78.78 0 01.527-.224 41.202 41.202 0 005.183-.5c1.437-.232 2.43-1.49 2.43-2.903V5.426c0-1.413-.993-2.67-2.43-2.902A41.289 41.289 0 0010 2zm0 7a1 1 0 100-2 1 1 0 000 2zM8 8a1 1 0 11-2 0 1 1 0 012 0zm5 1a1 1 0 100-2 1 1 0 000 2z"
                                                            fill-rule="evenodd"></path>
                                                    </svg>
                                                    </span>
                          </div>
                        </div>

                        <div id="add-ticket-comment-form" class="min-w-0 flex-1">
                          <div class="relative">
                            <div class="overflow-hidden rounded-lg">
                              <div>
                                                                <textarea id="comment"
                                                                          ref="commentInput"
                                                                          v-model="newCommentForm.comment"
                                                                          class="autoresize overflow-hidden block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                                          rows="3"
                                                                          @input="adjustTextarea">
                                                                </textarea>
                              </div>

                              <InputError :message="newCommentForm.errors?.comment"/>
                            </div>

                            <div class="mt-3 flex flex-col space-y-3">

                              <div
                                  class="flex flex-col space-y-3 border-t border-gray-200 px-2 py-5 bg-gray-50 sm:px-3">

                                <AlertInfo v-if="editingComment">
                                  {{ __('Attachments in edited comments must be re-uploaded.') }}
                                </AlertInfo>

                                <progress v-if="newCommentForm.progress"
                                          :value="newCommentForm.progress?.percentage"
                                          class="rounded-lg"
                                          max="100">
                                  {{ newCommentForm.progress?.percentage }}%
                                </progress>

                                <div
                                    class="flex items-center justify-between gap-x-3">
                                  <div class="flex">
                                    <label
                                        class="group cursor-pointer -my-2 ltr:-ml-2 rtl:-mr-2 inline-flex items-center rounded-full px-3 py-2 text-left text-gray-400"
                                        for="filesInput">
                                      <svg
                                          aria-hidden="true"
                                          class="rtl:ml-2 ltr:mr-2 h-5 w-5 group-hover:text-gray-500"
                                          fill="currentColor"
                                          viewBox="0 0 20 20">
                                        <path clip-rule="evenodd"
                                              d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z"
                                              fill-rule="evenodd"/>
                                      </svg>

                                      <span
                                          class="text-sm italic text-gray-500 group-hover:text-gray-600">
                                                {{
                                          newCommentForm.files?.length > 0 ? 'Re-attach file(s)' : 'Attach ticket file(s)'
                                        }}
                                            </span>
                                    </label>

                                    <div
                                        class="flex flex-col gap-x-1 items-center">
                                      <input id="filesInput"
                                             accept=".doc, .docx, .md, .pdf, .txt, .xls, .xlsx, .csv, .ppt, .pptx, .jpg, .jpeg, .png, .gif, .bmp, .svg, .rar, .zip, .tar, .gz, .7z, .php, .css, .scss, .env, .mp3, .mp4, .lock, .avi, .wav, .html, .json, .xml, .gitignore, .yaml"
                                             class="hidden" multiple
                                             type="file"
                                             @input="fileInput">

                                      <span
                                          v-if="newCommentForm.files?.length > 0 && !newCommentForm.processing"
                                          class="text-xs text-gray-500 italic">
                                        ({{
                                          __(':totalattached file(s) attached)', {totalattached: newCommentForm.files?.length})
                                        }}
                                                                        </span>

                                      <span
                                          v-if="newCommentForm.files?.length > 0 && !newCommentForm.processing"
                                          class="text-blue-600 cursor-pointer text-xs over:underline"
                                          @click="removeFile">
                                        {{ __('&times; Clear selected') }}
                                                                            </span>
                                    </div>

                                  </div>

                                  <div class="flex-shrink-0">
                                    <div class="flex items-center gap-x-3">
                                      <SecondaryButton
                                          v-if="editingComment"
                                          @click="cancelEditingComment">
                                        {{ __('Cancel edit') }}
                                      </SecondaryButton>

                                      <PrimaryButton
                                          :class="{'opacity-50': newCommentForm.processing || newCommentForm.comment === ''}"
                                          :disabled="newCommentForm.processing || newCommentForm.comment === ''"
                                          @click="addComment">
                                        {{
                                          !editingComment ? __('Add comment') : __('Update comment')
                                        }}
                                      </PrimaryButton>
                                    </div>
                                  </div>
                                </div>

                                <div>
                                  <ul class="text-sm space-y-1">
                                    <li v-for="(file, index) in newCommentForm.files"
                                        v-if="newCommentForm.files?.length > 0"
                                        :class="{'text-red-600': errors['files.'+index]}">
                                      {{ file.name }} -
                                      {{ humanFileSize(file.size) }}
                                      <span v-if="errors['files.'+index]"
                                            class="italic">- {{
                                          errors['files.' + index]
                                        }}</span>
                                    </li>
                                  </ul>

                                  <InputError :message="errors?.files" class="mt-2"/>
                                </div>
                              </div>
                            </div>

                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
          <aside class="block ltr:pl-3 rtl:pr-3 ltr:md:border-l rtl:md:border-r border-gray-200">
            <div class="space-y-5 pb-6">
              <h2 class="text-base font-medium text-gray-500">{{ __('Ticket Details') }}</h2>

              <div class="flex items-center gap-x-2">
                <!--                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                                 aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                      d="M10 2c-2.236 0-4.43.18-6.57.524C1.993 2.755 1 4.014 1 5.426v5.148c0 1.413.993 2.67 2.43 2.902.848.137 1.705.248 2.57.331v3.443a.75.75 0 001.28.53l3.58-3.579a.78.78 0 01.527-.224 41.202 41.202 0 005.183-.5c1.437-.232 2.43-1.49 2.43-2.903V5.426c0-1.413-.993-2.67-2.43-2.902A41.289 41.289 0 0010 2zm0 7a1 1 0 100-2 1 1 0 000 2zM8 8a1 1 0 11-2 0 1 1 0 012 0zm5 1a1 1 0 100-2 1 1 0 000 2z"
                                                      clip-rule="evenodd"></path>
                                            </svg>-->

                <svg v-if="ticket.status === 1" aria-hidden="true" class="h-5 w-5 text-gray-400"
                     data-slot="icon"
                     fill="none"
                     stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                  <path
                      d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"
                      stroke-linecap="round"
                      stroke-linejoin="round"></path>
                </svg>

                <svg v-if="ticket.status === 2" aria-hidden="true" class="h-5 w-5 text-gray-400"
                     data-slot="icon"
                     fill="none" stroke="currentColor" stroke-width="1.5"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg>

                <svg v-if="ticket.status === 4" aria-hidden="true" class="h-5 w-5 text-gray-400"
                     data-slot="icon" fill="none" stroke="currentColor" stroke-width="1.5"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path
                      d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"
                      stroke-linecap="round"
                      stroke-linejoin="round"></path>
                </svg>

                <svg v-if="ticket.status === 3" aria-hidden="true" class="h-5 w-5 text-gray-400"
                     data-slot="icon"
                     fill="none" stroke="currentColor" stroke-width="1.5"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path d="m4.5 12.75 6 6 9-13.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg>

                <svg v-if="ticket.status === 5" aria-hidden="true" class="h-5 w-5 text-gray-400"
                     data-slot="icon"
                     fill="none" stroke="currentColor" stroke-width="1.5"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path
                      d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"
                      stroke-linecap="round"
                      stroke-linejoin="round"></path>
                </svg>

                <span v-if="ticket.status === 1"
                      class="text-sm font-medium text-green-600">{{ __('Open') }}</span>
                <span v-if="ticket.status === 2"
                      class="text-sm font-medium text-orange-600">{{ __('Pending') }}</span>
                <!--                            <span class="text-sm font-medium text-green-600" v-if="ticket.status === 2">Solved</span>-->
                <span v-if="ticket.status === 3"
                      class="text-sm font-medium text-green-700">{{ __('Solved') }}</span>
                <span v-if="ticket.status === 4"
                      class="text-sm font-medium text-red-600">{{ __('Closed') }}</span>
                <span v-if="ticket.status === 5"
                      class="text-sm font-medium text-gray-600">{{ __('Draft') }}</span>
              </div>

              <div class="flex items-center gap-x-2">
                <svg aria-hidden="true" class="h-5 w-5 text-gray-400" data-slot="icon" fill="none"
                     stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                  <path
                      d="M3 3v1.5M3 21v-6m0 0 2.77-.693a9 9 0 0 1 6.208.682l.108.054a9 9 0 0 0 6.086.71l3.114-.732a48.524 48.524 0 0 1-.005-10.499l-3.11.732a9 9 0 0 1-6.085-.711l-.108-.054a9 9 0 0 0-6.208-.682L3 4.5M3 15V4.5"
                      stroke-linecap="round"
                      stroke-linejoin="round"></path>
                </svg>
                <span class="text-sm text-gray-900">
                  {{ __(':priority Priority', {priority: priorityNameById(ticket.priority)}) }}
                </span>
              </div>

              <div class="flex items-center gap-x-2">
                <svg aria-hidden="true" class="h-5 w-5 text-gray-400" fill="currentColor"
                     viewBox="0 0 20 20">
                  <path clip-rule="evenodd"
                        d="M10 2c-2.236 0-4.43.18-6.57.524C1.993 2.755 1 4.014 1 5.426v5.148c0 1.413.993 2.67 2.43 2.902.848.137 1.705.248 2.57.331v3.443a.75.75 0 001.28.53l3.58-3.579a.78.78 0 01.527-.224 41.202 41.202 0 005.183-.5c1.437-.232 2.43-1.49 2.43-2.903V5.426c0-1.413-.993-2.67-2.43-2.902A41.289 41.289 0 0010 2zm0 7a1 1 0 100-2 1 1 0 000 2zM8 8a1 1 0 11-2 0 1 1 0 012 0zm5 1a1 1 0 100-2 1 1 0 000 2z"
                        fill-rule="evenodd"></path>
                </svg>
                <span class="text-sm text-gray-900">
                  {{ __(':length comments', {length: ticket.comments.length}) }}
                </span>
              </div>

              <div class="flex items-center gap-x-2">
                <svg aria-hidden="true" class="h-5 w-5 text-gray-400" fill="currentColor"
                     viewBox="0 0 20 20">
                  <path clip-rule="evenodd"
                        d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z"
                        fill-rule="evenodd"></path>
                </svg>

                <span class="text-sm text-gray-900">
                  {{ __('Updated on :date', {date: ticket.updated_at}) }}
                </span>
              </div>

              <div class="flex items-center gap-x-2">
                <dd v-for="comment in getUniqueComments(ticket.comments)" :key="comment.id"
                    v-tooltip="comment.user.name">
                  <img :alt="comment.user.name"
                       :src="comment.user.profile_photo_url"
                       class="size-8 rounded-full bg-gray-50"/>
                </dd>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
