<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckForceNewPassword
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
        if (
            $request->user() &&
            $request->user()->needChangePassword() &&
            !$request->route()->named('person.profile') &&
            !$request->route()->named('company.verification.notice')
        ) {

            flash('Você está usando uma senha temporária. Por favor, altere a sua senha para prosseguir.', 'danger');

            return redirect()->route('person.profile');
        }

        return $next($request);
    }
}
