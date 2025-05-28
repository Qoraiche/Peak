<script setup>

import DialogModal from "@/Themes/Breeze/Components/DialogModal.vue";
import InputError from "@/Themes/Breeze/Components/InputError.vue";
import PrimaryButton from "@/Themes/Breeze/Components/PrimaryButton.vue";
import SelectInput from "@/Themes/Breeze/Components/SelectInput.vue";
import TextInput from "@/Themes/Breeze/Components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";
import {inject, onMounted, ref} from "vue";
import TinymceEditor from "@/Themes/Breeze/Components/TinymceEditor.vue";

const route = inject('route');

const open = ref(false);
const types = ref({});
const emitter = inject('emitter');

const form = useForm({
  type: null,
  title: null,
  body: null,
});

onMounted(() => {
  emitter.on('roadmap:new-feature-request', function (eventTypes) {
    open.value = true;

    types.value = eventTypes?.map(eventType => {
      return {key: eventType.id, value: eventType.name};
    });

    form.type = types.value[0]?.key ?? null;
  });
});


const close = () => {
  open.value = false;
  form.reset('type', 'body');
};

const submit = () => {
  form.post(route('roadmap.store'), {
    onSuccess: () => {
      close();
    }
  });
}

</script>

<template>
  <DialogModal :show="open" @close="close">
    <template #title>
      {{ __('Suggest a Feature') }}
    </template>

    <template #content>
      <div class="flex flex-col space-y-3">

        <div class="flex flex-col space-y-2">
          <SelectInput v-model="form.type" :values="types"/>
          <InputError :message="form.errors?.type" class="mt-1"/>
        </div>

        <div class="flex flex-col space-y-2">
          <TextInput v-model="form.title" class="w-full" placeholder="What do you want to suggest?"/>
          <InputError :message="form.errors?.title" class="mt-1"/>
        </div>

        <div class="flex flex-col space-y-2">
          <TinymceEditor v-model="form.body" :init="{
                        height: 350,
                        branding: false,
                        toolbar_mode: 'sliding',
                        plugins: [
                            // Core editing features
                            'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount'
                        ],
                        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                    }"/>
          <InputError :message="form.errors?.body" class="mt-1"/>
        </div>
      </div>
    </template>

    <template #footer>
      <PrimaryButton :class="{'opacity-75': form.processing || !form.isDirty}"
                     :disabled="form.processing || !form.isDirty" @click="submit">
        {{ __('Suggest') }}
      </PrimaryButton>
    </template>

  </DialogModal>
</template>

<style scoped></style>
