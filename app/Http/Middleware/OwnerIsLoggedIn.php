<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class OwnerIsLoggedIn
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
        $session = session('ownerAuth');

        if (empty($session)) {
            $remember_token = $request->cookie('ownerAuth');

            if (empty($remember_token)) {
                return redirect()->route('owner.login.show');
            }

            $owner = DB::table('users')->select('id', 'name', 'username')
                                        ->where('role', '=', 'Owner')
                                        ->where('remember_token', '=', $remember_token)
                                        ->first();

            if (empty($owner)) {
                session()->forget('ownerAuth');

                Cookie::forget('ownerAuth');

                return redirect()->route('owner.login.show');
            }

            session(['ownerAuth' => $owner]);
        } else {
            $owner = DB::table('users')->select('id', 'name', 'username')
                                        ->where('role', '=', 'owner')
                                        ->where('id', '=', $session->id)
                                        ->first();

            if (empty($owner)) {
                session()->forget('ownerAuth');

                Cookie::forget('ownerAuth');

                return redirect()->route('owner.login.show');
            }
        }

        $request->attributes->add(['ownerAuth' => $owner]);

        return $next($request);
    }
}
