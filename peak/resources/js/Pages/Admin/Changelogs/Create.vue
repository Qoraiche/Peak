<script setup>
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {useForm} from "@inertiajs/vue3";
import Card from "@peak/Components/Admin/Card.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import Input from "@peak/Components/Admin/Input.vue";
import {defineProps} from "vue";
import {Switch, SwitchDescription, SwitchGroup} from "@headlessui/vue";
import SelectUser from "@peak/Layouts/Admin/Components/SelectUser.vue";
import {useToast} from "vue-toastification";
import {useAuth} from "@peak/Composables/useAuth.js";
import TinymceEditor from "@peak/Components/Admin/TinymceEditor.vue";
import {__} from "@peak/Composables/useTranslate.js";

const props = defineProps({
  admins: Object
});

const {user} = useAuth();

const toast = useToast();

const form = useForm({
  published: false,
  title: '',
  author: user.value.id,
  published_at: null,
  body: ''
});
const submit = () => {
  form.post(route('admin.changelogs.store'), {
    onSuccess: (response) => {
      toast.success(__('Changelog created'));
    },
  });
};

const userSelected = (user) => {
  form.author = user.id;
}
</script>

<template>
  <AdminLayout :description="__('Add a new changelog entry')" :page-icon="false" :title="__('New Changelog')">
    <template v-slot:actions>
      <div class="flex gap-x-2">
        <PrimaryButton :class="{'opacity-75': form.processing || form.title === ''}"
                       :disabled="form.processing || form.title === ''"
                       :loading="form.processing"
                       @click="submit">
          {{ __('Create') }}
        </PrimaryButton>
      </div>
    </template>

    <div class="mx-auto mt-8 grid max-w-3xl grid-cols-1 gap-6 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
      <div class="space-y-6 lg:col-span-2 lg:col-start-1">
        <Card :has-error="form.errors?.length > 0">
          <template #header>
            {{ __('Content') }}
          </template>

          <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="sm:col-span-2 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="title">{{ __('Title') }}<span
                  class="text-red-600">*</span>
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <Input id="title" v-model="form.title"
                       class="w-full"/>
                <InputError :message="form.errors?.title" class="mt-2"/>
              </dd>
            </div>

            <div class="col-span-2 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Content') }}
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <TinymceEditor v-model="form.body"/>

                <InputError :message="form.errors?.body" class="mt-2"/>
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

            <InputError :message="form.errors?.published" class="mt-2"/>

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
              <label class="text-sm font-medium text-gray-600" for="author">{{ __('Author') }}<span
                  class="text-red-600">*</span>
              </label>

              <SelectUser :limit="20" :pre-selected-user="user" :roles="$page.props.config.admin_roles"
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
