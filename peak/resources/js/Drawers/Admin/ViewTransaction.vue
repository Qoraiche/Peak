<script setup>
import {computed, inject, onMounted, ref} from 'vue'
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue';
import {XMarkIcon} from '@heroicons/vue/24/outline'
import Badge from "@peak/Components/Admin/Badge.vue";

const open = ref(false);
const emitter = inject('emitter');
const transaction = ref({});

onMounted(() => {
  emitter.on('transaction:view', (transactionItem) => {
    open.value = true;
    transaction.value = transactionItem;
  });
});

const subscription = computed(() => {
  return transaction.stripeSubscription ?? transaction.paddleSubscription;
});

const subscriptionId = computed(() => {
  return transaction.value.paddle_subscription_id ?? transaction.value.stripe_subscription_id ?? '_';
});

const transactionStatusForBadge = status => {
  switch (status) {
    case 'success':
    case 'paid':
    case 'completed':
      return 'success';
    case 'refunded':
      return 'alert';
    case 'failed':
      return 'danger';
    default:
      return 'default';
  }
};

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
                        {{ __('Transaction Details') }}
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
                        {{ __('View transaction details and payment history.') }}
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
                            #{{ transaction.id }}
                          </dd>
                        </div>

                        <div class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('User') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0">
                            <a :href="route('admin.user-management.users.edit', transaction.user.id)"
                               class="text-blue-600 hover:text-blue-700" target="_blank">
                              {{ transaction.user.name }}
                            </a>
                          </dd>
                        </div>

                        <div class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Provider') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 capitalize text-gray-900 sm:ml-6 sm:mt-0">
                            {{ transaction.provider }}
                          </dd>
                        </div>

                        <div class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Payment ID') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0">
                            {{ transaction.stripe_id ?? transaction.paddle_id ?? '_' }}
                          </dd>
                        </div>

                        <div v-if="subscription" class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Subscription ID') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0">
                            <a :href="route('admin.finance.subscriptions.edit', subscription.id)"
                               class="text-blue-600 hover:text-blue-700" target="_blank">
                              {{ subscription.id }}
                            </a>
                          </dd>
                        </div>

                        <div class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Invoice Number') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0">
                            {{ transaction.invoice_number }}
                          </dd>
                        </div>

                        <div class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Status') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 capitalize text-gray-900 sm:ml-6 sm:mt-0">
                            <Badge
                                :type="transactionStatusForBadge(transaction.status)">
                              {{ transaction.status }}
                            </Badge>
                          </dd>
                        </div>

                        <div class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Full Amount') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0">
                            {{ transaction.full_amount }}
                          </dd>
                        </div>

                        <div v-if="transaction.status === 'refunded'"
                             class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Refund Amount') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0">
                            {{ transaction.refund_amount }}
                          </dd>
                        </div>

                        <div v-if="transaction.status === 'refunded'"
                             class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Refund Reason') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0 break-all">
                            {{ transaction.refund_reason }}
                          </dd>
                        </div>

                        <div v-if="transaction.status === 'failed'"
                             class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Failure Reason') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0 break-all">
                            {{ transaction.failure_reason }}
                          </dd>
                        </div>

                        <div v-if="transaction.status === 'success'"
                             class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Provider Fee') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0">
                            {{ transaction.fee_amount }}
                          </dd>
                        </div>

                        <div v-if="transaction.status === 'success'"
                             class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Tax') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0">
                            {{ transaction.tax }}
                          </dd>
                        </div>

                        <div class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Currency') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0">
                            {{ transaction.currency }}
                          </dd>
                        </div>

                        <div v-if="transaction.status === 'success'"
                             class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Account Country') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0">
                            {{ transaction.country }}
                          </dd>
                        </div>

                        <div v-if="transaction.status === 'success'"
                             class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Billed At') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0">
                            {{ transaction.billed_at }}
                          </dd>
                        </div>

                        <div v-if="transaction.status === 'failed'"
                             class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Failed At') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0">
                            {{ transaction.failed_at }}
                          </dd>
                        </div>

                        <div v-if="transaction.status === 'refunded'"
                             class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Refunded At') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0">
                            {{ transaction.refunded_at }}
                          </dd>
                        </div>

                        <div class="sm:flex sm:px-6 sm:py-3">
                          <dt class="text-sm font-medium text-gray-500 sm:w-40 sm:flex-shrink-0 lg:w-48">
                            {{ __('Created At') }}
                          </dt>

                          <dd class="mt-1 text-sm sm:col-span-2 text-gray-900 sm:ml-6 sm:mt-0">
                            {{ transaction.created_at }}
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
