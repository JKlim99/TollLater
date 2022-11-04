<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TollOperator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $session = $request->session()->get('type') ?? null;
        if(!$session)
        {
            return redirect('/admin');
        }
        else{
            if($session == 'user')
            {
                return redirect('/dashboard');
            }
            else if($session == 'admin')
            {
                return redirect('/admin/users');
            }
        }
        return $next($request);
    }
}
