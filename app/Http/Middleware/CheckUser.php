<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class CheckUser
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
        $admin = auth()->user()->username;
        $role = User::select('role')->where('username', $admin)->first();
        if ($role->role != 'superuser') {
            return redirect('/admin/dashboard');
        }
        return $next($request);
    }
}
