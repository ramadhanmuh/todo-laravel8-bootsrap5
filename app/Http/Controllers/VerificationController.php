<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class VerificationController extends Controller
{
    public function verify($user_id, $token) {
        $time = time();

        $userVerification = DB::table('user_verifications')
                                ->join('users', 'users.id', '=', 'user_verifications.user_id')
                                ->where('user_verifications.user_id', '=', $user_id)
                                ->where('user_verifications.token', '=', $token)
                                ->where('user_verifications.expired_at', '>=', $time)
                                ->whereNull('users.email_verified_at')
                                ->delete();

        if (!$userVerification) {
            abort(419);
        }

        DB::table('users')->where('id', '=', $user_id)
                            ->update(['email_verified_at' => $time]);

        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        return view('pages.auth.verified', $data);
    }
}
