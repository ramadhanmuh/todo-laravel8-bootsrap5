<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
        ->first();

        $data['navbarActive'] = 'profile';

        return view('pages.user.profile.index', $data);
    }
}
