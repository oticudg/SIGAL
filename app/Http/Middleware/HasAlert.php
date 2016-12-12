<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\AlertsRepository;

class HasAlert
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
        if( !AlertsRepository::alert() ){
            abort('404');
        }

        return $next($request);
    }
}
