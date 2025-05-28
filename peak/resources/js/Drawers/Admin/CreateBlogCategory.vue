<script setup>
import {inject, onMounted, ref, watch} from 'vue'
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {XMarkIcon} from '@heroicons/vue/24/outline'
import Input from "@peak/Components/Admin/Input.vue";
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import InputError from "@peak/Components/Admin/InputError.vue";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import {useToast} from "vue-toastification";
import {slugify} from "@peak/Composables/Utils.js";

const open = ref(false)
const emitter = inject('emitter');

const form = useForm({
  name: '',
  slug: '',
});

const toast = useToast();

const generateSlug = () => {
  form.slug = slugify(form.name);
};

const fixSlug = () => {
  form.slug = slugify(form.slug);
}

const store = () => {
  form.post(route('admin.blog.categories.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      toast.success('Blog Category Created');
      open.value = false;
    },
    onError: (error) => {
      //
    },
  });
};

onMounted(() => {
  emitter.on('blog-category:create', (tag) => {
    open.value = true;
  });
});

watch(open, (newVal) => {
  if (newVal) {
    form.clearErrors();
  } else {
    form.reset();
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
                      @submit.prevent="store">
                  <div class="h-0 flex-1 overflow-y-auto">
                    <div class="bg-blue-600 px-4 py-6 sm:px-6">
                      <div class="flex items-center justify-between">
                        <DialogTitle
                            class="text-base font-semibold leading-6 text-white capitalize">
                          New Blog Category
                        </DialogTitle>
                        <div class="ml-3 flex h-7 items-center">
                          <button class="relative rounded-md bg-blue-700 text-blue-200 hover:text-white"
                                  type="button"
                                  @click="open = false">
                            <span class="absolute -inset-2.5"/>
                            <span class="sr-only">Close panel</span>
                            <XMarkIcon aria-hidden="true" class="h-6 w-6"/>
                          </button>
                        </div>
                      </div>
                      <div class="mt-1">
                        <p class="text-sm text-blue-300">
                          Add a new category for blog posts.
                        </p>
                      </div>
                    </div>
                    <div class="flex flex-1 flex-col justify-between">
                      <div class="divide-y divide-gray-200 px-4 sm:px-6">
                        <div class="space-y-6 pb-5 pt-6">
                          <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900"
                                   for="name">
                              Name
                            </label>

                            <div class="mt-2">
                              <Input id="name"
                                     v-model="form.name"
                                     class="block w-full"
                                     required
                                     @input="generateSlug"/>
                            </div>

                            <InputError :message="form.errors?.name" class="mt-2"/>
                          </div>

                          <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900"
                                   for="slug">
                              Slug
                            </label>

                            <div class="mt-2">
                              <Input id="slug"
                                     v-model="form.slug"
                                     class="block w-full"
                                     @change="fixSlug"
                                     @input="fixSlug"/>
                            </div>

                            <InputError :message="form.errors?.slug"
                                        class="mt-2"/>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="flex flex-shrink-0 justify-end px-4 py-4 gap-x-3">
                    <SecondaryButton @click="open = false">Cancel</SecondaryButton>
                    <PrimaryButton :class="{ 'opacity-25': form.processing }"
                                   :disabled="form.processing"
                                   type="submit">
                      Create
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
