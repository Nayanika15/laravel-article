<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

use Closure;

class CheckRole
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
        
        if( (Auth::user()->is_admin) !=1)
        {   
            return redirect()->route('dashboard')->with('ErrorMessage','You are not authorised for this action.');
        }
        else
        {
            return $next($request);
        }

    }
}
