<script setup>

import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import DialogModal from "@peak/Components/Admin/DialogModal.vue";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import {inject, onMounted, ref} from "vue";
import TextInput from "@peak/Components/Admin/Input.vue";
import {useForm} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";
import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import TextArea from "@peak/Components/Admin/TextArea.vue";
import {__} from "@peak/Composables/useTranslate.js";

const addNewTestimonial = ref(false);
const emitter = inject('emitter');

const closeMe = () => {
  newTestimonialForm.reset();
  newTestimonialForm.clearErrors();
  addNewTestimonial.value = false;
};

const newTestimonialForm = useForm({
  name: '',
  company_name: '',
  rating: 5,
  comment: '',
});

const toast = useToast();

onMounted(() => {
  emitter.on('testimonial:add-new', function () {
    addNewTestimonial.value = true;
  });
});

const save = () => {
  newTestimonialForm.post(route('admin.settings.frontend.testimonials.create'), {
    onSuccess: () => {
      closeMe();
      toast.success(__('New Testimonial added'));
      // emitter.emit('pageAdded');
    },
    onError: (responseErrors) => {
      for (let key in responseErrors) {
        if (responseErrors.hasOwnProperty(key)) {
          toast.error(responseErrors[key])
        }
      }
    },
  })
};

</script>

<template>
  <DialogModal :show="addNewTestimonial" max-width="lg" @close="closeMe">
    <template #title>
      {{ __('Add New Testimonial') }}
    </template>

    <template #content>
      <div class="flex flex-col space-y-4">
        <div>
          <InputLabel :value="__('Name')" for="name"/>
          <TextInput id="name" v-model="newTestimonialForm.name" :placeholder="__('Name')"
                     class="mt-2 w-full"/>

          <InputError :message="newTestimonialForm.errors?.name" class="mt-1"/>
        </div>

        <div>
          <InputLabel :value="__('Rating')" for="rating"/>
          <TextInput id="rating" v-model="newTestimonialForm.rating" :placeholder="__('Rating')" class="mt-2 w-full"
                     max="5"
                     min="1"
                     type="number"/>

          <InputError :message="newTestimonialForm.errors?.rating" class="mt-1"/>
        </div>

        <div>
          <InputLabel :value="__('Company name')" for="company"/>
          <TextInput id="company" v-model="newTestimonialForm.company_name" :placeholder="__('Company Name')"
                     class="mt-2 block w-full"/>

          <InputError :message="newTestimonialForm.errors?.company_name" class="mt-1"/>
        </div>

        <div>
          <InputLabel :value="__('Comment')" for="comment"/>
          <TextArea id="comment" v-model="newTestimonialForm.comment" :placeholder="__('Comment')" class="mt-2"
                    rows="3">
          </TextArea>

          <InputError :message="newTestimonialForm.errors?.comment" class="mt-1"/>
        </div>
      </div>
    </template>

    <template #footer>
      <SecondaryButton @click="closeMe">
        {{ __('Cancel') }}
      </SecondaryButton>

      <PrimaryButton :class="{'opacity-25' : newTestimonialForm.processing}" :disabled="newTestimonialForm.processing"
                     class="ms-3"
                     @click="save">
        {{ __('Save Changes') }}
      </PrimaryButton>

    </template>
  </DialogModal>
</template>

