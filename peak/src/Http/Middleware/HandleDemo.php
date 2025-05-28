<?php

namespace Qoraiche\Peak\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class HandleDemo
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        if (config('app.demo') && $request->method() !== 'GET' && str_starts_with($routeName, 'admin.')
        ) {
            throw ValidationException::withMessages([
                '*' => [__('You’re not allowed to perform this action in demo mode.')],
            ]);
        }

        return $next($request);
    }
}