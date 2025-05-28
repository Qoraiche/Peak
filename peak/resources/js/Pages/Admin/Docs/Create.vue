<script setup>

import Card from "@peak/Components/Admin/Card.vue";
import Input from "@peak/Components/Admin/Input.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import TextArea from "@peak/Components/Admin/TextArea.vue";
import {extractHeadingsFromEditorJs, slugify} from '@peak/Composables/Utils.js';
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import InputError from "@peak/Layouts/Admin/Components/InputError.vue";
import SelectUser from "@peak/Layouts/Admin/Components/SelectUser.vue";
import {useForm} from "@inertiajs/vue3";
import {computed, defineProps, ref} from "vue";
import {watchDebounced} from "@vueuse/core";
import {useAuth} from "@peak/Composables/useAuth.js";
import {Switch, SwitchDescription, SwitchGroup} from "@headlessui/vue";
import EditorJs from "@peak/Components/Admin/EditorJs.vue";
import {Maximize} from "lucide-vue-next";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const {user} = useAuth();
const toast = useToast();

// Assuming the categories are being passed as a prop
const props = defineProps({
  categories: Array
});

const authUser = user.value;

const form = useForm({
  published: true,
  category: null,
  title: '',
  author: authUser?.id,
  slug: '',
  label: '',
  excerpt: '',
  published_at: null,
  body: '',
  headings: []
});

const categories = ref(props.categories);

const addNewCategory = (category) => {
  const newCategory = {id: `new-${Date.now()}`, name: category};
  // Push the new category to the options list
  categories.value.push(newCategory);
  // Add the new category value to the selected categories array
  form.category = newCategory;
};

const submit = (createNew = false) => {
  form.headings = extractHeadingsFromEditorJs(form.body?.blocks)?.headings;

  form.post(route('admin.docs.store'), {
    onSuccess: (response) => {
      toast.success(__('Doc Created'));
    },
  });
};

const generateSlug = () => {
  form.slug = slugify(form.title);
}

const fixSlug = () => {
  form.slug = slugify(form.slug);
}

const checkingSlugAvailability = ref(false);
const slugAvailable = ref(true);

/*watchDebounced(() => form.slug, (slug) => {
  if (slug === '') {
    return;
  }

  checkingSlugAvailability.value = true;

  axios.post(route('admin.docs.verify-slug'), {
    slug: slug
  }).then(response => {
    checkingSlugAvailability.value = false;
    slugAvailable.value = true;
  }).catch(responseErrors => {
    form.slug = form.slug + '-' + Math.floor(Math.random() * (1000 - 1 + 1)) + 1;
    slugAvailable.value = false;
    checkingSlugAvailability.value = false;
  });
}, {
  debounce: 600
});*/

const canPost = computed(() => {
  return form.slug !== '' && form.title !== '';
});

const userSelected = (user) => {
  form.author = user.id;
};

const expendDocumentation = () => {
  const elem = document.getElementById('documentation-content');

  if (!document.fullscreenElement) {
    elem.requestFullscreen().then(() => {
      elem.style.backgroundColor = "white"; // Set background to white
      elem.style.color = "black"; // Ensure text remains readable
    }).catch(err => {
      alert(`Error attempting to enable full-screen mode: ${err.message}`);
    });
  } else {
    document.exitFullscreen().then(() => {
      elem.style.backgroundColor = ""; // Reset background on exit
      elem.style.color = ""; // Reset text color
    });
  }
};

</script>

<template>
  <AdminLayout :description="__('Add a new documentation page')" :page-icon="false" :title="__('New Doc')">
    <template v-slot:actions>
      <PrimaryButton :class="{ 'opacity-75': form.processing || form.title === '' }"
                     :disabled="form.processing || form.title === ''"
                     :loading="form.processing"
                     @click="submit">
        {{ __('Create') }}
      </PrimaryButton>
    </template>

    <div class="mx-auto mt-8 grid max-w-3xl grid-cols-1 gap-6 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
      <div class="space-y-6 lg:col-span-2 lg:col-start-1">
        <Card :has-error="form.errors.length > 0">
          <template #header>
            {{ __('Title') }}
          </template>

          <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="sm:col-span-2 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="label">
                {{ __('Label') }}
                <span
                    class="text-red-600">*</span>
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <Input id="label" v-model="form.label" class="w-full" @input="generateSlug"/>
                <InputError :message="form.errors?.label" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="title">{{ __('Title') }}<span
                  class="text-red-600">*</span>
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <Input id="title" v-model="form.title" :placeholder="__('Doc Title')" class="w-full"
                       @input="generateSlug"/>
                <InputError :message="form.errors?.title" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="slug">
                {{ __('Slug') }}
                <span class="text-red-600">*</span>
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="slug" v-model="form.slug" class="w-full" @input="fixSlug"/>
                <InputError :message="form.errors?.slug" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-2 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="excerpt">
                {{ __('Excerpt') }}
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                                <TextArea id="excerpt" v-model="form.excerpt" class="w-full"
                                          rows="3"/>

                <InputError :message="form.errors?.excerpt" class="mt-2"/>
              </dd>
            </div>
          </dl>
        </Card>

        <Card id="documentation-content">
          <template #actions>
            <button
                v-tooltip="__('Fullscreen')"
                class="rounded-full size-7 bg-zinc-50 flex items-center justify-center text-gray-500 hover:bg-gray-100 ring-blue-600 focus:ring-blue-600">
              <Maximize class="size-4" @click="expendDocumentation"/>
            </button>
          </template>

          <template #header>
            {{ __('Content') }}
          </template>

          <div class="col-span-2 space-y-3">
            <dd class="max-h-[calc(100vh-5rem) min-h-96 overflow-y-auto w-full border border-gray-300 border-dashed rounded-md px-2 max-w-full text-[15px] prose-a:font-semibold leading-[23px] prose break-words text-zinc-900 dark:text-neutral-300 lg:prose-md dark:prose-invert prose-a:text-blue-500 prose-a:hover:text-blue-600 prose-a:no-underline prose-code:text-[13px]">
              <InputError :message="form.errors?.body" class="mb-2"/>
              <EditorJs v-model="form.body"/>
            </dd>
          </div>
        </Card>

      </div>

      <div>

        <Card :has-error="form.errors?.length > 0">
          <template #header>
            {{ __('Status') }}
          </template>

          <div class="flex flex-col space-y-6">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-grow flex-col">
                                      <SwitchDescription as="span"
                                                         class="text-sm text-gray-600 font-medium">
                                        {{ __('Published') }}
                                      </SwitchDescription>
                            </span>

              <Switch v-model="form.published"
                      :class="[form.published ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                                <span
                                                    :class="[form.published ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Published at') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="name" v-model="form.published_at" class="w-full"
                       type="date"/>
                <InputError :message="form.errors?.published_at" class="mt-2"/>
              </dd>
            </div>
          </div>
        </Card>

        <Card :has-error="form.errors?.length > 0" class="mt-3">
          <div class="flex flex-col space-y-6">
            <div>
              <label class="text-sm font-medium text-gray-600" for="author">
                {{ __('Author') }}
                <span class="text-red-600">*</span>
              </label>

              <div class="mt-2 flex flex-col space-y-2">
                <SelectUser :limit="20" :pre-selected-user="authUser" :roles="$page.props.config.admin_roles"
                            @userSelected="userSelected"/>
              </div>
            </div>

            <div>
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Category') }}
              </label>

              <div class="mt-2 flex flex-col space-y-2">
                <Multiselect
                    v-model="form.category"
                    :aria-label="__('Pick a category')"
                    :close-on-select="true"
                    :options="categories"
                    :placeholder="__('Pick a category')"
                    :searchable="true"
                    :tag-placeholder="__('Add a new category')"
                    :taggable="true"
                    label="name"
                    track-by="id"
                    @tag="addNewCategory"
                />

                <InputError :message="form.errors?.category" class="mt-2"/>
                <InputError :message="form.errors?.['category.id']" class="mt-2"/>
                <InputError :message="form.errors?.['category.name']" class="mt-2"/>
              </div>
            </div>
          </div>
        </Card>
      </div>
    </div>

  </AdminLayout>
</template>

<style>

</style>
