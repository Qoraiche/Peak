<script setup>
import {inject, onMounted, ref} from 'vue'
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue';
import {XMarkIcon} from '@heroicons/vue/24/outline'

const open = ref(false);
const emitter = inject('emitter');
const report = ref({});

onMounted(() => {
  emitter.on('report:view', (reportItem) => {
    open.value = true;
    report.value = reportItem;
  });
});
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
                <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                  <div class="bg-blue-600 px-4 py-6 sm:px-6">
                    <div class="flex items-center justify-between">
                      <DialogTitle
                          class="text-base font-semibold leading-6 text-white capitalize">
                        {{ __('Report Details') }}
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
                        {{ __('View reports details.') }}
                      </p>
                    </div>
                  </div>

                  <!-- Main -->
                  <div class="divide-y divide-gray-200">
                    <div class="px-4 py-5 sm:px-0 sm:py-0">
                      <dl class="space-y-5 sm:space-y-0 sm:divide-y sm:divide-gray-200">
                        <div class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('ID') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 capitalize text-gray-900 sm:ml-6 sm:mt-0">
                            #{{ report.id }}
                          </dd>
                        </div>

                        <div class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Report By') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0">
                            <a :href="route('admin.user-management.users.edit', report.user.id)"
                               class="text-blue-600 hover:text-blue-700" target="_blank">
                              {{ report.user.name ?? report.user.username }}
                            </a>
                          </dd>
                        </div>

                        <div class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Type') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 capitalize text-gray-900 sm:ml-6 sm:mt-0">
                            <a :href="report.reportable_link"
                               class="text-blue-600 hover:text-blue-700" target="_blank">
                              {{ report.reportable_name }}
                            </a>
                          </dd>
                        </div>

                        <div class="flex flex-col sm:px-6 sm:py-3 gap-y-4">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Subject') }}
                          </dt>

                          <dd class="text-sm sm:col-span-2 text-gray-900 break-words">
                            {{ report.reportable_title }}
                          </dd>
                        </div>

                        <div class="flex flex-col sm:px-6 sm:py-3 gap-y-4">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Reason') }}
                          </dt>

                          <dd class="text-sm sm:col-span-2 text-gray-900 break-words">
                            {{ report.reason }}
                          </dd>
                        </div>
                      </dl>
                    </div>
                  </div>
                </div>
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
