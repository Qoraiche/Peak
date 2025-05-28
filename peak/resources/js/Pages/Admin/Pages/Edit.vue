<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import Card from "@peak/Components/Admin/Card.vue";
import InputError from "@peak/Layouts/Admin/Components/InputError.vue";
import Input from "@peak/Components/Admin/Input.vue";
import {computed, defineProps} from "vue";
import TextArea from "@peak/Components/Admin/TextArea.vue";
import {slugify} from '@peak/Composables/Utils.js';
import SelectUser from "@peak/Layouts/Admin/Components/SelectUser.vue";
import {useToast} from "vue-toastification";
import TinymceEditor from "@peak/Components/Admin/TinymceEditor.vue";
import {__} from "@peak/Composables/useTranslate.js";
import {Switch, SwitchDescription, SwitchGroup} from "@headlessui/vue";

const props = defineProps({
  page: Object
});

const author = props.page.user ?? usePage().props.auth?.user;

const form = useForm({
  published: props.page.published,
  title: props.page.title,
  author: author?.id,
  slug: props.page.slug,
  description: props.page.description,
  published_at: props.page.published_at,
  meta_description: props.page.meta_description,
  meta_keywords: props.page.meta_keywords,
  body: props.page.body
});

const toast = useToast();

const submit = () => {
  form.put(route('admin.pages.update', props.page.id), {
    onSuccess: () => {
      toast.success(__('Page saved'));
    },
  })
};

const generateSlug = () => {
  form.slug = slugify(form.title);
}

const fixSlug = () => {
  form.slug = slugify(form.slug);
}

const canPost = computed(() => {
  return form.slug !== '' && form.title !== '';
});

const userSelected = (user) => {
  form.author = user.id;
}
</script>

<template>
  <AdminLayout :description="__('Edit :resourcename #:id', {resourcename: 'Page', id: page.id})" :page-icon="false"
               :title="__('Edit Page')">
    <template v-slot:actions>
      <PrimaryButton :class="{'opacity-75': form.processing || form.title === ''}"
                     :disabled="form.processing || form.title === ''"
                     :loading="form.processing"
                     @click="submit">
        {{ __('Save') }}
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
              <label class="text-sm font-medium text-gray-600" for="title">
                {{ __('Title') }}
                <span
                    class="text-red-600">*</span>
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <Input id="title" v-model="form.title" class="w-full"
                       @input="generateSlug"/>
                <InputError :message="form.errors?.title" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="slug">
                {{ __('Slug') }}
                <span
                    class="text-red-600">*</span>
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="slug" v-model="form.slug" :placeholder="__('A user-friendly, SEO-optimized URL')"
                       class="w-full" @input="fixSlug"/>
                <InputError :message="form.errors?.slug" class="mt-2"/>
              </dd>
            </div>

            <div class="col-span-2 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="content">
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
                                        'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount'
                                      ],
                                      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
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

          <dl class="flex flex-col gap-x-4 gap-y-8">
            <div class="space-y-3">
              <label class="text-sm font-medium text-gray-600" for="meta_description">
                {{ __('Meta Description') }}
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                                <TextArea id="meta_description" v-model="form.meta_description" class="!w-full"
                                          placeholder="Summarizing the blog post"
                                          rows="3"/>
                <InputError :message="form.errors?.meta_description" class="mt-2"/>
              </dd>
            </div>

            <div class="space-y-3">
              <label class="text-sm font-medium text-gray-600" for="meta_keywords">
                {{ __('Meta Keywords') }}
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                                <TextArea id="meta_keywords" v-model="form.meta_keywords"
                                          :placeholder="__('SEO, Blog Writing, Content Marketing')"
                                          class="!w-full"
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
              <label class="text-sm font-medium text-gray-600" for="published_at">
                {{ __('Published at') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="published_at" v-model="form.published_at" class="w-full"
                       type="date"/>
                <InputError :message="form.errors?.published_at" class="mt-2"/>
              </dd>
            </div>

            <div class="sm:col-span-1 space-y-3">
              <label class="text-sm font-medium text-gray-600">
                {{ __('View') }}
              </label>
              <dd class="mt-1 text-sm text-gray-900 break-words">
                <a :href="page.url" class="text-blue-600" target="_blank">{{ page.url }}</a>
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

              <!-- @todo: fetch only admin users -->
              <SelectUser :limit="20" :pre-selected-user="author" :roles="$page.props.config.admin_roles"
                          @userSelected="userSelected"/>
            </div>
          </div>
        </Card>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
