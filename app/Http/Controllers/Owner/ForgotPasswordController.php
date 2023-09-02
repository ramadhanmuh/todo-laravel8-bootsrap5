<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Owner\ForgotPasswordRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function show() {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        return view('pages.owner.auth.forgot-password', $data);
    }

    public function send(ForgotPasswordRequest $request) {
        $email = $request->email;
        $token = Str::random(40);
        $url = route('owner.reset-password.show', [
            'email' => $email,
            'token' => $token
        ]);

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token
        ]);

        Mail::to($email)->send(new ForgotPassword($email, $token, $url));

        return response()->json([
            'success' => true
        ]);
    }
}
