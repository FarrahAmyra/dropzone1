<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {

        // current user loggedin info
        $user = auth()->user();

        // check user role, break if not allowed
        if(!$user->hasRole($role)){
            dd('Maaf, anda bukan admin!');
        }

        return $next($request);

    }
}
