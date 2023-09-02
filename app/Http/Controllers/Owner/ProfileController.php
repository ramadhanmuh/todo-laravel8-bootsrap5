<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Owner\UpdateProfileRequest;
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
        ->where('id', '=', session('ownerAuth')->id)
        ->where('role', '=', 'Owner')
        ->first();

        $data['navbarActive'] = 'profile';

        return view('pages.owner.profile.index', $data);
    }

    public function edit() {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['user'] = DB::table('users')->select([
            'name', 'username', 'email'
        ])
        ->where('id', '=', session('ownerAuth')->id)
        ->where('role', '=', 'Owner')
        ->first();

        $data['navbarActive'] = 'profile';

        return view('pages.owner.profile.edit', $data);
    }

    public function update(UpdateProfileRequest $request) {
        $input = $request->safe()->except(['password']);

        $input['updated_at'] = time();

        $process = DB::table('users')
                        ->where('id', '=', session('ownerAuth')->id)
                        ->update($input);

        if (!$process) {
            return back()->withErrors([
                'email' => 'Gagal mengubah profil.',
            ]);
        }

        $newSession = new stdClass;

        $newSession->id = session('ownerAuth')->id;
        $newSession->username = $input['username'];
        $newSession->name = $input['name'];

        $request->session()->put('ownerAuth', $newSession);
        
        $request->session()->flash('profileChangedSuccessfully', 'OK');

        return redirect()->route('owner.profile.index');
    }
}
