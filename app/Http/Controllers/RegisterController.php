<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;

class RegisterController extends Controller
{
    public function show()
    {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        return view('pages.auth.register', $data);
    }

    public function save(RegisterRequest $request) {
        $time = time();

        $input = [
            'id' => $request->id,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'created_at' => $time,
            'role' => 'User'
        ];

        $insertUser = DB::table('users')->insert($input);

        if (!$insertUser) {
            return response()->json([
                'success' => false
            ], 503);
        }

        $token = Str::random(20);

        $expired_at = $time + 10800;

        $insertVerification = DB::table('user_verifications')->insert([
            'user_id' => $request->id,
            'token' => $token,
            'expired_at' => $expired_at
        ]);

        if (!$insertVerification) {
            DB::table('users')->where('id', '=', $request->id)
                                ->delete();

            return response()->json([
                'success' => false
            ], 503);
        }

        Mail::to($request->email)->send(new EmailVerification($input, $token));

        return response()->json([
            'success' => true,
            'expired_at' => $expired_at
        ]);
    }
}
