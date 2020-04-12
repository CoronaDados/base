<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckCanLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $auth_guard = Auth::guard($guard);

        if ($auth_guard->check() && !$auth_guard->user()->can('Efetuar Login')) {
            $auth_guard->logout();
            return redirect('/');
        }

        return $next($request);
    }
}
