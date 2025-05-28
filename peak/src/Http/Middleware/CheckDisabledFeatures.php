<?php

namespace Qoraiche\Peak\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Qoraiche\Peak\Support\PeakFeatures;
use Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckDisabledFeatures
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $disabledFeatures = config('peak.disabled_features', []);
        $currentRouteName = $request->route()?->getName();

        if ($currentRouteName) {
            foreach ($disabledFeatures as $feature) {
                $patterns = (array)PeakFeatures::pattern($feature);

                foreach ($patterns as $pattern) {
                    if ($pattern === $currentRouteName || Str::is($pattern, $currentRouteName)) {
                        throw new NotFoundHttpException();
                    }
                }
            }
        }

        return $next($request);
    }
}
