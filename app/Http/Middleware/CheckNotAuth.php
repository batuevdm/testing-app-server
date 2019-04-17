<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Session;

class CheckNotAuth
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
        $id = Session::get('user_id', null);
        if (!$id)
            return $next($request);

        $user = User::find($id);
        if (!$user) {
            return $next($request);
        }

        $role = $user->Role;
        if ($role != 'admin') {
            return $next($request);
        }

        return redirect('/dashboard');
    }
}
