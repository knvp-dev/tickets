<?php

namespace App\Http\Middleware;

use Closure;

class TeamExistsInSession
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
        if(! session()->has('team_id')){
            return redirect('/teams');
        }
        return $next($request);
    }
}
