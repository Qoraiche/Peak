<script setup>

import ConnectPaddlePayment from "@peak/Modals/Admin/Settings/Selling/ConnectPaddlePayment.vue";
import ConnectStripePayment from "@peak/Modals/Admin/Settings/Selling/ConnectStripePayment.vue";
import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {inject} from "vue";
import Card from "@peak/Components/Admin/Card.vue";

const emitter = inject('emitter');

const props = defineProps({
  stripe: Object,
  paddle: Object
});

const connectStripe = () => {
  emitter.emit('payments:connect-stripe');
};

const connectPaddle = () => {
  emitter.emit('payments:connect-paddle');
};
</script>

<template>
  <AdminLayout :description="__('Set up payment gateways and billing settings')" :title="__('Payment Gateways')">
    <ConnectStripePayment :stripe="stripe"/>
    <ConnectPaddlePayment :paddle="paddle"/>

    <Card :collapsible="false" :has-shadow="false" class="flex flex-col space-y-1">
      <template #header>
        {{ __('Settings') }}
      </template>

      <div class="mt-6">
        <h3 class="text-lg font-bold">{{ __('Connect a payment processor') }}</h3>
        <p class="mt-4 text-gray-600">
          {{
            __('Add ways to receive payments. Once you connect a payment processor, customers can check out in your website.')
          }}
        </p>
      </div>

      <div
          class="flex lg:w-1/2 items-center justify-between p-6 mt-8 border border-gray-200 cursor-pointer hover:bg-gray-50"
          @click="connectStripe">
        <div class="text-[#635BFF]">
          <svg role="img" class="size-9 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Stripe</title>
            <path
                d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.594-7.305h.003z"/>
          </svg>
        </div>

        <span v-if="stripe.active"
              class="inline-flex items-center gap-x-1.5 rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                    <svg aria-hidden="true" class="h-1.5 w-1.5 fill-green-500" viewBox="0 0 6 6">
                        <circle cx="3" cy="3" r="3"/>
                    </svg>
                    {{ __('Connect') }}
                </span>

        <span v-else
              class="inline-flex items-center gap-x-1.5 rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">
                    <svg aria-hidden="true" class="h-1.5 w-1.5 fill-yellow-500" viewBox="0 0 6 6">
                        <circle cx="3" cy="3" r="3"/>
                    </svg>
                    {{ __('Stripe Not connected') }}
                </span>
      </div>
      <div
          class="flex lg:w-1/2 items-center justify-between p-6 mt-8 border border-gray-200 cursor-pointer hover:bg-gray-50"
          @click="connectPaddle">

        <div class="text-[#FDDD35] bg-gray-900 p-1">
          <svg role="img" class="size-9 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Paddle</title><path d="M2.363 7.904v.849a3.95 3.95 0 0 1 3.65 2.425c.198.476.3.987.299 1.502h.791c0-1.04.416-2.037 1.157-2.772a3.962 3.962 0 0 1 2.792-1.149V7.91a3.959 3.959 0 0 1-3.65-2.425 3.893 3.893 0 0 1-.299-1.502h-.791c0 1.04-.416 2.037-1.157 2.772a3.96 3.96 0 0 1-2.792 1.149M13.105 2.51H6.312V0h6.793c4.772 0 8.532 3.735 8.532 8.314 0 4.58-3.76 8.314-8.532 8.314H9.156V24H6.312v-9.882h6.793c3.319 0 5.688-2.352 5.688-5.804 0-3.451-2.37-5.804-5.688-5.804"/></svg>
        </div>

        <span v-if="paddle.active"
              class="inline-flex items-center gap-x-1.5 rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                    <svg aria-hidden="true" class="h-1.5 w-1.5 fill-green-500" viewBox="0 0 6 6">
                        <circle cx="3" cy="3" r="3"/>
                    </svg>
                    {{ __('Connect') }}
                </span>

        <span v-else
              class="inline-flex items-center gap-x-1.5 rounded-md bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800">
                    <svg aria-hidden="true" class="h-1.5 w-1.5 fill-yellow-500" viewBox="0 0 6 6">
                        <circle cx="3" cy="3" r="3"/>
                    </svg>
                    {{ __('Paddle Not connected') }}
                </span>
      </div>
    </Card>
  </AdminLayout>
</template>

<style scoped></style>
