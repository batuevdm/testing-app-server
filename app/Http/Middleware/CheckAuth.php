<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Session;

class CheckAuth
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
            return redirect('/dashboard/login');

        $user = User::find($id);
        if (!$user) {
            return redirect('/dashboard/logout');
        }

        $role = $user->Role;
        if ($role != 'admin') {
            return redirect('/dashboard/logout');
        }

        return $next($request);
    }
}
