<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use DateTime;
use DateTimeZone;
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

    public function getTotalTodayTasks(Request $request) {
        $date = $request->date;
        
        if (empty($date)) {
            return response()->json(['total' => 0]);
        }

        $dateExploded = explode('-', $date);

        if (count($dateExploded) !== 3 || !checkdate(intval($dateExploded[1]), intval($dateExploded[2]), intval($dateExploded[0]))) {
            return response()->json(['total' => 0]);
        }

        try {
            $timezone = new DateTimeZone($request->timezone);
        } catch (\Throwable $th) {
            $timezone = new DateTimeZone('UTC');
        }

        $startUnix = new DateTime($date . ' 00:00:00', $timezone);

        $startUnix = $startUnix->getTimestamp();

        $endUnix = new DateTime($date . ' 23:59:59', $timezone);

        $endUnix = $endUnix->getTimestamp();

        $total = DB::table('tasks')->whereBetween('created_at', [
            $startUnix, $endUnix
        ])->count();
        
        return response()->json(['total' => $total]);
    }

    public function getCurrentMonthTotalTasks(Request $request) {
        $date = $request->date;

        if (empty($date)) {
            return response()->json(['total' => 0]);
        }

        $dateExploded = explode('-', $date);

        if (count($dateExploded) < 3 || !checkdate(intval($dateExploded[1]), intval($dateExploded[2]), intval($dateExploded[0]))) {
            return response()->json(['total' => 0]);
        }

        try {
            $timezone = new DateTimeZone($request->timezone);
        } catch (\Throwable $th) {
            $timezone = new DateTimeZone('UTC');
        }

        $startDate = $dateExploded[0] . '-' . $dateExploded[1] . '-01' . ' 00:00:00';

        $startUnix = new DateTime($startDate, $timezone);

        $startUnix = $startUnix->getTimestamp();

        if (checkdate(intval($dateExploded[1]), 31, intval($dateExploded[0]))) {
            $endDate = $dateExploded[0] . '-' . $dateExploded[1] . '-31 23:59:59';
        } else if (checkdate(intval($dateExploded[1]), 30, intval($dateExploded[0]))) {
            $endDate = $dateExploded[0] . '-' . $dateExploded[1] . '-30 23:59:59';
        } else if (checkdate(intval($dateExploded[1]), 29, intval($dateExploded[0]))) {
            $endDate = $dateExploded[0] . '-' . $dateExploded[1] . '-29 23:59:59';
        } else {
            $endDate = $dateExploded[0] . '-' . $dateExploded[1] . '-28 23:59:59';
        }

        $endUnix = new DateTime($endDate, $timezone);

        $endUnix = $endUnix->getTimestamp();

        $total = DB::table('tasks')->whereBetween('created_at', [
            $startUnix, $endUnix
        ])->count();
        
        return response()->json(['total' => $total]);
    }
}
