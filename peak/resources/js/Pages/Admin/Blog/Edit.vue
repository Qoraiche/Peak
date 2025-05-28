<script setup>
import Card from "@peak/Components/Admin/Card.vue";
import Input from "@peak/Components/Admin/Input.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import TextArea from "@peak/Components/Admin/TextArea.vue";
import {slugify} from '@peak/Composables/Utils.js';
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import SelectUser from "@peak/Layouts/Admin/Components/SelectUser.vue";
import {Switch, SwitchDescription, SwitchGroup} from "@headlessui/vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import {watchDebounced} from "@vueuse/core";
import {computed, defineProps, ref} from "vue";
import {useToast} from "vue-toastification";
import TinymceEditor from "@peak/Components/Admin/TinymceEditor.vue";
import {useConfirm} from "@peak/Composables/useConfirm.js";
import {__} from "@peak/Composables/useTranslate.js";

const props = defineProps({
  article: Object,
  topics: Array,
  categories: Array
});

const authUser = usePage().props.auth?.user;

const imagePreview = ref(props.article.locale_image);

const form = useForm({
  published: props.article.published,
  featured: props.article.featured,
  topics: props.article.locale_topics.map(topic => topic.name),
  category: props.article.categories[0] || null,
  title: props.article.title,
  author: authUser?.id,
  image: null,
  slug: props.article.slug,
  excerpt: props.article.excerpt,
  description: props.article.description,
  published_at: props.article.published_at,
  meta_description: props.article.meta_description,
  meta_keywords: props.article.meta_keywords,
  body: props.article.body,
  _method: 'put'
});
const handleFileUpdate = (files) => {
  form.image = files.length > 0 ? files[0].file : null;
  imagePreview.value = null;
}

const topics = ref(props.topics);
const categories = ref(props.categories);

const addNewTopic = (topic) => {
  form.topics.push(topic);
  topics.value.push(topic);
};
const addNewCategory = (categoryName) => {
  const newCategory = {id: `new-${Date.now()}`, name: categoryName};
  categories.value.push(newCategory);
  form.category = newCategory; // Store the object
};

const toast = useToast();

const submit = () => {
  form.post(route('admin.blog.articles.update', props.article.id), {
    onSuccess: () => {
      toast.success('Article Updated');
    },
  })
};

const deleteImage = async () => {
  const confirmed = await useConfirm({
    title: __("Delete image?"),
    text: __("Are you sure you want to delete this image? This cannot be undone."),
    confirmButtonText: __("Yes, delete it"),
  });

  if (confirmed) {
    router.delete(route('admin.blog.image.delete', props.article.id), {
      preserveScroll: true,
      onSuccess: () => {
        imagePreview.value = null;
      }
    });
  }
};

const generateSlug = () => {
  form.slug = slugify(form.title);
}

const fixSlug = () => {
  form.slug = slugify(form.slug);
}

const checkingSlugAvailability = ref(false);
const slugAvailable = ref(true);

watchDebounced(() => form.slug, (slug) => {
  if (slug === '') {
    return;
  }

  checkingSlugAvailability.value = true;

  axios.post(route('admin.blog.verify-slug'), {
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
});

const canPost = computed(() => {
  return form.slug !== '' && form.title !== '';
});

const userSelected = (user) => {
  form.author = user.id;
}
</script>

<template>
  <AdminLayout :description="__('Edit :resourcename #:id', {resourcename: 'Post', id: article.id})" :page-icon="false"
               :title="__('Edit blog post')">
    <template v-slot:actions>
      <PrimaryButton :class="{ 'opacity-75': form.processing || form.title === '' }"
                     :disabled="form.processing || form.title === ''"
                     :loading="form.processing"
                     @click="submit">
        {{ __('Save Changes') }}
      </PrimaryButton>
    </template>

    <div class="grid max-w-3xl grid-cols-1 gap-6 mx-auto mt-8 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
      <div class="space-y-6 lg:col-span-2 lg:col-start-1">
        <Card :has-error="form.errors?.length > 0">
          <template #header>
            {{ __('Content') }}
          </template>

          <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="space-y-3 sm:col-span-1">
              <label class="text-sm font-medium text-gray-600" for="title">{{ __('Title') }}<span
                  class="text-red-600">*</span>
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <Input id="title" v-model="form.title" class="w-full" @input="generateSlug"/>
                <InputError :message="form.errors?.title" class="mt-2"/>
              </dd>
            </div>

            <div class="space-y-3 sm:col-span-1">
              <label class="text-sm font-medium text-gray-600" for="slug">{{ __('Slug') }}<span
                  class="text-red-600">*</span>
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="slug" v-model="form.slug" class="w-full"
                       @input="fixSlug"/>
                <InputError :message="form.errors?.slug" class="mt-2"/>
              </dd>
            </div>

            <div class="space-y-3 sm:col-span-2">
              <label class="text-sm font-medium text-gray-600" for="excerpt">
                {{ __('Excerpt') }}
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                                <TextArea id="excerpt" v-model="form.excerpt" class="w-full"
                                          rows="3"/>
                <InputError :message="form.errors?.excerpt" class="mt-2"/>
              </dd>
            </div>

            <div class="col-span-2 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Content') }}
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <TinymceEditor v-model="form.body" :init="{
                                    height: 470,
                                    branding: false,
                                    toolbar_mode: 'sliding',
                                    plugins: [
                                        // Core editing features
                                        'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount'
                                    ],
                                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                                }"/>
              </dd>
            </div>
          </dl>
        </Card>

        <Card>
          <template #header>
            {{ __('SEO') }}
          </template>

          <dl class="grid grid-cols-2 gap-x-4 gap-y-8">
            <div class="space-y-3 sm:col-span-2">
              <label class="text-sm font-medium text-gray-600" for="meta_description">
                {{ __('Meta Description') }}
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <TextArea id="meta_description" v-model="form.meta_description" class="w-full" rows="3"/>
                <InputError :message="form.errors?.meta_description" class="mt-2"/>
              </dd>
            </div>

            <div class="space-y-3 sm:col-span-2">
              <label class="text-sm font-medium text-gray-600" for="meta_keywords">
                {{ __('Meta Keywords') }}
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                                <TextArea id="meta_keywords" v-model="form.meta_keywords" class="w-full"
                                          rows="3"/>
                <InputError :message="form.errors?.meta_keywords" class="mt-2"/>
              </dd>
            </div>
          </dl>

        </Card>
      </div>

      <div>

        <Card :has-error="form.errors?.length > 0">
          <template #header>
            {{ __('Status') }}
          </template>

          <div class="flex flex-col space-y-6">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchDescription as="span" class="text-sm font-medium text-gray-600">
                                  {{ __('Featured') }}
                                </SwitchDescription>
                            </span>

              <Switch v-model="form.featured"
                      :class="[form.featured ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.featured ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>

            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchDescription as="span" class="text-sm font-medium text-gray-600">
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

            <div class="space-y-3 sm:col-span-1">
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Published at') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="name" v-model="form.published_at" class="w-full" type="date"/>
                <InputError :message="form.errors?.published_at" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-600">
                {{ __('View') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900 break-words">
                <a :href="article.url" class="text-blue-600" target="_blank">{{ article.url }}</a>
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

              <SelectUser :limit="20" :pre-selected-user="authUser" :roles="$page.props.config.admin_roles" class="mt-2"
                          @userSelected="userSelected"/>
            </div>

            <div>
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Category') }}
              </label>

              <div class="mt-2">
                <Multiselect v-model="form.category" :close-on-select="true" :multiple="false" :options="categories"
                             :placeholder="__('Choose...')" :tag-placeholder="__('Add a new category')" :taggable="true"
                             label="name"
                             track-by="id" @tag="addNewCategory"/>
              </div>

              <InputError :message="form.errors?.category" class="mt-2"/>
              <InputError :message="form.errors?.['category.value']" class="mt-2"/>
              <InputError :message="form.errors?.['category.label']" class="mt-2"/>
            </div>

            <div>
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Topics') }}
              </label>

              <div class="mt-2">
                <Multiselect v-model="form.topics" :multiple="true" :options="topics" :placeholder="__('Choose...')"
                             :tag-placeholder="__('Add a new topic')" :taggable="true"
                             @tag="addNewTopic"/>
              </div>

              <InputError :message="form.errors?.topics" class="mt-2"/>
            </div>
          </div>
        </Card>

        <Card class="mt-3">
          <template #header>
            {{ __('Featured Image') }}
          </template>

          <div class="flex flex-col space-y-4">

            <div v-if="imagePreview"
                 class="flex items-center text-sm font-normal text-red-500 cursor-pointer hover:text-red-600 gap-x-1 group"
                 @click="deleteImage">
                            <span>
                                <svg aria-hidden="true" class="w-4 h-4 group-hover:text-red-500" data-slot="icon"
                                     fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                        stroke-linecap="round"
                                        stroke-linejoin="round">
                                    </path>
                                </svg>
                            </span>

              <span>{{ __('Delete image') }}</span>
            </div>
            <img v-if="imagePreview" :src="imagePreview" alt="main logo"
                 class="max-w-[150px] inline-block"/>

            <FilePond :allow-revert="false"
                      :file-validate-type-label-expected-types="__('Expects PNG or JPG')"
                      :instant-upload="false" :label-idle="__('file.filepond_select')" :server="null"
                      accepted-file-types="image/png, image/jpeg"
                      allow-multiple="false"
                      name="image"
                      @updatefiles="handleFileUpdate"/>
          </div>
        </Card>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped></style>
