<?php

namespace Qoraiche\Peak\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $forceHttps = config('app.force_https');

        // Redirect to HTTPS if the condition is true and the request is not already secure
        if ($forceHttps && !$request->isSecure() && !app()->isLocal()) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
