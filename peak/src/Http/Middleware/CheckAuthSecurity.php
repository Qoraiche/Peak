<?php

namespace Qoraiche\Peak\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Qoraiche\Peak\Settings\Admin\WebsiteSecuritySettings;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): Response $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $securitySettings = app(WebsiteSecuritySettings::class);

        if (!$securitySettings->profile_management_enabled && $request->is('dashboard/account/profile', 'dashboard/account/profile/*')) {
            return response()->json(['message' => __('profile management is disabled.')], 403);
        }

        if (!$securitySettings->teams_management_enabled && $request->is('teams', 'teams/*')) {
            return response()->json(['message' => __('teams management is disabled.')], 403);
        }

        if (!$securitySettings->login_enabled && $request->is('login', 'login/*')) {
            return response()->json(['message' => __('Login is disabled.')], 403);
        }

        if (!$securitySettings->registration_enabled && $request->is('register', 'register/*')) {
            return response()->json(['message' => __('Registration is disabled.')], 403);
        }

        if (!$securitySettings->reset_password_enabled && $request->is('forgot-password', 'password/reset', 'password/reset/*')) {
            return response()->json(['message' => __('Password reset is disabled.')], 403);
        }

        return $next($request);
    }
}
