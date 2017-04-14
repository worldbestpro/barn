<?php

namespace App\Http\Middleware;

use Closure;

class OwnerProtection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $entityName = $request->segment(2);
        if ($request->route($entityName)->user_id != auth()->user()->id)
            return response('Unauthorized.', 401);

        return $next($request);
    }
}
