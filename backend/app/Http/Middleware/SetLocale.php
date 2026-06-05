<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sets the application locale from a `?lang=` query param (or the
 * Accept-Language header), restricted to the supported locales. This drives the
 * Translatable cast so `GET /cms/site?lang=en` returns English content.
 */
class SetLocale
{
    private const SUPPORTED = ['pl', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $requested = $request->query('lang')
            ?? substr((string) $request->header('Accept-Language'), 0, 2);

        if (is_string($requested) && in_array($requested, self::SUPPORTED, true)) {
            app()->setLocale($requested);
        }

        return $next($request);
    }
}
