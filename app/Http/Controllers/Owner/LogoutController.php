<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        DB::table('users')->where('id', '=', session('ownerAuth')->id)
                            ->update(['remember_token' => null]);

        session()->forget('ownerAuth');

        return redirect()->route('owner.login.show')
                            ->withCookie(Cookie::forget('ownerAuth'));
    }
}
