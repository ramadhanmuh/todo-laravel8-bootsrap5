<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserIsLoggedIn
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
        $session = session('user', null);

        if (empty($session)) {
            $cookie = $request->cookie('user');

            $user = DB::table('users')->select('id', 'name', 'username')
                                        ->where('remember_token', '=', 'token')
                                        ->first();

            if (empty($user)) {
                return redirect()->route('login.show');
            }
        }

        return $next($request);
    }
}
