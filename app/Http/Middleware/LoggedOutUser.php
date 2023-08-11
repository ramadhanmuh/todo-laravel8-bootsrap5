<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LoggedOutUser
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
        $session = session('userAuth', null);

        if (!empty($session)) {
            return redirect()->route('user.home');
        }

        return $next($request);
    }
}
