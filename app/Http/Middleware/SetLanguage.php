<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
    if (session()->has('locale')) {
        app()->setLocale(session('locale'));
    } elseif ($request->cookie(key:  'locale')) {
        // Fallback to cookie if session storage isn't available
        app()->setLocale($request->cookie('locale'));
    }
        return $next($request);
    }
}
