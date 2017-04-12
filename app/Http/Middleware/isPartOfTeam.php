<?php

namespace App\Http\Middleware;

use Closure;

class isPartOfTeam
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
        if(! auth()->user()->isPartOfTeam(session('team_id'))){
            return redirect('/');
        }

        return $next($request);
    }
}
