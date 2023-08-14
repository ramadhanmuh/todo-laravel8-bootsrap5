<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function edit() {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['navbarActive'] = 'change-password';

        return view('pages.user.change-password.edit', $data);
    }

    public function update(ChangePasswordRequest $request) {
        $input = [
            'password' => Hash::make($request->password),
            'updated_at' => time()
        ];

        $process = DB::table('users')->where('id', '=', session('userAuth')->id)
                                        ->update($input);

        if (!$process) {
            return back()->withErrors([
                'password' => 'Gagal mengubah kata sandi.',
            ]);
        }

        $request->session()->flash('passwordChangedSuccessfully', 'OK');

        return redirect()->route('user.change-password.edit');
    }
}
