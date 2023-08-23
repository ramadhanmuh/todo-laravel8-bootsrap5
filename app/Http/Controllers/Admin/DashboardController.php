<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['navbarActive'] = 'dashboard';

        $data['totalUsers'] = DB::table('users')->where('role', '=', 'User')
                                                ->whereNotNull('email_verified_at')
                                                ->count();

        $data['totalTasks'] = DB::table('tasks')->count();

        $data['totalAdmins'] = DB::table('users')->where('role', '=', 'Administrator')
                                                    ->whereNotNull('email_verified_at')
                                                    ->count();
    
        $data['totalUsers'] = number_format($data['totalUsers'], 0, ',', '.');
        $data['totalTasks'] = number_format($data['totalTasks'], 0, ',', '.');
        $data['totalAdmins'] = number_format($data['totalAdmins'], 0, ',', '.');

        return view('pages.admin.dashboard', $data);
    }

    // function FunctionName() {
        
    // }
}
