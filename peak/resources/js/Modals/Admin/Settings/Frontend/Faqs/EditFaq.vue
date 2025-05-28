<script setup>

import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import DialogModal from "@peak/Components/Admin/DialogModal.vue";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import {inject, onMounted, ref} from "vue";
import TextInput from "@peak/Components/Admin/Input.vue";
import {useForm} from "@inertiajs/vue3";
import {useToast} from "vue-toastification";
import TinymceEditor from "@peak/Components/Admin/TinymceEditor.vue";
import {__} from "@peak/Composables/useTranslate.js";

const editFaq = ref(false);
const emitter = inject('emitter');

const faq = ref(null);

const editFaqForm = useForm({
  question: '',
  answer: '',
});

const closeMe = () => {
  editFaqForm.reset();
  editFaqForm.clearErrors();
  editFaq.value = false;
};

const toast = useToast();

onMounted(() => {
  emitter.on('faq:edit', function (faqContent) {
    editFaq.value = true;
    faq.value = faqContent;
    editFaqForm.question = faqContent.question;
    editFaqForm.answer = faqContent.answer;
  });
});

const save = () => {
  editFaqForm.put(route('admin.settings.frontend.faqs.update', {
    faq: faq.value.id
  }), {
    onSuccess: () => {
      closeMe();
      toast.success(__('FAQ item saved'));
      emitter.emit('pageAdded');
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
  <DialogModal :show="editFaq" max-width="2xl" @close="closeMe">
    <template #title>
      {{ __('Edit FAQ') }}
    </template>

    <template #content>
      <div class="flex flex-col space-y-3">
        <TextInput v-model="editFaqForm.question" :placeholder="__('Question')"
                   class="w-full py-3"/>

        <TinymceEditor
            v-model="editFaqForm.answer"
            :init="{
                                    height: 280,
                                      branding: false,
                                      toolbar_mode: 'sliding',
                                      plugins: [
                                        // Core editing features
                                        'anchor', 'code', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount'
                                      ],
                                      toolbar: 'code | undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                                    }"/>

      </div>
    </template>

    <template #footer>
      <SecondaryButton @click="closeMe">
        {{ __('Cancel') }}
      </SecondaryButton>

      <PrimaryButton :class="{'opacity-25' : editFaqForm.processing}" :disabled="editFaqForm.processing"
                     class="ms-3"
                     @click="save">
        {{ __('Save Changes') }}
      </PrimaryButton>

    </template>
  </DialogModal>
</template>

