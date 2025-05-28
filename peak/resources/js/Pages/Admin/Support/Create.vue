<script setup>
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import Card from "@peak/Components/Admin/Card.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import Input from "@peak/Components/Admin/Input.vue";
import {computed, defineProps} from "vue";
import SelectUser from "@peak/Layouts/Admin/Components/SelectUser.vue";
import SelectInput from "@peak/Components/Admin/SelectInput.vue";
import {humanFileSize} from '@peak/Composables/Utils.js';
import TinymceEditor from "@peak/Components/Admin/TinymceEditor.vue";
import {__} from "@peak/Composables/useTranslate.js";
import {useToast} from "vue-toastification";

const props = defineProps({
  errors: Object
});

const toast = useToast();

const authUser = usePage().props.auth?.user;

const statuses = [
  {key: '1', value: __('Open')},
  {key: '2', value: __('Pending')},
  {key: '3', value: __('Solved')},
  {key: '4', value: __('Closed')},
  {key: '5', value: __('Draft')}
];

const priorities = [
  {key: '1', value: __('Low')},
  {key: '2', value: __('Normal')},
  {key: '3', value: __('High')},
  {key: '4', value: __('Urgent')}
];

const form = useForm({
  subject: '',
  description: '',
  author: authUser.id,
  priority: priorities[0]?.key,
  status: statuses[0]?.key,
  files: null
});

const canPost = computed(() => {
  return form.subject !== '';
});

const userSelected = (user) => {
  form.author = user.id;
}

const fileInput = (event) => {

  /*if (event.target.files?.length > 10) {
    toast.warning(__('Only maximum of 10 files allowed to attach, use file compression instead (zip, rar)'));
    return;
  }*/

  form.files = event.target.files;
}

const removeFile = () => {
  if (form.files?.length > 0) {
    form.files = null;
  }
};

const submit = () => {

  form.post(route('admin.support.tickets.store'), {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('Ticket created'));
    },
    onError: () => {
      toast.error(__('Something went wrong.'));
    }
  })
}

</script>

<template>
  <AdminLayout :description="__('Add a new support ticket')" :page-icon="false" :title="__('New Ticket')">
    <template v-slot:actions>
      <PrimaryButton :class="{'opacity-75': form.processing || form.subject === ''}"
                     :disabled="form.processing || form.subject === ''"
                     :loading="form.processing"
                     @click="submit">
        {{ __('Create') }}
      </PrimaryButton>
    </template>

    <div class="mx-auto mt-8 grid max-w-3xl grid-cols-1 gap-6 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
      <div class="space-y-6 lg:col-span-2 lg:col-start-1">
        <Card :has-error="form.errors?.length > 0">
          <template #header>
            {{ __('Content') }}
          </template>

          <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="sm:col-span-2 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="subject">{{ __('Subject') }}<span
                  class="text-red-600">*</span>
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <Input id="subject" v-model="form.subject"
                       class="w-full"/>
                <InputError :message="form.errors?.subject" class="mt-2"/>
              </dd>
            </div>

            <div class="col-span-2 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="description">
                {{ __('Description') }}
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <TinymceEditor
                    v-model="form.description"
                    :init="{
                                    height: 470,
                                      branding: false,
                                      toolbar_mode: 'sliding',
                                      plugins: [
                                        // Core editing features
                                        'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount'
                                      ],
                                      toolbar: 'code | undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                    }"
                />

                <InputError :message="form.errors?.description" class="mt-2"/>
              </dd>

              <div class="p-3 bg-gray-50 flex flex-col space-y-2">
                <div class="flex items-center gap-x-2">

                  <progress v-if="form.progress" :value="form.progress?.percentage"
                            class="rounded-lg"
                            max="100">
                    {{ form.progress?.percentage }}%
                  </progress>

                  <span v-if="form.progress"
                        class="text-xs">{{ form.progress?.percentage }}%</span>

                  <label v-if="!form.progress"
                         class="group cursor-pointer -my-2 -ml-2 inline-flex items-center rounded-full px-3 py-2 text-left text-gray-400"
                         for="filesInput">
                    <svg aria-hidden="true"
                         class="-ml-1 mr-2 h-5 w-5 group-hover:text-gray-500" fill="currentColor"
                         viewBox="0 0 20 20">
                      <path clip-rule="evenodd"
                            d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z"
                            fill-rule="evenodd"/>
                    </svg>

                    <span class="flex items-center gap-x-3">
                                            <span class="text-sm text-gray-500 group-hover:text-gray-600">
                                            <span class="italic">
                                                {{
                                                form.files?.length > 0 ? __('Re-attach file(s)') : __('Attach file(s)')
                                              }}
                                            </span>
                                            </span>
                                        </span>
                  </label>

                  <input id="filesInput"
                         accept=".doc, .docx, .md, .pdf, .txt, .xls, .xlsx, .csv, .ppt, .pptx, .jpg, .jpeg, .png, .gif, .bmp, .svg, .rar, .zip, .tar, .gz, .7z, .php, .css, .scss, .env, .mp3, .mp4, .lock, .avi, .wav, .html, .json, .xml, .gitignore, .yaml"
                         class="hidden" multiple
                         type="file"
                         @input="fileInput">

                  <span v-if="form.files?.length > 0 && !form.processing"
                        class="text-xs text-gray-500 italic">

                    ({{ __(':totalattached file(s) attached)', {totalattached: form.files?.length}) }}
                                    </span>

                  <span
                      v-if="form.files?.length > 0 && !form.processing"
                      class="text-purple-600 cursor-pointer text-xs over:underline"
                      @click="removeFile">{{ __('&times; Clear selected') }}</span>
                </div>

                <ul class="text-sm space-y-1">
                  <li v-for="(file, index) in form.files"
                      v-if="form.files?.length > 0"
                      :class="{'text-red-600': errors['files.'+index]}">
                    {{ file.name }} - {{ humanFileSize(file.size) }} <span
                      v-if="errors['files.'+index]"
                      class="italic">- {{
                      errors['files.' + index]
                    }}</span>
                  </li>
                </ul>

                <InputError :message="form.errors?.files" class="mt-2"/>
              </div>
            </div>
          </dl>
        </Card>
      </div>

      <div>
        <Card :has-error="form.errors?.length > 0">
          <div class="flex flex-col space-y-6">
            <div>
              <label class="text-sm font-medium text-gray-600" for="author">
                {{ __('Author') }}
                <span
                    class="text-red-600">*</span>
              </label>

              <SelectUser :limit="20" :pre-selected-user="authUser" :roles="$page.props.config.admin_roles"
                          @userSelected="userSelected"/>

              <InputError :message="form.errors?.author" class="mt-2"/>
            </div>

            <div>
              <label class="text-sm font-medium text-gray-600" for="priority">
                {{ __('Priority') }}<span
                  class="text-red-600">*</span>
              </label>

              <SelectInput v-model="form.priority" :values="priorities"/>

              <InputError :message="form.errors?.priority" class="mt-2"/>

            </div>

            <div>
              <label class="text-sm font-medium text-gray-600" for="priority">
                {{ __('Status') }} <span
                  class="text-red-600">*</span>
              </label>

              <SelectInput v-model="form.status" :values="statuses"/>

              <InputError :message="form.errors?.status" class="mt-2"/>
            </div>
          </div>
        </Card>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
