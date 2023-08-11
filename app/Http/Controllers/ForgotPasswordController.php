<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function show() {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        return view('pages.auth.forgot-password', $data);
    }

    public function send(ForgotPasswordRequest $request) {
        $email = $request->email;
        $token = Str::random(30);

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token
        ]);

        Mail::to($request->email)->send(new ForgotPassword($email, $token));

        return response()->json([
            'success' => true
        ]);
    }
}
