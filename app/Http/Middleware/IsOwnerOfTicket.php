<?php

namespace App\Http\Middleware;

use Closure;

class IsOwnerOfTicket
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
        if(! auth()->user()->ownsTicket($request->ticket)){
            throw new \Exception(403);
        }
        return $next($request);
    }
}
