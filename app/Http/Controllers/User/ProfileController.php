<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\User\UpdateProfileRequest;
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
        ->where('id', '=', session('userAuth')->id)
        ->where('role', '=', 'User')
        ->first();

        $data['navbarActive'] = 'profile';

        return view('pages.user.profile.index', $data);
    }

    public function edit() {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['user'] = DB::table('users')->select([
            'name', 'username', 'email'
        ])
        ->where('id', '=', session('userAuth')->id)
        ->where('role', '=', 'User')
        ->first();

        $data['navbarActive'] = 'profile';

        return view('pages.user.profile.edit', $data);
    }

    public function update(UpdateProfileRequest $request) {
        $input = $request->validated();

        $input['updated_at'] = time();

        $process = DB::table('users')
                        ->where('role', '=', 'User')
                        ->where('id', '=', session('userAuth')->id)
                        ->update($input);

        if (!$process) {
            return back()->withErrors([
                'email' => 'Gagal mengubah profil.',
            ]);
        }

        $newSession = new stdClass;

        $newSession->id = session('userAuth')->id;
        $newSession->username = $input['username'];
        $newSession->name = $input['name'];

        $request->session()->put('userAuth', $newSession);
        
        $request->session()->flash('profileChangedSuccessfully', 'OK');

        return redirect()->route('user.profile.index');
    }
}
