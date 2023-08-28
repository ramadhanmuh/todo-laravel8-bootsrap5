<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoggedOutOwner
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
        if (empty(session('ownerAuth'))) {
            $remember_token = $request->cookie('ownerAuth');

            if (!empty($remember_token)) {
                $user = DB::table('users')->select('id', 'name', 'username')
                                            ->where('role', 'owner')
                                            ->where('remember_token', '=', $remember_token)
                                            ->first();
    
                if (!empty($user)) {
                    session(['ownerAuth' => $user]);
        
                    return redirect()->route('owner.dashboard.index');
                }
            }

        } else {
            return redirect()->route('owner.dashboard.index');
        }

        return $next($request);
    }
}
