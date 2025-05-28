<script setup>
import InputError from "@peak/Components/Admin/InputError.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import Codemirror from "codemirror-editor-vue3";
import "codemirror/mode/css/css.js";
import "codemirror/mode/javascript/javascript.js";
import {inject, ref} from "vue";
import {useToast} from "vue-toastification";
import Card from "@peak/Components/Admin/Card.vue";
import {__} from "@peak/Composables/useTranslate.js";

const page = usePage();

const props = defineProps({
  errors: Object,
  custom_css: String | null,
  custom_js: String | null,
});

const toast = useToast();
const emitter = inject('emitter');

const updateCustomCssCodeForm = useForm({
  custom_css: props.custom_css,
});

const updateCustomJsCodeForm = useForm({
  custom_js: props.custom_js,
});

const saveCss = () => {
  updateCustomCssCodeForm.put(route('admin.settings.frontend.custom-code.css.update'), {
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    }
  });
}

const saveJs = () => {

  updateCustomJsCodeForm.put(route('admin.settings.frontend.custom-code.js.update'), {
    onSuccess: () => {
      toast.success(__('Changes Saved'));
    }
  });
}

const cssCodeRef = ref();
const jsCodeRef = ref();

</script>

<template>
  <AdminLayout :description="__('Inject your own custom CSS or JavaScript.')" :title="__('Custom CSS/JS Code')">
    <div class="flex flex-col space-y-6">

      <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
        <template #header>
          {{ __('Custom CSS Code') }}
        </template>

        <template #actions>
          <PrimaryButton
              :class="{ 'opacity-25': !updateCustomCssCodeForm.isDirty || updateCustomCssCodeForm.processing }"
              :disabled="!updateCustomCssCodeForm.isDirty || updateCustomCssCodeForm.processing"
              class="self-end"
              @click="saveCss">
            {{ __('Save Changes') }}
          </PrimaryButton>
        </template>

        <div class="gap-5 py-5 md:grid-cols-2">
          <div class="col-span-2">
            <div class="flex flex-col space-y-2">
              <InputError :message="errors?.custom_css" class="my-1"/>

              <Codemirror ref="cssCodeRef" v-model:value="updateCustomCssCodeForm.custom_css"
                          :options='{ mode: "text/css" }' border
                          height="400" width="100%">
              </Codemirror>
            </div>
          </div>
        </div>
      </Card>

      <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
        <template #header>
          {{ __('Custom JS Code') }}
        </template>

        <template #actions>
          <PrimaryButton
              :class="{ 'opacity-25': !updateCustomJsCodeForm.isDirty || updateCustomJsCodeForm.processing }"
              :disabled="!updateCustomJsCodeForm.isDirty || updateCustomJsCodeForm.processing"
              class="self-end"
              @click="saveJs">
            {{ __('Save Changes') }}
          </PrimaryButton>
        </template>

        <div class="gap-5 py-5 md:grid-cols-2">
          <div class="col-span-2">
            <div class="flex flex-col space-y-2">
              <InputError :message="errors?.custom_js" class="my-1"/>
              <Codemirror ref="jsCodeRef"
                          v-model:value="updateCustomJsCodeForm.custom_js" :options='{ mode: "text/javascript" }'
                          border height="400"
                          width="100%">
              </Codemirror>
            </div>
          </div>
        </div>
      </Card>
    </div>
  </AdminLayout>
</template>

<style scoped></style>
