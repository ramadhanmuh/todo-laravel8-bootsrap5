<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function show(Request $request) {
        dd($request->cookie('user'));

        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        return view('pages.auth.login', $data);
    }

    public function authenticate(LoginRequest $request) {
        $identity = $request->identity;

        if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
            $identityColumn = 'email';
        } else {
            $identityColumn = 'username';
        }

        $user = DB::table('users')->select('id', 'username', 'name', 'password')
                                    ->where($identityColumn, '=', $identity)
                                    ->where('role', '=', 'User')
                                    ->whereNotNull('email_verified_at')
                                    ->first();

        if (empty($user)) {
            return $this->userNotFoundResponse();
        }

        if (!Hash::check($request->password, $user->password)) {
            return $this->userNotFoundResponse();
        }

        unset($user->password);

        session(['user' => $user]);

        if ($request->remember_me === 'Yes') {
            $remember_token = Str::random(100);

            DB::table('users')->where('id', '=', $user->id)
                                ->update(['remember_token' => $remember_token]);

            // dd(Cookie::make('user', $remember_token, 30));
            Cookie::queue('user', $remember_token, 43800);

            // return response()->json([
            //     'success' => true
            // ])->cookie(cookie('user', $remember_token, 43800));
            return response()->json([
                'success' => true
            ]);
            // $test = Cookie::make('user', $remember_token, 30);
            // dd($test);

            // return response()->json([
            //     'success' => true
            // ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

    private function userNotFoundResponse() {
        return response()->json([
            'errors' => [
                'identity' => ['The provided credentials do not match our records.']
            ]
        ], 422);
    }
}
