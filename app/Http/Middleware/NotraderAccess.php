<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class NotraderAccess
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
        if ( auth()->user()->role == 'trader' ) {
            Auth::logout();
            Session()->flush();
            return redirect('/login')->with('message', 'Invalid Credentials');
        }
        return $next($request);
    }
}
