<?php

namespace Qoraiche\Peak\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Qoraiche\Peak\Settings\Admin\WebsiteSecuritySettings;

trait ConfirmsTwoFactorAuthentication
{
    /**
     * Validate the two factor authentication state for the request.
     *
     * @param Request
     * @return void
     */
    protected function validateTwoFactorAuthenticationState(Request $request)
    {
        $securitySettings = app(WebsiteSecuritySettings::class);

        if (!$securitySettings->two_factor_auth_enabled) {
            return;
        }
        
        /*if (!Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm')) {
            return;
        }*/

        $currentTime = time();

        // Notate totally disabled state in session...
        if ($this->twoFactorAuthenticationDisabled($request)) {
            $request->session()->put('two_factor_empty_at', $currentTime);
        }

        // If was previously totally disabled this session but is now confirming, notate time...
        if ($this->hasJustBegunConfirmingTwoFactorAuthentication($request)) {
            $request->session()->put('two_factor_confirming_at', $currentTime);
        }

        // If the profile is reloaded and is not confirmed but was previously in confirming state, disable...
        if ($this->neverFinishedConfirmingTwoFactorAuthentication($request, $currentTime)) {
            app(DisableTwoFactorAuthentication::class)(Auth::user());

            $request->session()->put('two_factor_empty_at', $currentTime);
            $request->session()->remove('two_factor_confirming_at');
        }
    }

    /**
     * Determine if two factor authenticatoin is totally disabled.
     *
     * @param Request $request
     * @return bool
     */
    protected function twoFactorAuthenticationDisabled(Request $request)
    {
        return is_null($request->user()->two_factor_secret) &&
            is_null($request->user()->two_factor_confirmed_at);
    }

    /**
     * Determine if two factor authentication is just now being confirmed within the last request cycle.
     *
     * @param Request $request
     * @return bool
     */
    protected function hasJustBegunConfirmingTwoFactorAuthentication(Request $request)
    {
        return !is_null($request->user()->two_factor_secret) &&
            is_null($request->user()->two_factor_confirmed_at) &&
            $request->session()->has('two_factor_empty_at') &&
            is_null($request->session()->get('two_factor_confirming_at'));
    }

    /**
     * Determine if two factor authentication was never totally confirmed once confirmation started.
     *
     * @param Request $request
     * @param int $currentTime
     * @return bool
     */
    protected function neverFinishedConfirmingTwoFactorAuthentication(Request $request, $currentTime)
    {
        return !array_key_exists('code', $request->session()->getOldInput()) &&
            is_null($request->user()->two_factor_confirmed_at) &&
            $request->session()->get('two_factor_confirming_at', 0) != $currentTime;
    }
}
