<?php

namespace App\Http\Middleware;

use Closure;

class IsOwnerOfTeam
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
        if( ! auth()->user()->ownsTeam($request->team) ){
            return back();
        }
        return $next($request);
    }
}
