<script setup>

import Card from "@peak/Components/Admin/Card.vue";
import TextInput from "@peak/Components/Admin/Input.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import InputLabel from "@peak/Components/Admin/InputLabel.vue";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import SelectInput from "@peak/Components/Admin/SelectInput.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {Switch, SwitchDescription, SwitchGroup, SwitchLabel} from "@headlessui/vue";
import {useForm} from "@inertiajs/vue3";
import {inject} from "vue";
import {useToast} from "vue-toastification";
import {__} from "@peak/Composables/useTranslate.js";

const emitter = inject('emitter');
const toast = useToast();

const props = defineProps({
  pwa_enabled: Boolean,

  name: {type: String, default: null},
  short_name: {type: String, default: null},
  start_url: {type: String, default: null},
  background_color: {type: String, default: null},
  theme_color: {type: String, default: null},
  display: {type: String, default: null},
  orientation: {type: String, default: null},
  status_bar: {type: String, default: null},

  // icons
  icons_72x72: {type: String, default: null},
  icons_96x96: {type: String, default: null},
  icons_128x128: {type: String, default: null},
  icons_144x144: {type: String, default: null},
  icons_152x152: {type: String, default: null},
  icons_192x192: {type: String, default: null},
  icons_384x384: {type: String, default: null},
  icons_512x512: {type: String, default: null},

  // splash
  splash_640x1136: {type: String, default: null},
  splash_750x1334: {type: String, default: null},
  splash_828x1792: {type: String, default: null},
  splash_1125x2436: {type: String, default: null},
  splash_1242x2208: {type: String, default: null},
  splash_1242x2688: {type: String, default: null},
  splash_1536x2048: {type: String, default: null},
  splash_1668x2224: {type: String, default: null},
  splash_1668x2388: {type: String, default: null},
  splash_2048x2732: {type: String, default: null},
});

const form = useForm({
  pwa_enabled: props.pwa_enabled,
  name: props.name,
  short_name: props.short_name,
  start_url: props.start_url,
  background_color: props.background_color,
  theme_color: props.theme_color,
  display: props.display,
  orientation: props.orientation,
  status_bar: props.status_bar,
  // icons
  icons_72x72: props.icons_72x72,
  icons_96x96: props.icons_96x96,
  icons_128x128: props.icons_128x128,
  icons_144x144: props.icons_144x144,
  icons_152x152: props.icons_152x152,
  icons_192x192: props.icons_192x192,
  icons_384x384: props.icons_384x384,
  icons_512x512: props.icons_512x512,
  // splash
  splash_640x1136: props.splash_640x1136,
  splash_750x1334: props.splash_750x1334,
  splash_828x1792: props.splash_828x1792,
  splash_1125x2436: props.splash_1125x2436,
  splash_1242x2208: props.splash_1242x2208,
  splash_1242x2688: props.splash_1242x2688,
  splash_1536x2048: props.splash_1536x2048,
  splash_1668x2224: props.splash_1668x2224,
  splash_1668x2388: props.splash_1668x2388,
  splash_2048x2732: props.splash_2048x2732,
});

const save = () => {
  form.put(route('admin.settings.website.pwa.update'), {
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
  <AdminLayout :description="__('Progressive Web App settings')" :title="__('Progress Web App')">
    <template #actions>
      <PrimaryButton :class="{ 'opacity-25': !form.isDirty || form.processing }"
                     :disabled="!form.isDirty || form.processing" :loading="form.processing"
                     class="self-end"
                     @click="save">
        {{ __('Save Changes') }}
      </PrimaryButton>
    </template>

    <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
      <template #header>
        {{ __('Edit Settings') }}
      </template>

      <div class="grid grid-cols-1 gap-5 py-5 md:grid-cols-2">
        <div class="col-span-2">
          <div class="flex flex-col space-y-2">
            <SwitchGroup as="div" class="flex items-center justify-between">
                            <span class="flex flex-col flex-grow">
                                <SwitchLabel as="span" class="text-sm font-medium leading-6 text-gray-900" passive>
                                    {{ __('Enable Progress Web App') }}
                                </SwitchLabel>
                                <SwitchDescription as="span" class="text-sm text-gray-500">
                                  {{
                                    __('Allow users to install your website as a mobile or desktop app with offline support and faster loading.')
                                  }}
                                </SwitchDescription>
                            </span>
              <Switch v-model="form.pwa_enabled"
                      :class="[form.pwa_enabled ? 'bg-gray-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2']">
                                <span
                                    :class="[form.pwa_enabled ? 'ltr:translate-x-5 rtl:-translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
                                    aria-hidden="true"/>
              </Switch>
            </SwitchGroup>
            <InputError :message="form.errors?.pwa_enabled" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Name')" for="name"/>
            <TextInput id="name" v-model="form.name" class="block w-full"/>
            <InputError :message="form.errors?.name" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Short Name')" for="short_name"/>
            <TextInput id="short_name" v-model="form.short_name" class="block w-full"/>
            <InputError :message="form.errors?.short_name" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Start URL Path')" for="start_url"/>
            <TextInput id="start_url" v-model="form.start_url" class="block w-full"/>
            <InputError :message="form.errors?.start_url" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Background color')" for="background_color"/>
            <TextInput id="background_color" v-model="form.background_color" class="block w-full" type="color"/>
            <InputError :message="form.errors?.background_color" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Theme color')" for="theme_color"/>
            <TextInput id="theme_color" v-model="form.theme_color" class="block w-full" type="color"/>
            <InputError :message="form.errors?.theme_color" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Display')" for="display"/>
            <SelectInput v-model="form.display"
                         :values="['fullscreen', 'standalone', 'minimal-ui', 'browser']" class="block w-full"/>
            <InputError :message="form.errors?.display" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Orientation')" for="orientation"/>
            <SelectInput v-model="form.orientation"
                         :values="['any', 'natural', 'portrait', 'portrait-primary', 'portrait-secondary', 'landscape', 'landscape-primary', 'landscape-secondary']"
                         class="block w-full"/>
            <InputError :message="form.errors?.orientation" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Status bar')" for="status_bar"/>
            <TextInput id="status_bar" v-model="form.status_bar" class="block w-full" type="color"/>
            <InputError :message="form.errors?.status_bar" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Icon 72x72 Path')" for="icons_72x72"/>
            <TextInput id="icons_72x72" v-model="form.icons_72x72" class="block w-full"/>
            <InputError :message="form.errors?.icons_72x72" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Icon 96x96 Path')" for="icons_96x96"/>
            <TextInput id="icons_96x96" v-model="form.icons_96x96" class="block w-full"/>
            <InputError :message="form.errors?.icons_96x96" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Icon 128x128 Path')" for="icons_128x128"/>
            <TextInput id="icons_128x128" v-model="form.icons_128x128" class="block w-full"/>
            <InputError :message="form.errors?.icons_128x128" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Icon 144x144 Path')" for="icons_144x144"/>
            <TextInput id="icons_144x144" v-model="form.icons_144x144" class="block w-full"/>
            <InputError :message="form.errors?.icons_144x144" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Icon 152x152 Path')" for="icons_152x152"/>
            <TextInput id="icons_152x152" v-model="form.icons_152x152" class="block w-full"/>
            <InputError :message="form.errors?.icons_152x152" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Icon 192x192 Path')" for="icons_192x192"/>
            <TextInput id="icons_192x192" v-model="form.icons_192x192" class="block w-full"/>
            <InputError :message="form.errors?.icons_192x192" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Icon 384x384 Path')" for="icons_384x384"/>
            <TextInput id="icons_384x384" v-model="form.icons_384x384" class="block w-full"/>
            <InputError :message="form.errors?.icons_384x384" class="mt-1"/>
          </div>
        </div>


        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Icon 512x512 Path')" for="icons_512x512"/>
            <TextInput id="icons_512x512" v-model="form.icons_512x512" class="block w-full"/>
            <InputError :message="form.errors?.icons_512x512" class="mt-1"/>
          </div>
        </div>

        <!--Splash Settings-->
        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Splash 640x1136 Path')" for="splash_640x1136"/>
            <TextInput id="splash_640x1136" v-model="form.splash_640x1136" class="block w-full"/>
            <InputError :message="form.errors?.splash_640x1136" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Splash 750x1334 Path')" for="splash_750x1334"/>
            <TextInput id="splash_750x1334" v-model="form.splash_750x1334"
                       class="block w-full"/>
            <InputError :message="form.errors?.splash_750x1334" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Splash 828x1792 Path')" for="splash_828x1792"/>
            <TextInput id="splash_828x1792" v-model="form.splash_828x1792"
                       class="block w-full"/>
            <InputError :message="form.errors?.splash_828x1792" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Splash 1125x2436 Path')" for="splash_1125x2436"/>
            <TextInput id="splash_1125x2436" v-model="form.splash_1125x2436"
                       class="block w-full"/>
            <InputError :message="form.errors?.splash_1125x2436" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Splash 1242x2208 Path')" for="splash_1242x2208"/>
            <TextInput id="splash_1242x2208" v-model="form.splash_1242x2208"
                       class="block w-full"/>
            <InputError :message="form.errors?.splash_1242x2208" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Splash 1242x2688 Path')" for="splash_1242x2688"/>
            <TextInput id="splash_1242x2688" v-model="form.splash_1242x2688"
                       class="block w-full"/>
            <InputError :message="form.errors?.splash_1242x2688" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Splash 1536x2048 Path')" for="splash_1536x2048"/>
            <TextInput id="splash_1536x2048" v-model="form.splash_1536x2048"
                       class="block w-full"/>
            <InputError :message="form.errors?.splash_1536x2048" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Splash 1668x2224 Path')" for="splash_1668x2224"/>
            <TextInput id="splash_1668x2224" v-model="form.splash_1668x2224"
                       class="block w-full"/>
            <InputError :message="form.errors?.splash_1668x2224" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Splash 1668x2388 Path')" for="splash_1668x2388"/>
            <TextInput id="splash_1668x2388" v-model="form.splash_1668x2388"
                       class="block w-full"/>
            <InputError :message="form.errors?.splash_1668x2388" class="mt-1"/>
          </div>
        </div>

        <div>
          <div class="flex flex-col space-y-2">
            <InputLabel :value="__('Splash 2048x2732 Path')" for="splash_2048x2732"/>
            <TextInput id="splash_2048x2732" v-model="form.splash_2048x2732"
                       class="block w-full"/>
            <InputError :message="form.errors?.splash_2048x2732" class="mt-1"/>
          </div>
        </div>
      </div>
    </Card>
  </AdminLayout>
</template>

<style scoped></style>
