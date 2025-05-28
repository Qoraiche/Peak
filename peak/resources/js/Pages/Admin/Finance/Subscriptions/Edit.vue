<script setup>

import AdminLayout from "@peak/Layouts/Admin/AdminLayout.vue";
import {Link, router} from '@inertiajs/vue3';
import {inject} from "vue";
import AlertInfo from "@peak/Components/Admin/Alerts/AlertInfo.vue";
import SecondaryButton from "@peak/Components/Admin/SecondaryButton.vue";
import DangerButton from "@peak/Components/Admin/DangerButton.vue";
import {useToast} from "vue-toastification";
import Card from "@peak/Components/Admin/Card.vue";
import {useLoading} from "vue-loading-overlay";
import Badge from "@peak/Components/Admin/Badge.vue";
import {__} from "@peak/Composables/useTranslate.js";
import {useConfirm} from "@peak/Composables/useConfirm.js";

const toast = useToast();
const emitter = inject('emitter');

const props = defineProps({
  user: Object,
  subscription: Object | null,
  plans: Object | null,
});

const $loading = useLoading({
  active: true,
  color: 'blue'
});

const cancelSubscription = async () => {
  const confirmed = await useConfirm({
    title: __("Cancel subscription?"),
    text: __("Are you sure you want to cancel this subscription?"),
    confirmButtonText: __("Yes, cancel it"),
  });

  if (confirmed) {
    const loader = $loading.show();

    router.put(route('dashboard.account.billing.subscription.cancel', props.subscription?.id), {}, {
      onSuccess: () => {
        toast.success(__('Subscription canceled'))
      },
      onFinish: () => {
        loader.hide();
      },
      onError: (errors) => {
        toast.error(errors['*']);
      },
    });
  }
}

const changeSubscriptionPlan = () => {
  emitter.emit('subscription:switch', {
    currentPlan: props.subscription.plan_pricing,
    plans: props.plans,
    paymentGateway: props.subscription.provider
  });
};

const planStatusForBadge = status => {
  switch (status) {
    case 'active':
      return 'success';
    case 'canceled':
    case 'incomplete':
    case 'unpaid':
      return 'alert';
    case 'ended':
      return 'danger';
    default:
      return 'default';
  }
};
</script>

<template>
  <AdminLayout :description="__('Edit :resourcename #:id', {resourcename: 'Subscription', id: subscription.id})"
               :page-icon="false" :title="__('Edit Subscription')">
    <div class="mx-auto mt-8 grid max-w-3xl grid-cols-1 gap-6 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
      <div class="space-y-6 lg:col-span-2 lg:col-start-1">
        <Card :bordered="true" :collapsible="false">
          <template #header>{{ __('Subscription') }}</template>
          <template #description>{{ __('Details') }}</template>
          <div v-if="subscription" class="flex flex-col space-y-3">
            <dl class="grid grid-cols-1 sm:grid-cols-2">
              <div class="px-4 py-3 sm:col-span-1 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">{{ __('Subscription ID') }}</dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:mt-2 capitalize">
                  #{{ subscription.id }}
                </dd>
              </div>

              <div class="px-4 py-3 sm:col-span-1 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">{{ __('Subscriber') }}</dt>
                <dd v-if="subscription.user"
                    class="mt-1 text-sm/6 sm:mt-2 hover:underline text-blue-600">
                  <Link
                      :href="route('admin.user-management.users.edit', subscription.user.id)">
                    {{ subscription.user.name }}
                  </Link>
                </dd>
              </div>
              <div class="px-4 py-3 sm:col-span-1 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">{{ __('Subscriber Email') }}</dt>
                <dd v-if="subscription.user"
                    class="mt-1 text-sm/6 sm:mt-2 hover:underline text-blue-600">
                  <Link
                      :href="route('admin.user-management.users.edit', subscription.user.id)">
                    {{ subscription.user.email }}
                  </Link>
                </dd>
              </div>
              <div class="px-4 py-3 sm:col-span-1 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">{{ __('Subscribed at') }}</dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:mt-2">{{ subscription.created_at }}</dd>
              </div>
              <div class="px-4 py-6 sm:col-span-1 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">{{ __('Subscription Status') }}</dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:mt-2 capitalize">
                  <Badge
                      :type="planStatusForBadge(subscription.stripe_status ? subscription.stripe_status?.toLowerCase() : subscription.status?.toLowerCase())">
                    {{ subscription.stripe_status ?? subscription.status }}
                  </Badge>
                </dd>
              </div>
              <div class="px-4 py-6 sm:col-span-1 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">{{ __('Plan') }}</dt>
                <dd v-if="subscription.plan_pricing"
                    class="mt-1 text-sm/6 sm:mt-2 hover:underline text-blue-600">
                  <Link
                      :href="route('admin.finance.plans.edit', subscription.plan_pricing.subscription_plan.id)">
                    {{ subscription.plan_pricing.formatted_price }}
                    {{ subscription.plan_pricing.subscription_plan.name }} /
                    {{ subscription.plan_pricing.interval }}
                  </Link>
                </dd>
              </div>
              <div class="px-4 py-6 sm:col-span-1 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">
                  {{ __('Payment Gateway') }}
                </dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:mt-2 capitalize">{{
                    subscription.provider
                  }}
                </dd>
              </div>
              <div class="px-4 py-6 sm:col-span-1 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">
                  {{ __('Provider Subscription ID') }}
                </dt>

                <dd class="mt-1 text-sm/6 text-gray-700 sm:mt-2">
                  {{ subscription?.provider === 'paddle' ? subscription?.paddle_id : subscription?.stripe_id }}
                </dd>
              </div>

              <div class="px-4 py-6 sm:col-span-1 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">
                  {{ __('Price ID') }}
                </dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:mt-2">
                  {{ subscription?.provider === 'stripe' ? subscription.stripe_price : subscription.paddle_price }}
                </dd>
              </div>

              <div class="px-4 py-6 sm:col-span-1 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">
                  {{ __('Quantity (Seats)') }}
                </dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:mt-2">
                  {{ subscription?.quantity ?? 1 }}
                </dd>
              </div>

              <div class="px-4 py-6 sm:col-span-1 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">
                  {{ __('Trial Ends at') }}
                </dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:mt-2">
                  {{ subscription.trial_ends_at ?? '_' }}
                </dd>
              </div>

              <div class="px-4 py-6 sm:col-span-1 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-900">
                  {{ __('Ends At') }}
                </dt>
                <dd class="mt-1 text-sm/6 text-gray-700 sm:mt-2">
                  {{ subscription.ends_at ?? '_' }}
                </dd>
              </div>
            </dl>
          </div>

          <div v-else>
            <AlertInfo>
              {{ __('No active subscription is associated with this user.') }}
            </AlertInfo>
          </div>

          <template
              v-if="(subscription?.stripe_status === 'active' || subscription?.stripe_status === 'past_due') || (subscription?.status === 'active' || subscription?.status === 'past_due')"
              #footer>
            <div class="flex items-center gap-x-2">
              <SecondaryButton @click="changeSubscriptionPlan">
                {{ __('Change Plan') }}
              </SecondaryButton>
              
              <DangerButton @click="cancelSubscription">
                {{ __('Cancel Subscription') }}
              </DangerButton>
            </div>
          </template>
        </Card>
      </div>
    </div>
  </AdminLayout>
</template>

<style scoped>

</style>
