<script setup>

import EditEmail from "@peak/Modals/Admin/Settings/Marketing/EditEmail.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import {useForm} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');

const props = defineProps({
  emails: Object
});

const form = useForm({
  send_welcome_email: props.send_welcome_email,
  welcome_email_content: props.welcome_email_content,
});

const toast = useToast();

const save = () => {
  form.put(route('admin.settings.marketing.transactional-emails.update'), {
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    }
  });
}

const editTransactionalEmail = (email) => {
  emitter.emit('editEmail', email);
};

</script>

<template>
  <AdminLayout description="Manage essential settings for your application." title="Transactional Emails">
    <EditEmail/>
    <div class="max-w-2xl p-2 mx-auto">
      <div class="flex flex-col p-1">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                    <span class="text-base font-semibold text-gray-600">
                        Edit Settings
                    </span>

          <PrimaryButton :class="{ 'opacity-25': !form.isDirty || form.processing }"
                         :disabled="!form.isDirty || form.processing" :loading="form.processing"
                         class="self-end"
                         @click="save">
            Save Changes
          </PrimaryButton>
        </div>

        <div v-for="email in emails" :key="email.id"
             class="flex items-center justify-between p-4 mt-8 border border-gray-200 hover:bg-gray-50">
          <div class="font-normal">
            {{ email.subject }}
          </div>

          <div class="flex gap-x-4">
            <!--                        <SwitchGroup as="div"
                                                 class="flex items-center justify-between">
                                        <Switch v-model="form.send_welcome_email"
                                                :class="[form.send_welcome_email ? 'bg-blue-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2']">
                                                            <span aria-hidden="true"
                                                                  :class="[form.send_welcome_email ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"/>
                                        </Switch>
                                    </SwitchGroup>-->
            <div>
              <SecondaryButton class="!rounded-full !p-1 text-white shadow-sm" type="button"
                               @click="editTransactionalEmail(email)">
                <svg aria-hidden="true" class="size-5" data-slot="icon" fill="none"
                     stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                  <path
                      d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                      stroke-linecap="round"
                      stroke-linejoin="round">
                  </path>
                </svg>
              </SecondaryButton>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped></style>
