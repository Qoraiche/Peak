<?php

namespace Qoraiche\Peak\Services\User;

use Illuminate\Http\Request;
use Qoraiche\Peak\Models\Referral;
use Qoraiche\Peak\Models\User;
use Str;

class ReferralService
{
    /**
     * @param $user
     * @return string
     */
    public function getUserReferralLink($user = null): string
    {
        $user = $user ?? auth()->user();

        return route('register', ['referral' => $user->referral_code]);
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getUserReferrals($user = null)
    {
        $user = $user ?? auth()->user();

        return Referral::query()->where('referrer_id', $user->id)->get();
    }

    /**
     * @param $user
     * @return void
     */
    public function generateReferralCode($user)
    {
        $user->update(['referral_code' => Str::random(10)]);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function trackReferral(Request $request)
    {
        if ($request->has('referral')) {
            session(['referral' => $request->query('referral')]);
        }
    }

    /**
     * @param $user
     * @return void
     */
    public function processReferral($user)
    {
        $referralCode = session('referral');

        if ($referralCode) {
            $referrer = User::query()->where('referral_code', $referralCode)->first();

            if ($referrer) {
                $user->update(['referrer_id' => $referrer->id]);

                Referral::query()->create([
                    'referrer_id' => $referrer->id,
                    'referred_id' => $user->id,
                    'pending_reward' => config('peak.settings.referrals.reward', 0)
                ]);
            }
        }
    }
}
