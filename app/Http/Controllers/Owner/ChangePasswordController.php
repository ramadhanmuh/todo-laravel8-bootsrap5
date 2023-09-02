<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\ChangePasswordRequest;
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

        return view('pages.owner.change-password.edit', $data);
    }

    public function update(ChangePasswordRequest $request) {
        $process = DB::table('users')->where('id', '=', session('ownerAuth')->id)
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

        return redirect()->route('owner.change-password.edit');
    }
}
