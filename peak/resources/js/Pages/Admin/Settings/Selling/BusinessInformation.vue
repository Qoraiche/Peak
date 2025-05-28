<script setup>

import TextInput from "@peak/Components/Admin/Input.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');
const toast = useToast();

const props = defineProps({
  company_name: String | null,
  street: String | null,
  location: String | null,
  phone: String | null,
  email: String | null
});

const form = useForm({
  company_name: props.company_name,
  street: props.street,
  location: props.location,
  phone: props.phone,
  email: props.email
});

const save = () => {
  form.put(route('admin.settings.selling.business-information.update'), {
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    },
    onError: () => {
      toast.error(__('Something went wrong.'));
    },
  });
};

</script>

<template>
  <AdminLayout description="Update business name, address, and legal info" title="Business Information">
    <div class="flex flex-col space-y-1">
      <div id="business-information" class="p-3 rounded-md">
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
          <div class="py-3">
            <div class="grid grid-cols-1 gap-5 py-5 md:grid-cols-2">
              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel for="company_name" value="Company name"/>
                  <TextInput id="company_name" v-model="form.company_name" class="block w-full"
                             placeholder="Your Company"/>
                  <InputError :message="form.errors?.company_name" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <div class="flex items-center gap-x-2">
                    <InputLabel for="street" value="Street"/>
                  </div>
                  <TextInput id="street" v-model="form.street" class="block w-full"
                             placeholder="Main Str. 1"/>
                  <InputError :message="form.errors?.street" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel for="location" value="Location"/>
                  <TextInput id="location" v-model="form.location" class="block w-full"
                             placeholder="2000 Antwerp, Belguim"/>
                  <InputError :message="form.errors?.location" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel for="phone" value="Phone"/>
                  <TextInput id="phone" v-model="form.phone" class="block w-full"
                             placeholder="+32 499 00 00 00"/>
                  <InputError :message="form.errors?.phone" class="mt-1"/>
                </div>
              </div>

              <div>
                <div class="flex flex-col space-y-2">
                  <InputLabel for="email" value="Email"/>
                  <TextInput id="email" v-model="form.email" class="block w-full"
                             placeholder="info@example.com"/>
                  <InputError :message="form.errors?.email" class="mt-1"/>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <template #footerActions>
      <!--                        <PrimaryButton>
                                  Save
                              </PrimaryButton>-->
    </template>
  </AdminLayout>
</template>

<style scoped></style>
