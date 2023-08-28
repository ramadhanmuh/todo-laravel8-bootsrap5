<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

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
        $session = session('userAuth', null);

        if (empty($session)) {
            $remember_token = $request->cookie('userAuth');

            if (empty($remember_token)) {
                return redirect()->route('login.show');
            }

            $user = DB::table('users')->select('id', 'name', 'username')
                                        ->where('remember_token', '=', $remember_token)
                                        ->first();

            if (empty($user)) {
                return redirect()->route('login.show');
            }

            session(['userAuth' => $user]);
        } else {
            $user = DB::table('users')->select('id', 'name', 'username')
                                        ->where('role', '=', 'User')
                                        ->where('id', '=', $session->id)
                                        ->first();

            if (empty($user)) {
                session()->forget('userAuth');

                Cookie::forget('userAuth');

                return redirect()->route('login.show');
            }
        }

        $request->attributes->add(['userAuth' => $user]);

        return $next($request);
    }
}
