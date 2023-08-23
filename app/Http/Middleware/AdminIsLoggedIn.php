<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminIsLoggedIn
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
        $session = session('adminAuth', null);

        if (empty($session)) {
            $remember_token = $request->cookie('adminAuth');

            if (empty($remember_token)) {
                return redirect()->route('admin.login.show');
            }

            $user = DB::table('users')->select('id', 'name', 'username')
                                        ->where('role', '=', 'Administrator')
                                        ->where('remember_token', '=', $remember_token)
                                        ->first();

            if (empty($user)) {
                return redirect()->route('login.show');
            }

            session(['adminAuth' => $user]);
        } else {
            $user = DB::table('users')->select('id', 'name', 'username')
                                        ->where('id', '=', $session->id)
                                        ->first();

            if (empty($user)) {
                return redirect()->route('login.show');
            }
        }

        $request->attributes->add(['adminAuth' => $user]);

        return $next($request);
    }
}
