<script setup>
import {computed, inject, onMounted, ref} from 'vue'
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {XMarkIcon} from '@heroicons/vue/24/outline';
import PrimaryButton from "@peak/Components/Admin/PrimaryButton.vue";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@peak/Components/Admin/InputError.vue";
import SelectInput from "@peak/Components/Admin/SelectInput.vue";
import {useToast} from "vue-toastification";
import {useLoading} from "vue-loading-overlay";
import AlertInfo from "@peak/Components/Admin/Alerts/AlertInfo.vue";

const open = ref(false);
const toast = useToast();
const emitter = inject('emitter');
const plans = ref({});
const currentPlan = ref({});
const paymentGateway = ref(null);

const form = useForm({
  plan: null
});

onMounted(() => {
  emitter.on('subscription:switch', (event) => {
    console.log(event.currentPlan);

    open.value = true;
    paymentGateway.value = event.paymentGateway;

    plans.value = event.plans?.map(function (pricing) {
      return {
        key: event.paymentGateway === 'stripe' ? pricing.stripe_id : pricing.paddle_id,
        value: pricing.subscription_plan?.name + ' / ' + pricing?.interval + ' (' + pricing?.formatted_price + ')'
      };
    });

    form.plan = plans.value[0].key;
    currentPlan.value = event.currentPlan;
  });
});

const $loading = useLoading({
  active: true,
  color: 'blue'
});

const updatePlan = () => {

  const loader = $loading.show();

  form.put(route('dashboard.account.billing.subscription.update'), {
    preserveState: false,
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Subscription plan changed')
    },
    onFinish: () => {
      loader.hide();
    },
    onError: (errors) => {
      toast.error(errors['*']);
    },
  });
};

/**
 * @type {ComputedRef<unknown>}
 */
const selectedPlanByGatewayAvailable = computed(() => {
  return (paymentGateway.value === 'stripe' && currentPlan.value.stripe_id != form.plan) || (paymentGateway.value === 'paddle' && currentPlan.value.paddle_id != form.plan);
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
                <form class="flex h-full flex-col divide-y divide-gray-200 bg-white shadow-xl"
                      @submit.prevent="updatePlan">
                  <div class="h-0 flex-1 overflow-y-auto">
                    <div class="bg-blue-600 px-4 py-6 sm:px-6">
                      <div class="flex items-center justify-between">
                        <DialogTitle
                            class="text-base font-semibold leading-6 text-white capitalize">
                          Change Subscription Plan
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
                          Manually switch the user's subscription.
                        </p>
                      </div>
                    </div>

                    <div class="flex flex-1 flex-col justify-between">
                      <div class="divide-y divide-gray-200 px-4 sm:px-6">
                        <div class="space-y-6 pb-5 pt-6">
                          <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900"
                                   for="new-password">
                              Current Plan
                            </label>

                            <div class="mt-2 flex flex-col space-y-1">
                                                            <span class="text-lg font-semibold">{{
                                                                currentPlan.name
                                                              }}</span>
                              <span class="font-medium text-sm capitalize">{{
                                  currentPlan.formatted_price
                                }} / {{ currentPlan.interval }}</span>
                            </div>
                          </div>
                        </div>

                        <div class="space-y-6 pb-5 pt-6">
                          <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900"
                                   for="new-plan">
                              New Plan
                            </label>

                            <div class="mt-2">
                              <SelectInput id="new-plan" v-model="form.plan"
                                           :values="plans"/>
                            </div>

                            <InputError :message="form.errors?.plan" class="mt-2"/>
                          </div>

                          <AlertInfo>
                            Important: Plan change will happen immediately and depending on
                            proration setting you set, user might be billed immediately full
                            plan price or a proration is applied.
                          </AlertInfo>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="flex flex-shrink-0 justify-end px-4 py-4">
                    <SecondaryButton @click="open = false">Cancel</SecondaryButton>
                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing || !form.isDirty || !selectedPlanByGatewayAvailable }"
                        :disabled="form.processing || !form.isDirty || !selectedPlanByGatewayAvailable"
                        class="ltr:ml-2 rtl:mr-2"
                        type="submit"
                        @click="updatePlan">
                      {{
                        !selectedPlanByGatewayAvailable ? 'Choose a different Plan' : 'Update Plan'
                      }}
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
