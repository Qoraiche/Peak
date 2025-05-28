<script setup>
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import Card from "@peak/Components/Admin/Card.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import Input from "@peak/Components/Admin/Input.vue";
import {computed, defineProps, ref} from "vue";
import {Switch, SwitchDescription, SwitchGroup} from "@headlessui/vue";
import TextArea from "@peak/Components/Admin/TextArea.vue";
import {watchDebounced} from "@vueuse/core";
import {slugify} from '@peak/Composables/Utils.js';
import SelectUser from "@peak/Layouts/Admin/Components/SelectUser.vue";
import {useToast} from "vue-toastification";
import TinymceEditor from "@peak/Components/Admin/TinymceEditor.vue";

const props = defineProps({
  topics: Array,
  categories: Array
});

const authUser = usePage().props.auth?.user;

const form = useForm({
  published: true,
  featured: false,
  category: null,
  topics: [],
  title: '',
  author: authUser?.id,
  image: null,
  slug: '',
  description: '',
  excerpt: '',
  published_at: null,
  meta_description: '',
  meta_keywords: '',
  body: ''
});

const handleFileUpdate = (files) => {
  form.image = files.length > 0 ? files[0].file : null;
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
  form.post(route('admin.blog.articles.store'), {
    onSuccess: () => {
      toast.success('New article added');
    },
  })
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
  <AdminLayout :description="__('Write and publish a new blog post.')" :page-icon="false" :title="__('New Blog Post')">
    <template v-slot:actions>
      <PrimaryButton :class="{'opacity-75': form.processing || form.title === ''}"
                     :disabled="form.processing || form.title === ''"
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
            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="title">{{ __('Title') }}<span
                  class="text-red-600">*</span>
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <Input id="title" v-model="form.title" class="w-full"
                       @input="generateSlug"/>
                <InputError :message="form.errors?.title" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="slug">{{ __('Slug') }}<span
                  class="text-red-600">*</span>
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="slug" v-model="form.slug" class="w-full"
                       @input="fixSlug"/>
                <InputError :message="form.errors?.slug" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-2 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="excerpt">
                {{ __('Excerpt') }}
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <TextArea id="excerpt" v-model="form.excerpt" class="w-full" rows="3"/>
                <InputError :message="form.errors?.excerpt" class="mt-2"/>
              </dd>
            </div>

            <div class="col-span-2 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Content') }}
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <TinymceEditor
                    v-model="form.body"
                    :init="{
                                    height: 470,
                                      branding: false,
                                      toolbar_mode: 'sliding',
                                      plugins: [
                                        // Core editing features
                                        'anchor', 'code', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount'
                                      ],
                                      toolbar: 'code | undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                                    }"
                />
              </dd>
            </div>
          </dl>
        </Card>

        <Card>
          <template #header>
            {{ __('SEO') }}
          </template>

          <dl class="grid grid-cols-2 gap-x-4 gap-y-8">
            <div class="sm:col-span-2 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="meta_description">
                {{ __('Meta Description') }}
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <TextArea id="meta_description" v-model="form.meta_description" class="w-full" rows="3"/>
                <InputError :message="form.errors?.meta_description" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-2 space-y-3">
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
                            <span class="flex flex-grow flex-col">
                                      <SwitchDescription as="span"
                                                         class="text-sm text-gray-600 font-medium">
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
                <span
                    class="text-red-600">*</span>
              </label>

              <SelectUser :limit="20" :pre-selected-user="authUser" :roles="$page.props.config.admin_roles" class="mt-2"
                          @userSelected="userSelected"/>
            </div>

            <div>
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Category') }}
              </label>

              <div class="mt-2">
                <Multiselect
                    v-model="form.category"
                    :multiple="false"
                    :options="categories"
                    :placeholder="__('Choose...')"
                    :tag-placeholder="__('Add a new category')"
                    :taggable="true"
                    label="name"
                    track-by="id"
                    @tag="addNewCategory"/>
              </div>

              <InputError :message="form.errors?.category" class="mt-2"/>
              <InputError :message="form.errors?.['category.name']" class="mt-2"/>
              <InputError :message="form.errors?.['category.id']" class="mt-2"/>
            </div>

            <div>
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Topics') }}
              </label>

              <div class="mt-2">
                <Multiselect
                    v-model="form.topics"
                    :multiple="true"
                    :options="topics"
                    :placeholder="__('Choose...')"
                    :taggable="true"
                    tag-placeholder="Add a new topic"
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
            <FilePond
                :file-validate-type-label-expected-types="__('Expects PNG or JPG')"
                :label-idle="__('file.filepond_select')"
                accepted-file-types="image/png, image/jpeg"
                allow-multiple="false"
                name="image"
                @updatefiles="handleFileUpdate"
            />

            <InputError :message="form.errors?.image" class="mt-2"/>
          </div>
        </Card>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
