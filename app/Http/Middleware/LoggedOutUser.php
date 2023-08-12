<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        if (empty(session('userAuth', null))) {
            $remember_token = $request->cookie('userAuth');

            $user = DB::table('users')->select('id', 'name', 'username')
                                        ->where('remember_token', '=', $remember_token)
                                        ->first();

            if (!empty($user)) {
                session(['userAuth' => $user]);
    
                return redirect()->route('user.home');
            }
        }
        return $next($request);
    }
}
