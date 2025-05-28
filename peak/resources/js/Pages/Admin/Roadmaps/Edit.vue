<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {useForm} from "@inertiajs/vue3";
import Card from "@peak/Components/Admin/Card.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import Input from "@peak/Components/Admin/Input.vue";
import {computed, defineProps, ref} from "vue";
import {Switch, SwitchDescription, SwitchGroup} from "@headlessui/vue";
import SelectUser from "@peak/Layouts/Admin/Components/SelectUser.vue";
import TinymceEditor from "@peak/Components/Admin/TinymceEditor.vue";
import {__} from "@peak/Composables/useTranslate.js";
import {useToast} from "vue-toastification";
import {slugify} from "@peak/Composables/Utils.js";

const props = defineProps({
  roadmap: Object,
  statuses: Object | null | Array,
  types: Object | null | Array,
});

const toast = useToast();

const form = useForm({
  status: props.roadmap.locale_status,
  type: props.roadmap.locale_type,
  published: props.roadmap.published,
  title: props.roadmap.title,
  slug: props.roadmap.slug,
  author: props.roadmap.user_id,
  published_at: props.roadmap.published_at,
  body: props.roadmap.body
});

const statusList = ref(props.statuses);
const typeList = ref(props.types);

const addNewStatus = (status) => {
  statusList.value.push(status);
  form.status.push(status);
};

const addNewType = (type) => {
  typeList.value.push(type);
  form.type.push(type);
};

const submit = () => {
  form.put(route('admin.roadmaps.update', props.roadmap.id), {
    onSuccess: () => {
      toast.success(__('Changes saved'));
    },
  });
};

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
  <AdminLayout :description="__('Edit :resourcename #:id', {resourcename: 'Roadmap', id: roadmap.id})"
               :page-icon="false"
               :title="__('Edit Roadmap')">
    <template v-slot:actions>
      <PrimaryButton :class="{'opacity-75': form.processing || form.title === ''}"
                     :disabled="form.processing || form.title === ''"
                     :loading="form.processing"
                     @click="submit">
        {{ __('Save Changes') }}
      </PrimaryButton>
    </template>

    <div class="mx-auto mt-8 grid max-w-3xl grid-cols-1 gap-6 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
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
                <Input id="title" v-model="form.title" :placeholder="__('Title')" class="w-full"/>
                <InputError :message="form.errors?.title" class="mt-2"/>
              </dd>
            </div>

            <div class="space-y-3 sm:col-span-1">
              <label class="text-sm font-medium text-gray-600" for="slug">{{ __('Slug') }}<span
                  class="text-red-600">*</span>
              </label>
              <dd class="mt-1 text-sm text-gray-900">
                <Input id="slug" v-model="form.slug" :placeholder="__('Slug URL')" class="w-full" @input="fixSlug"/>
                <InputError :message="form.errors?.slug" class="mt-2"/>
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
                                        'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount'
                                      ],
                                      toolbar: 'code | undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                                    }"
                />
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

        <Card :has-error="form.errors?.length > 0" class="mt-4">
          <div class="flex flex-col gap-y-6">
            <div>
              <label class="text-sm font-medium text-gray-600" for="author">
                {{ __('Author') }}
                <span class="text-red-600">*</span>
              </label>

              <SelectUser :limit="20" :pre-selected-user="roadmap.user" :roles="$page.props.config.admin_roles"
                          @userSelected="userSelected"/>

              <InputError :message="form.errors?.author" class="mt-2"/>
            </div>

            <div>
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Status') }}
              </label>

              <div class="mt-2">
                <Multiselect v-model="form.status" :allow-empty="false" :options="statusList"
                             :taggable="true" :tag-placeholder="__('Add a new status')" @tag="addNewStatus"/>
              </div>

              <InputError :message="form.errors?.status" class="mt-2"/>
            </div>

            <div>
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Type') }}
              </label>

              <div class="mt-2">
                <Multiselect v-model="form.type" :allow-empty="false" :options="typeList"
                             :tag-placeholder="__('Add a new status')" :taggable="true" @tag="addNewType"/>
              </div>

              <InputError :message="form.errors?.type" class="mt-2"/>
            </div>
          </div>
        </Card>

      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
