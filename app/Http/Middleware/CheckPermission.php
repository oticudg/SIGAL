<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Permission;
use App\Permissions_assigned;

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

        if( Auth::user()->id  == 1)
          return $next($request);

        $idP  = Permission::where('ip', $permission)->value('id');
        $rol  = Auth::user()->rol;

        if(!Permissions_assigned::where('role', $rol)->where('permission', $idP)->first()){
          return redirect('auth/logout');
        }

        return $next($request);
    }
}
