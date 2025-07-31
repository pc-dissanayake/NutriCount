<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserActive
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
        $user = Auth::user();

        if ($user && !$user->active) {
            Auth::logout();
            return redirect('/login')->withErrors(['Your account is inactive. Please contact support.']);
        }

        return $next($request);
    }
}
