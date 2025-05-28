<script setup>

import DialogModal from "@peak/Components/Admin/DialogModal.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {inject, nextTick, onMounted, ref, watch} from "vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import TextInput from "@peak/Components/Admin/Input.vue";
import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import {useForm} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";
import {Switch, SwitchGroup} from "@headlessui/vue";
import Editor from '@toast-ui/editor'; // Import the TUI Editor
import '@toast-ui/editor/dist/toastui-editor.css'; // Import the CSS
import {__} from "@peak/Composables/useTranslate.js";

const editorContainer = ref(null); // Reference to the editor container
let editorInstance = null; // Editor instance variable

const editEmail = ref(false);
const emitter = inject('emitter');
// const email = ref(null);

const form = useForm({
  name: null,
  slug: null,
  subject: null,
  from_name: null,
  from_email: null,
  body: null,
  active: null
});

const mail = ref(null);

onMounted(() => {
  // Listen for the event to open the modal
  emitter.on('editEmail', async (transactionalEmail) => {
    // Show the modal
    editEmail.value = true;

    mail.value = transactionalEmail;

    // Set form values
    form.name = transactionalEmail.name;
    form.slug = transactionalEmail.slug;
    form.subject = transactionalEmail.subject;
    form.from_name = transactionalEmail.from_name;
    form.from_email = transactionalEmail.from_email;
    form.body = transactionalEmail.body;
    form.active = transactionalEmail.active;

    // Wait for the DOM to render the modal content
    await nextTick();

    // Initialize the editor
    if (editorContainer.value) {
      if (editorInstance) {
        // Destroy existing instance if modal is reopened
        editorInstance.destroy();
        editorInstance = null;
      }

      editorInstance = new Editor({
        el: editorContainer.value,
        height: '500px',
        initialEditType: 'markdown',
        initialValue: form.body, // Set initial value from form.body
      });

      // Listen for changes and update form.body
      editorInstance.on('change', () => {
        form.body = editorInstance.getMarkdown();
      });
    } else {
      console.error("Editor container is null. Ensure the ref is correctly set.");
    }
  });
});

// Ensure editor instance is destroyed when the modal is hidden
watch(editEmail, (newVal) => {
  if (!newVal && editorInstance) {
    editorInstance.destroy();
    editorInstance = null;
  }
});

const toast = useToast();

const save = () => {

  form.put(route('admin.settings.marketing.transactional-emails.update', {mailTemplate: mail.value.id}), {
    onSuccess: () => {
      toast.success(__('Email settings saved'));
      editEmail.value = false;
    },
    preserveScroll: false
  });
};

const view = ref('settings');

const changeView = newViewVal => {
  view.value = newViewVal;
};

const getMarkdown = () => {
  if (editorInstance) {
    const markdownContent = editorInstance.getMarkdown();
    console.log(markdownContent);
  } else {
    console.error("Editor instance is not initialized.");
  }
};

const close = () => {
  editEmail.value = false;
  view.value = 'settings';
  form.reset();
  form.clearErrors();
}

</script>

<template>
  <DialogModal :show="editEmail" max-width="2xl" @close="editEmail = false">
    <template #title>
      Edit {{ mail.name }} <span class="uppercase">({{ mail.language }})</span>
    </template>
    <template #content>

      <div class="mb-6 border-b border-gray-200">
        <nav aria-label="Tabs" class="-mb-px flex gap-x-8">
          <!-- Current: "border-blue-500 text-blue-600", Default: "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" -->
          <button
              :class="[view === 'settings' ? 'group inline-flex items-center border-b-2 border-blue-500 px-1 py-4 text-sm font-medium text-blue-600' : 'group inline-flex items-center border-b-2 border-transparent px-1 py-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700']"
              aria-current="page"
              @click="changeView('settings')">
            <svg
                :class="[view === 'settings' ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500']"
                aria-hidden="true" class="-ml-0.5 mr-2 size-5" data-slot="icon" fill="none"
                stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
              <path
                  d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"
                  stroke-linecap="round"
                  stroke-linejoin="round"></path>
              <path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" stroke-linecap="round"
                    stroke-linejoin="round"></path>
            </svg>

            <span>Settings</span>
          </button>

          <button
              :class="[view === 'edit_content' ? 'group inline-flex items-center border-b-2 border-blue-500 px-1 py-4 text-sm font-medium text-blue-600' : 'group inline-flex items-center border-b-2 border-transparent px-1 py-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700']"
              @click="changeView('edit_content')">
            <svg
                :class="[view === 'edit_content' ? 'text-blue-500' : 'text-gray-400 group-hover:text-gray-500']"
                aria-hidden="true" class="-ml-0.5 mr-2 size-5" data-slot="icon" fill="none"
                stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
              <path
                  d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                  stroke-linecap="round"
                  stroke-linejoin="round"></path>
            </svg>

            <span>Edit Content</span>
          </button>
        </nav>
      </div>

      <div v-show="view === 'edit_content'" class="grid grid-cols-4 gap-5">
        <div class="col-span-4">

          <div class="mb-4">
            Available variables:
            <span v-for="field in mail.fields"
                  class="inline-flex items-center hover:bg-gray-100 mr-1 mb-1 rounded-full bg-gray-50 px-1.5 py-0.5 text-sm font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
    &#123;&#123; {{ field }} &#125;&#125;
</span>
          </div>

          <div ref="editorContainer">
            <span>zefzef</span>
          </div>
        </div>
      </div>

      <div v-show="view === 'settings'" class="grid grid-cols-4 gap-5">
        <div class="col-span-2">
          <div class="flex flex-col space-y-2">
            <InputLabel for="name" value="Name"/>
            <TextInput id="name" v-model="form.name"
                       class="w-full block"
                       placeholder="Name"/>
            <InputError :message="form.errors?.name" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2">
          <div class="flex flex-col space-y-2">
            <InputLabel for="slug" value="Slug"/>
            <TextInput id="slug" v-model="form.slug" class="w-full block bg-gray-50"
                       disabled="disabled"
                       placeholder="Slug"/>
            <InputError :message="form.errors?.slug" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2">
          <div class="flex flex-col space-y-2">
            <InputLabel for="from_name" value="From name"/>
            <TextInput id="from_name" v-model="form.from_name"
                       class="w-full block"
                       placeholder="From name"/>
            <InputError :message="form.errors?.from_name" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2">
          <div class="flex flex-col space-y-2">
            <InputLabel for="from_email" value="From email"/>
            <TextInput id="from_email" v-model="form.from_email"
                       class="w-full block"
                       placeholder="From email"/>
            <InputError :message="form.errors?.from_email" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-4">
          <div class="flex flex-col space-y-2">
            <InputLabel for="subject" value="Subject"/>
            <TextInput id="subject" v-model="form.subject"
                       class="w-full block"
                       placeholder="Subject"/>
            <InputError :message="form.errors?.subject" class="mt-1"/>
          </div>
        </div>

        <div class="col-span-2">
          <div class="flex flex-col space-y-2">
            <InputLabel for="active" value="Active"/>

            <SwitchGroup as="div"
                         class="flex items-center justify-between">
              <Switch v-model="form.active"
                      :class="[form.active ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                                                        <span
                                                                            :class="[form.active ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                                                            aria-hidden="true"/>
              </Switch>
            </SwitchGroup>

            <InputError :message="form.errors?.active" class="mt-1"/>
          </div>
        </div>
      </div>
    </template>
    <template #footer>
      <PrimaryButton :class="{'opacity-75':form.processing || !form.isDirty}"
                     :disabled="form.processing || !form.isDirty" :loading="form.processing"
                     @click="save">
        Save
      </PrimaryButton>
    </template>
  </DialogModal>
</template>

<style>
.tiny-editable:hover:not(:focus),
.tiny-editable:focus {
  outline: 3px solid #b4d7ff;
}

.tiny-editable:empty::before {
  content: "Write here...";
}
</style>
