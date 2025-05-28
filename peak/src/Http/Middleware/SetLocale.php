<?php

namespace Qoraiche\Peak\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Qoraiche\Peak\Helpers;
use Qoraiche\Peak\Services\Admin\LanguageService;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{

    public function __construct(private LanguageService $languageService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if a 'lang' parameter exists in the request
        if ($locale = $request->query('lang')) {

            if ($this->languageService->getLanguageByLocaleCode($locale)) {

                session(['locale' => $locale]);

                if (auth()->check()) {
                    $user = auth()->user();
                    // Update preferred_locale only if it is different from the current one
                    if ($user->preferred_locale !== $locale) {
                        $user->preferred_locale = $locale;
                        $user->save();
                    }
                }
            }

        } else {
            // If no 'lang' parameter is provided, use the session value or default locale
            $locale = Helpers::getLocale();
        }

        // Set the application locale
        App::setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
