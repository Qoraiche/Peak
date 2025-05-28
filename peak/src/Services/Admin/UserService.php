<?php

namespace Qoraiche\Peak\Services\Admin;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Laravel\Cashier\Cashier;
use Qoraiche\Peak\Models\Subscription;
use Qoraiche\Peak\Models\SubscriptionPlanPricing;
use Qoraiche\Peak\Models\User;
use Qoraiche\Peak\Settings\Admin\SellingStripePaymentSettings;

class UserService
{
    /**
     * @param string|null $search
     * @param array|null $roles
     * @param int $limit
     * @param array $columns
     * @return Collection
     */
    public function getUsersByRole(?string $search, ?array $roles = ['admin'], int $limit = 10, array $columns = ['*'])
    {
        $query = User::query()->select($columns);

        if ($roles && collect($roles)->isNotEmpty()) {
            $query->whereHas('roles', function ($query) use ($roles) {
                $query->whereIn('name', $roles);
            });
        }

        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        })
            ->limit($limit)
            ->get();
    }

    public function getSubscriptionCollectionMethod($user)
    {
        /** @var \Laravel\Cashier\Subscription|null $billable */
        $subscription = $user->subscription();

        // Filter out incomplete subscriptions for now...
        if ($subscription && $subscription->incomplete()) {
            $subscription = null;
        }

        $collectionMethod = $subscription?->provider === Subscription::STRIPE_PROVIDER_NAME ? $subscription?->asStripeSubscription()->collection_method : null;

        return $subscription ? $collectionMethod : null;
    }

    public function userSubscription($user)
    {
        $subscription = $user->subscription();

        // Filter out incomplete subscriptions for now...
        if ($subscription && $subscription->incomplete()) {
            $subscription = null;
        }

        $plans = $this->getPlans($user);

        return ['plan' => $subscription && ($subscription->active() || $subscription->pastDue())
            ? $plans->firstWhere('stripe_id', $subscription->stripe_price)
            : null, 'subscription' => $subscription];
    }

    public function getPlans($billable)
    {
        $plans = SubscriptionPlanPricing::all();

        $prices = collect();

        if (app(SellingStripePaymentSettings::class)->active) {
            $prices = collect($billable->stripe()->prices->all(['limit' => 100])->autoPagingIterator());
        }

        return $plans->map(function ($plan) use ($prices) {
            if (!$stripePrice = $prices->firstWhere('id', $plan->stripe_id)) {
//                continue;
//                throw new RuntimeException('Price [' . $plan->stripe_id . '] does not exist in your Stripe account.');
            }

            $plan->rawPrice = $stripePrice?->unit_amount ?? 0;
            $plan->features = $plan->subscriptionPlan->features;
            $plan->name = $plan->subscriptionPlan->name;
            $plan->short_description = $plan->subscriptionPlan->description;
            $plan->active = true;

            $plan->incentive = [
                'monthly' => null,
                'yearly' => null,
            ];

            $price = Cashier::formatAmount($stripePrice?->unit_amount ?? 0, $stripePrice?->currency ?? 'USD');

            if (Str::endsWith($price, '.00')) {
                $price = substr($price, 0, -3);
            }

            if (Str::endsWith($price, '.0')) {
                $price = substr($price, 0, -2);
            }

            $plan->price = $price;
            $plan->currency = $stripePrice?->currency ?? '$';

            return $plan;
        });
    }
}
