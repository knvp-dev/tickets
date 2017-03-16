<?php

namespace App\Http\Middleware;

use Closure;

class IsPartOfATeam
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
        if(! $request->user()->isPartOfATeam($request->team)){
            return redirect('/teams');
        }
        return $next($request);
    }
}
