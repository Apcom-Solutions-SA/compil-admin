<?php

namespace App\Http\Middleware;

use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!auth()->check()){
            return redirect('/login');
        }
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return $next($request);
        }
        abort(403, trans('front.no_permission'));
    }
}