<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function show() {
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

        session(['userAuth' => $user]);

        if ($request->remember_me === 'Yes') {
            $remember_token = Str::random(100);

            DB::table('users')->where('id', '=', $user->id)
                                ->update(['remember_token' => $remember_token]);

            return response()->json([
                'success' => true
            ])->cookie('userAuth', $remember_token, 43800);
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
