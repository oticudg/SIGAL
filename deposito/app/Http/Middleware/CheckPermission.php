<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Privilegio;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {   
        if ( Auth::user()->haspermission($permission) != true){
            return redirect('auth/logout');
        }

        return $next($request);
    }
}
