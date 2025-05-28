<script setup>
import {inject, onMounted, ref, watch} from 'vue';
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue';
import {XMarkIcon} from '@heroicons/vue/24/outline';
import Input from "@peak/Components/Admin/Input.vue";
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const open = ref(false);
const type = ref('create');
const emitter = inject('emitter');
const permissions = ref([]);
const selectedPermissions = ref([]);
const editId = ref(null);

const form = useForm({
  roleName: null,
  permissions: [],
});

const errors = ref({});

onMounted(() => {
  emitter.on('role:manage', (event) => {

    if (event.type === 'edit') {
      form.permissions = event.selectedPermissions;
    } else {
      form.permissions = []
    }

    type.value = event.type;
    permissions.value = event.permissions;
    form.roleName = event.roleName;
    open.value = true;
    editId.value = event.id;
  });
});

const toast = useToast();

const submit = () => {

  const routeName = type.value === 'create' ? 'admin.user-management.roles.store' : 'admin.user-management.roles.update';
  const routeType = type.value === 'create' ? 'post' : 'put';
  const labelName = type.value === 'create' ? __('Role created') : __('Changes Saved');

  let params = {};

  if (type.value === 'edit' && editId.value) {
    params.role = editId.value;
  }

  form[routeType](route(routeName, params), {
    onSuccess: () => {
      open.value = false;
      toast.success(labelName);
    },
    onError: (err) => {
      toast.error(__('Something went wrong.'));
    }
  });
}

watch(open, (newVal) => {
  if (newVal) {
    form.clearErrors();
    errors.value = {};
  }
})

</script>

<template>
  <TransitionRoot :show="open" as="template">
    <Dialog as="div" class="relative z-40" @close="open = false">
      <div class="fixed inset-0"/>
      <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
          <div
              class="pointer-events-none fixed inset-y-0 ltr:right-0 rtl:left-0 flex max-w-full ltr:pl-10 ltr:sm:pl-16">
            <TransitionChild as="template"
                             enter="transform transition ease-in-out duration-500 sm:duration-700"
                             enter-from="ltr:translate-x-full rtl:-translate-x-full"
                             enter-to="ltr:translate-x-0 rtl:translate-x-0"
                             leave="transform transition ease-in-out duration-500 sm:duration-700"
                             leave-from="ltr:translate-x-0 rtl:translate-x-0"
                             leave-to="ltr:translate-x-full rtl:-translate-x-full">
              <DialogPanel class="pointer-events-auto w-screen max-w-md">
                <form class="flex h-full flex-col divide-y divide-gray-200 bg-white shadow-xl"
                      @submit.prevent="submit">
                  <div class="h-0 flex-1 overflow-y-auto">
                    <div class="bg-blue-600 px-4 py-6 sm:px-6">
                      <div class="flex items-center justify-between">
                        <DialogTitle
                            class="text-base font-semibold leading-6 text-white capitalize">
                          {{ __(':type Role', {type: type}) }}
                        </DialogTitle>
                        <div class="ml-3 flex h-7 items-center">
                          <button class="relative rounded-md bg-blue-700 text-blue-200 hover:text-white"
                                  type="button"
                                  @click="open = false">
                            <span class="absolute -inset-2.5"/>
                            <span class="sr-only">
                              {{ __('Close panel') }}
                            </span>
                            <XMarkIcon aria-hidden="true" class="h-6 w-6"/>
                          </button>
                        </div>
                      </div>

                      <div class="mt-1">
                        <p class="text-sm text-blue-300">
                          {{ __('Edit roles and their associated permissions.') }}
                        </p>
                      </div>
                    </div>
                    <div class="flex flex-1 flex-col justify-between">
                      <div class="divide-y divide-gray-200 px-4 sm:px-6">
                        <div class="space-y-6 pb-5 pt-6">
                          <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900"
                                   for="role-name">
                              {{ __('Name') }}
                            </label>
                            <div class="mt-2">
                              <Input id="role-name" v-model="form.roleName"
                                     class="block w-full"
                                     placeholder="Role name" required/>
                            </div>

                            <InputError :message="form.errors?.roleName" class="mt-2"/>
                          </div>
                          <fieldset>
                            <legend class="text-sm font-medium leading-6 text-gray-900">
                              {{ __('Permissions') }}
                            </legend>
                            <div class="mt-2 space-y-4">
                              <fieldset>
                                <legend class="sr-only">
                                  {{ __('Notifications') }}
                                </legend>
                                <div class="space-y-5">
                                  <div v-for="(permissionGroup, key) in permissions"
                                       :key="key"
                                       class="relative flex flex-col items-start">
                                    <p class="text-sm font-medium leading-6 capitalize text-gray-700 my-2">
                                      {{ __(key) }}
                                    </p>
                                    <div v-for="(permission, id) in permissionGroup"
                                         :key="permission.id"
                                         class="flex items-center">
                                      <div class="flex h-6 items-center">
                                        <input :id="'permission-' + id"
                                               v-model="form.permissions"
                                               :value="id"
                                               class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600"
                                               type="checkbox"/>
                                      </div>
                                      <div
                                          class="ltr:ml-3 rtl:mr-3 text-sm leading-6">
                                        <label :for="'permission-' + id"
                                               class="font-medium text-gray-900">
                                          {{ __(permission) }}
                                        </label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </fieldset>
                            </div>
                          </fieldset>

                          <InputError :message="form.errors?.permissions" class="mt-2"/>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="flex flex-shrink-0 justify-end px-4 py-4 gap-x-3">
                    <SecondaryButton @click="open = false">
                      {{ __('Cancel') }}
                    </SecondaryButton>
                    <PrimaryButton :class="{'opacity-25' : form.processing}" :disabled="form.processing"
                                   class="ltr:ml-2 rtl:mr-2">
                      {{ type === 'create' ? __('Create') : __('Save') }}
                    </PrimaryButton>
                  </div>
                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<style scoped>

</style>
