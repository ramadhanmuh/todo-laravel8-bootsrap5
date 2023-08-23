<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoggedOutAdmin
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
        if (empty(session('adminAuth', null))) {
            $remember_token = $request->cookie('adminAuth');

            if (!empty($remember_token)) {
                $user = DB::table('users')->select('id', 'name', 'username')
                                            ->where('role', 'Administrator')
                                            ->where('remember_token', '=', $remember_token)
                                            ->first();
    
                if (!empty($user)) {
                    session(['adminAuth' => $user]);
        
                    return redirect()->route('admin.dashboard.index');
                }
            }

        } else {
            return redirect()->route('admin.dashboard.index');
        }

        return $next($request);
    }
}
