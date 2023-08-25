<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Admin\UpdateProfileRequest;
use stdClass;

class ProfileController extends Controller
{
    public function index() {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['profile'] = DB::table('users')->select([
            'name', 'username', 'email', 'created_at', 'updated_at'
        ])
        ->where('id', '=', session('adminAuth')->id)
        ->where('role', '=', 'Administrator')
        ->first();

        $data['navbarActive'] = 'profile';

        return view('pages.admin.profile.index', $data);
    }

    public function edit() {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['user'] = DB::table('users')->select([
            'name', 'username', 'email'
        ])
        ->where('id', '=', session('adminAuth')->id)
        ->where('role', '=', 'Administrator')
        ->first();

        $data['navbarActive'] = 'profile';

        return view('pages.admin.profile.edit', $data);
    }

    public function update(UpdateProfileRequest $request) {
        $input = $request->safe()->except(['password']);

        $input['updated_at'] = time();

        $process = DB::table('users')
                        ->where('id', '=', session('adminAuth')->id)
                        ->update($input);

        if (!$process) {
            return back()->withErrors([
                'email' => 'Gagal mengubah profil.',
            ]);
        }

        $newSession = new stdClass;

        $newSession->id = session('adminAuth')->id;
        $newSession->username = $input['username'];
        $newSession->name = $input['name'];

        $request->session()->put('adminAuth', $newSession);
        
        $request->session()->flash('profileChangedSuccessfully', 'OK');

        return redirect()->route('admin.profile.index');
    }
}
