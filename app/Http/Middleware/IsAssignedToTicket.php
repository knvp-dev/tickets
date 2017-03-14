<?php

namespace App\Http\Middleware;

use Closure;
use App\Ticket;

class IsAssignedToTicket
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
        if(! $request->user()->isAssignedToTicket($request->ticket)){
            return redirect()->back();
        }

        return $next($request);
    }
}
