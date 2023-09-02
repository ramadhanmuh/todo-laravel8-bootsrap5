<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function show(Request $request) {
        $params = $request->query();

        if (!array_key_exists('email', $params) || !array_key_exists('token', $params)) {
            abort(404);
        }

        if (!$this->passwordResetIsAvailable($params)) {
            abort(404);
        }

        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        return view('pages.owner.auth.reset-password', $data);
    }

    public function save(Request $request) {
        $params = $request->query();

        if (!array_key_exists('email', $params) || !array_key_exists('token', $params)) {
            abort(404);
        }

        if (!$this->passwordResetIsAvailable($params)) {
            abort(404);
        }

        $validated = $request->validate([
            'password' => 'required|string|max:255|confirmed'
        ]);

        $deletePasswordResets = DB::table('password_resets')
                                    ->where('email', '=', $params['email'])
                                    ->delete();

        if (!$deletePasswordResets) {
            return response()->json(['success' => false], 503);
        }

        $updateUser = DB::table('users')->where('email', '=', $params['email'])
                                        ->update([
                                            'password' => Hash::make($validated['password'])
                                        ]);

        if (!$updateUser) {
            return response()->json(['success' => false], 503);
        }

        return response()->json(['success' => true]);
    }

    private function passwordResetIsAvailable($params) {
        $passwordReset = DB::table('password_resets as a')
                            ->join('users as b', 'a.email', '=', 'b.email')
                            ->select('a.email')
                            ->where('a.email', '=', $params['email'])
                            ->where('a.token', '=', $params['token'])
                            ->where('b.role', '=', 'Owner')
                            ->whereNotNull('email_verified_at')
                            ->first();

        if (empty($passwordReset)) {
            return 0;
        }

        return 1;
    }
}
