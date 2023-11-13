<?php

namespace App\Http\Middleware;

use Closure;
use Ushahidi\Core\Support\Facades\Feature;

class CheckFeature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$features
     * @return mixed
     */
    public function handle($request, Closure $next, ...$features)
    {
        foreach ($features as $feature) {
            if (! Feature::isEnabled($feature)) {
                abort(404);
            }
        }

        return $next($request);
    }
}
