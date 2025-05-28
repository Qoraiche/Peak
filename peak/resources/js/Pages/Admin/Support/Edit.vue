<script setup>
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import {useForm} from "@inertiajs/vue3";
import Card from "@peak/Components/Admin/Card.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import Input from "@peak/Components/Admin/Input.vue";
import {computed, defineProps} from "vue";
import SelectUser from "@peak/Layouts/Admin/Components/SelectUser.vue";
import SelectInput from "@peak/Components/Admin/SelectInput.vue";
import TinymceEditor from "@peak/Components/Admin/TinymceEditor.vue";
import {__} from '@peak/Composables/useTranslate.js';
import {useToast} from "vue-toastification";

const props = defineProps({
  ticket: Object
});

const toast = useToast();

const form = useForm({
  subject: props.ticket.subject,
  description: props.ticket.description,
  author: props.ticket.user_id,
  priority: props.ticket.priority,
  status: props.ticket.status
});

const submit = () => {
  form.put(route('admin.support.tickets.update', props.ticket.uuid), {
    onSuccess: () => {
      toast.success(__('Ticket saved'));
    },
  });
};

const canPost = computed(() => {
  return form.slug !== '' && form.title !== '';
});

const userSelected = (user) => {
  form.author = user.id;
}

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

</script>

<template>
  <AdminLayout :description="__('Edit :resourcename #:id', {resourcename: 'Ticket', id: ticket.id})"
               :page-icon="false"
               :title="__('Edit Ticket')">
    <template v-slot:actions>
      <PrimaryButton :class="{'opacity-75': form.processing || form.subject === ''}"
                     :disabled="form.processing || form.subject === ''"
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
            <div class="sm:col-span-2 space-y-3">
              <label class="text-sm font-medium text-gray-600" for="subject">
                {{ __('Subject') }}
                <span
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
                <TinymceEditor v-model="form.description" :init="{
                                    height: 470,
                                      branding: false,
                                      toolbar_mode: 'sliding',
                                      plugins: [
                                        // Core editing features
                                        'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount'
                                      ],
                                      toolbar: 'code | undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                                    }"/>
              </dd>
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

              <SelectUser :limit="20" :pre-selected-user="ticket.user" :roles="$page.props.config.admin_roles"
                          @userSelected="userSelected"/>
            </div>

            <div>
              <label class="text-sm font-medium text-gray-600" for="priority">
                {{ __('Priority') }}
                <span class="text-red-600">*</span>
              </label>

              <SelectInput v-model="form.priority" :values="priorities"/>
            </div>

            <div>
              <label class="text-sm font-medium text-gray-600" for="priority">
                {{ __('Status') }}
                <span class="text-red-600">*</span>
              </label>

              <SelectInput v-model="form.status" :values="statuses"/>
            </div>
          </div>
        </Card>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
