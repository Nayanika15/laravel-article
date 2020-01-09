<?php


namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;

use Closure;

class CheckMobile
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
        if(is_null(Auth::user()->mobile))
        {   
            return redirect()->route('add-phone');
        }
        else
        {
            return $next($request);
        }
    }
}
