<script setup>

import Card from "@peak/Components/Admin/Card.vue";
import Input from "@peak/Components/Admin/Input.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import InputError from "@peak/Layouts/Admin/Components/InputError.vue";
import SelectUser from "@peak/Layouts/Admin/Components/SelectUser.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {computed, defineProps} from "vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const props = defineProps({});

const owner = usePage().props.auth?.user;

const form = useForm({
  owner: owner?.id,
  name: null,
});

const toast = useToast();

const submit = () => {
  form.post(route('admin.user-management.teams.store'), {
    onSuccess: () => {
      toast.success(__('Team created'));
    }
  });
};

const userSelected = (user) => {
  form.owner = user.id;
};
</script>

<template>
  <AdminLayout :description="__('New Team')" :page-icon="false" :title="__('New Team')">
    <template v-slot:actions>
      <PrimaryButton :class="{ 'opacity-75': form.processing || form.name === '' }"
                     :disabled="form.processing || form.name === ''"
                     :loading="form.processing"
                     @click="submit">
        {{ __('Create') }}
      </PrimaryButton>
    </template>

    <div class="grid max-w-3xl grid-cols-1 gap-6 mx-auto mt-8 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
      <div class="space-y-6 lg:col-span-2 lg:col-start-1">
        <Card :has-error="form.errors?.length > 0">
          <template #header>
            {{ __('Team Details') }}
          </template>

          <template #description>
            {{ __('Create a new team to collaborate with others on projects.') }}
          </template>

          <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="space-y-3 sm:col-span-2">
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Team Name') }}
                <span class="text-red-600">*</span>
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <Input id="name" v-model="form.name" class="w-full"/>
                <InputError :message="form.errors?.name" class="mt-2"/>
              </dd>
            </div>

            <div class="space-y-3 sm:col-span-2">
              <label class="text-sm font-medium text-gray-600" for="name">
                {{ __('Owner') }}
                <span class="text-red-600">*</span>
              </label>

              <dd class="mt-1 text-sm text-gray-900">
                <SelectUser :limit="20"
                            :pre-selected-user="owner"
                            :roles="$page.props.config.admin_roles"
                            @userSelected="userSelected"/>

                <InputError :message="form.errors?.owner" class="mt-2"/>
              </dd>
            </div>
          </dl>
        </Card>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped></style>
