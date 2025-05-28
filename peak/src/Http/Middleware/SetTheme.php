<?php

namespace Qoraiche\Peak\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Qoraiche\Peak\Settings\Admin\AppearanceThemeSettings;
use Symfony\Component\HttpFoundation\Response;

class SetTheme
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the current theme from Spatie settings
        $theme = app(AppearanceThemeSettings::class)->current_theme_name ?? 'Classic';

        // Share the theme with Inertia
        Inertia::share('theme', $theme);

        return $next($request);
    }
}
