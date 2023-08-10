<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;

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

        $user = DB::table('users')->where($identityColumn, '=', $identity)
                                    ->where('role', '=', 'User')
                                    ->whereNotNull('email_verified_at')
                                    ->first();

        if (empty($user)) {
            return response()->json([
                'errors' => [
                    'identity' => 'The provided credentials do not match our records.'
                ]
            ], 422);
        }


    }
}
