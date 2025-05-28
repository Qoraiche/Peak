<script setup>

import {router, useForm} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";
import {ref} from "vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import DangerButton from "@peak/Components/Admin/DangerButton.vue";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import TextArea from "@peak/Components/Admin/TextArea.vue";
import {SquarePen, Trash2} from "lucide-vue-next";
import Card from "@peak/Components/Admin/Card.vue";
import {__} from "@peak/Composables/useTranslate.js";

const props = defineProps({
  notes: Array,
  userId: Number
});

const commentInput = ref();
const editableNote = ref(null);

const form = useForm({
  comment: null,
});

const errors = ref({});
const toast = useToast();

const editNote = note => {
  editableNote.value = note;
  form.comment = editableNote.value.note;

  if (commentInput.value) {
    const element = commentInput.value.$el || commentInput.value; // Handles both Vue component and native elements
    element.focus();
  }
}

const cancelEditNote = () => {
  editableNote.value = null;
  form.reset('comment');
}

const deleteNote = note => {
  router.delete(route('admin.user-management.notes.destroy', note.id), {
    onSuccess: () => {
      toast.success(__('Note deleted'));
    }
  });
};

const submit = () => {
  let routeName = editableNote.value !== null ? 'admin.user-management.notes.update' : 'admin.user-management.notes.store';
  let params = editableNote.value === null ? {user: props.userId} : {note: editableNote.value.id};
  let formMethod = editableNote.value !== null ? 'put' : 'post';

  form[formMethod](route(routeName, params), {
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('Note created'));
      form.reset('comment');
    },
    onError: err => {
      for (let key in err) {
        if (err.hasOwnProperty(key)) {
          toast.error(err[key])
        }
      }
      errors.value = err;
    },
  });
}

</script>

<template>

  <Card :collapsible="true">
    <template #header>
      {{ __('Notes') }}
    </template>

    <div>
      <ul class="space-y-8" role="list">
        <li v-for="note in notes" :key="note.id">
          <div class="flex gap-x-3 group">
            <div class="flex-shrink-0">
              <img :alt="note.poster.name"
                   :src="note.poster.profile_photo_url"
                   class="h-10 w-10 rounded-full"/>
            </div>
            <div>
              <div class="text-sm">
                <a :href="route('admin.user-management.users.edit', note.poster.id)"
                   class="font-medium text-gray-900">
                  {{
                    `${note.poster.name}`
                  }}</a>
              </div>
              <div class="mt-1 text-sm text-gray-700">
                <p class="whitespace-pre-line">
                  {{ note.note }}
                </p>
              </div>
              <div class="mt-2 gap-x-2 text-xs">
                    <span class="text-gray-500">
                      {{ note.created_at }}
                    </span>

                {{ ' ' }}
                <span
                    class="font-medium text-gray-500 invisible group-hover:visible">&middot;</span>
                {{ ' ' }}

                <SecondaryButton class="invisible group-hover:visible" type="button"
                                 @click="editNote(note)">
                  <SquarePen class="!px-0 h-4 w-4 text-gray-600"/>
                </SecondaryButton>

                <DangerButton class="rtl:mr-2 ltr:ml-2 invisible group-hover:visible !rounded-md !text-xs"
                              type="button"
                              @click="deleteNote(note)">
                  <Trash2 class="h-4 w-4 text-white"/>
                </DangerButton>
                {{ ' ' }}
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <div class="py-6">
      <div class="flex gap-x-3">
        <div class="flex-shrink-0">
          <img :src="$page.props.auth.user.profile_photo_url"
               alt=""
               class="h-10 w-10 object-cover rounded-full"/>
        </div>

        <div class="min-w-0 flex-1">
          <form @submit.prevent="submit">
            <div>
              <label class="sr-only" for="comment">
                {{ __('Note') }}
              </label>

              <TextArea id="comment" ref="commentInput" v-model="form.comment" :placeholder="__('Write note')"
                        class="w-full text-sm"
                        cols="50" name="comment" required rows="3">
              </TextArea>
            </div>

            <InputError :message="errors.comment" class="mt-2"/>

            <div class="mt-3 flex items-center gap-x-2 justify-end">
              <SecondaryButton v-if="editableNote !== null" @click="cancelEditNote">
                {{ __('Cancel Edit') }}
              </SecondaryButton>

              <PrimaryButton :class="{'opacity-25': form.processing}" :disabled="form.processing"
                             type="submit">
                {{ editableNote === null ? __('Add Note') : __('Update Note') }}
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>

  </Card>
</template>

<style scoped>

</style>
