<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectSettings
{
    public function handle(Request $request, Closure $next)
    {
        /** @noinspection ALL */
        if (!$request->routeIs('profile.show') && $request->isMethod('GET') && auth()->check() && !hasBasecampSetup()) {

            session()->flash('warning', 'Please setup your Basecamp related settings first!');

            return redirect(route('profile.show'));
        }

        return $next($request);
    }
}
