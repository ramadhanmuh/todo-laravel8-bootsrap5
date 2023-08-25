<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangePasswordRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function edit() {
        // dd(Hash::make('admin'));
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['navbarActive'] = 'change-password';

        return view('pages.admin.change-password.edit', $data);
    }

    public function update(ChangePasswordRequest $request) {
        $process = DB::table('users')->where('id', '=', session('userAuth')->id)
                                        ->update([
                                            'password' => Hash::make($request->password),
                                            'updated_at' => time()
                                        ]);

        if (!$process) {
            return back()->withErrors([
                'password' => 'Gagal mengubah kata sandi.',
            ]);
        }

        $request->session()->flash('passwordChangedSuccessfully', 'OK');

        return redirect()->route('admin.change-password.edit');
    }
}
