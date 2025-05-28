<script setup>

import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {useForm, usePage} from "@inertiajs/vue3";
import {inject, ref} from "vue";
import {useToast} from "vue-toastification";
import Card from "@peak/Components/Admin/Card.vue";
import {ColorPicker} from "vue3-colorpicker";
import {__} from "@peak/Composables/useTranslate.js";

const page = usePage();

const color = ref("#b2dfdc");

const props = defineProps({
  errors: Object,
  text_color: String | null,
  cta_button_text_color: String | null,
  cta_button_background_color: String | null,
  background_color: String | null
});

const toast = useToast();
const emitter = inject('emitter');

const updateAppearanceForm = useForm({
  text_color: props.text_color,
  cta_button_text_color: props.cta_button_text_color,
  cta_button_background_color: props.cta_button_background_color,
  background_color: props.background_color
});

const onColorChange = (newColor) => {
  color.value = newColor;
  console.log("Updated Color:", newColor); // Debugging
};

const save = () => {
  updateAppearanceForm.put(route('admin.settings.frontend.appearance.update'), {
    preserveScroll: true,
    onSuccess: () => {
      toast.success(__('Changes Saved'));
      updateAppearanceForm.reset('image_path');
    },

    onError: (responseErrors) => {
      for (let key in responseErrors) {
        if (responseErrors.hasOwnProperty(key)) {
          toast.error(responseErrors[key])
        }
      }

      updateAppearanceForm.reset('image_path');
    }
  });
}
</script>

<template>
  <AdminLayout description="Customize the frontend theme layout and styling" title="Appearance">
    <template #actions>
      <PrimaryButton :class="{ 'opacity-25': !updateAppearanceForm.isDirty || updateAppearanceForm.processing }"
                     :disabled="!updateAppearanceForm.isDirty || updateAppearanceForm.processing"
                     :loading="updateAppearanceForm.processing"
                     class="self-end"
                     @click="save">
        {{ __('Save Changes') }}
      </PrimaryButton>
    </template>

    <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
      <template #header>
        {{ __('Appearance') }}
      </template>

      <div id="general" class="rounded-md">
        <div class="flex flex-col">
          <div>
            <div class="flex flex-col p-3 space-y-5">
              <div class="grid grid-cols-2 gap-6">
                <div class="flex flex-col space-y-4">
                  <InputLabel :value="__('Text Color')" for="text_color"/>
                  <ColorPicker v-model:pureColor="updateAppearanceForm.text_color" disable-history format="hex"/>
                </div>

                <div class="flex flex-col space-y-4">
                  <InputLabel :value="__('CTA Button text color')" for="cta_button_text_color"/>
                  <ColorPicker v-model:pureColor="updateAppearanceForm.cta_button_text_color" disable-history
                               format="hex"/>
                </div>

                <div class="flex flex-col space-y-4">
                  <InputLabel :value="__('CTA Button background color')" disable-history
                              for="cta_button_background_color"
                              format="hex"/>
                  <ColorPicker v-model:pureColor="updateAppearanceForm.cta_button_background_color" disable-history
                               format="hex"/>
                </div>

              </div>
              <div class="flex flex-col space-y-4">
                <InputLabel :value="__('Hero & Page Header Background Color')" for="hero_bg_color"/>

                <div class="grid grid-cols-3 gap-5">
                  <div
                      :class="{ 'outline outline-black': updateAppearanceForm.background_color === 'bg-gradient-to-r from-pink-300 via-purple-300 to-blue-400' }"
                      class="flex items-center justify-center p-3 text-sm font-medium text-center rounded-lg cursor-pointer select-none hover:outline hover:outline-black bg-gradient-to-r from-pink-300 via-purple-300 to-blue-400"
                      @click="updateAppearanceForm.background_color = 'bg-gradient-to-r from-pink-300 via-purple-300 to-blue-400'">
                    {{ __('Text') }}
                  </div>

                  <div
                      :class="{ 'outline outline-black': updateAppearanceForm.background_color === 'bg-gradient-to-r from-pink-500 via-red-500 to-yellow-500' }"
                      class="flex items-center justify-center p-3 text-sm font-medium text-center rounded-lg cursor-pointer select-none hover:outline hover:outline-black bg-gradient-to-r from-pink-500 via-red-500 to-yellow-500"
                      @click="updateAppearanceForm.background_color = 'bg-gradient-to-r from-pink-500 via-red-500 to-yellow-500'">
                    {{ __('Text') }}
                  </div>

                  <div
                      :class="{ 'outline outline-black': updateAppearanceForm.background_color === 'bg-gradient-to-r from-green-300 via-blue-500 to-purple-600' }"
                      class="flex items-center justify-center p-3 text-sm font-medium text-center rounded-lg cursor-pointer select-none hover:outline hover:outline-black bg-gradient-to-r from-green-300 via-blue-500 to-purple-600"
                      @click="updateAppearanceForm.background_color = 'bg-gradient-to-r from-green-300 via-blue-500 to-purple-600'">
                    {{ __('Text') }}
                  </div>

                  <div
                      :class="{ 'outline outline-black': updateAppearanceForm.background_color === 'bg-gradient-to-r from-yellow-200 via-green-200 to-green-500' }"
                      class="flex items-center justify-center p-3 text-sm font-medium text-center rounded-lg cursor-pointer select-none hover:outline hover:outline-black bg-gradient-to-r from-yellow-200 via-green-200 to-green-500"
                      @click="updateAppearanceForm.background_color = 'bg-gradient-to-r from-yellow-200 via-green-200 to-green-500'">
                    {{ __('Text') }}
                  </div>

                  <div
                      :class="{ 'outline outline-black': updateAppearanceForm.background_color === 'bg-gradient-to-r from-red-200 via-red-300 to-yellow-200' }"
                      class="flex items-center justify-center p-3 text-sm font-medium text-center rounded-lg cursor-pointer select-none hover:outline hover:outline-black bg-gradient-to-r from-red-200 via-red-300 to-yellow-200"
                      @click="updateAppearanceForm.background_color = 'bg-gradient-to-r from-red-200 via-red-300 to-yellow-200'">
                    {{ __('Text') }}
                  </div>

                  <div
                      :class="{ 'outline outline-black': updateAppearanceForm.background_color === 'bg-gradient-to-r from-purple-200 via-purple-400 to-purple-800' }"
                      class="flex items-center justify-center p-3 text-sm font-medium text-center rounded-lg cursor-pointer select-none hover:outline hover:outline-black bg-gradient-to-r from-purple-200 via-purple-400 to-purple-800"
                      @click="updateAppearanceForm.background_color = 'bg-gradient-to-r from-purple-200 via-purple-400 to-purple-800'">
                    {{ __('Text') }}
                  </div>

                  <div
                      :class="{ 'outline outline-black': updateAppearanceForm.background_color === 'bg-gradient-to-r from-blue-100 via-blue-300 to-blue-500' }"
                      class="flex items-center justify-center p-3 text-sm font-medium text-center rounded-lg cursor-pointer select-none hover:outline hover:outline-black bg-gradient-to-r from-blue-100 via-blue-300 to-blue-500"
                      @click="updateAppearanceForm.background_color = 'bg-gradient-to-r from-blue-100 via-blue-300 to-blue-500'">
                    {{ __('Text') }}
                  </div>

                  <div
                      :class="{ 'outline outline-black': updateAppearanceForm.background_color === 'bg-gradient-to-r from-red-400 via-gray-300 to-blue-500' }"
                      class="flex items-center justify-center p-3 text-sm font-medium text-center rounded-lg cursor-pointer select-none hover:outline hover:outline-black bg-gradient-to-r from-red-400 via-gray-300 to-blue-500"
                      @click="updateAppearanceForm.background_color = 'bg-gradient-to-r from-red-400 via-gray-300 to-blue-500'">
                    {{ __('Text') }}
                  </div>

                  <div
                      :class="{ 'outline outline-black': updateAppearanceForm.background_color === 'bg-gradient-to-r from-rose-400 via-fuchsia-500 to-blue-500' }"
                      class="flex items-center justify-center p-3 text-sm font-medium text-center rounded-lg cursor-pointer select-none hover:outline hover:outline-black bg-gradient-to-r from-rose-400 via-fuchsia-500 to-blue-500"
                      @click="updateAppearanceForm.background_color = 'bg-gradient-to-r from-rose-400 via-fuchsia-500 to-blue-500'">
                    {{ __('Text') }}
                  </div>

                  <div
                      :class="{ 'outline outline-black': updateAppearanceForm.background_color === 'bg-gradient-to-b from-orange-500 to-yellow-300' }"
                      class="flex items-center justify-center p-3 text-sm font-medium text-center rounded-lg cursor-pointer select-none hover:outline hover:outline-black bg-gradient-to-b from-orange-500 to-yellow-300"
                      @click="updateAppearanceForm.background_color = 'bg-gradient-to-b from-orange-500 to-yellow-300'">
                    {{ __('Text') }}
                  </div>

                  <div
                      :class="{ 'outline outline-black': updateAppearanceForm.background_color === 'bg-gradient-to-r from-teal-200 to-lime-200' }"
                      class="flex items-center justify-center p-3 text-sm font-medium text-center rounded-lg cursor-pointer select-none hover:outline hover:outline-black bg-gradient-to-r from-teal-200 to-lime-200"
                      @click="updateAppearanceForm.background_color = 'bg-gradient-to-r from-teal-200 to-lime-200'">
                    {{ __('Text') }}
                  </div>

                  <div
                      :class="{ 'outline outline-black': updateAppearanceForm.background_color === 'bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-gray-300 via-fuchsia-600 to-orange-600' }"
                      class="hover:outline select-none hover:outline-black text-sm flex justify-center items-center text-center font-medium cursor-pointer p-3 rounded-lg bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-gray-300 via-fuchsia-600 to-orange-600"
                      @click="updateAppearanceForm.background_color = 'bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-gray-300 via-fuchsia-600 to-orange-600'">
                    {{ __('Text') }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Card>
  </AdminLayout>
</template>

<style scoped></style>
