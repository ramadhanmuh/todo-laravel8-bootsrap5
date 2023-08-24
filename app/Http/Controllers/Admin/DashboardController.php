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

        $dateExploded = explode($date, '-');

        if (count($dateExploded) < 3 || !checkdate(intval($dateExploded[1]), intval($dateExploded[2]), intval($dateExploded[0]))) {
            return response()->json(['total' => 0]);
        }

        $startUnix = new DateTime($date . ' 00:00:00');

        $startUnix->setTimezone(new DateTimeZone('UTC'));

        $startUnix = strtotime($startUnix->format('Y-m-d H:i:s'));

        $endUnix = new DateTime($date . ' 23:59:59');

        $endUnix->setTimezone(new DateTimeZone('UTC'));

        $endUnix = strtotime($endUnix->format('Y-m-d H:i:s'));

        $total = DB::table('tasks')->whereBetween('created_at', [
            $startUnix - 1, $endUnix + 1
        ])->count();
        
        return response()->json(['total' => $total]);
    }

    public function getCurrentMonthTotalTasks(Request $request) {
        $date = $request->date;

        if (empty($date)) {
            return response()->json(['total' => 0]);
        }

        $dateExploded = explode($date, '-');

        if (count($dateExploded) < 3 || !checkdate(intval($dateExploded[1]), intval($dateExploded[2]), intval($dateExploded[0]))) {
            return response()->json(['total' => 0]);
        }

        $startDate = $dateExploded[0] . '-' . $dateExploded[1] . '-01' . ' 00:00:00';

        $startUnix = new DateTime($startDate);

        $startUnix->setTimezone(new DateTimeZone('UTC'));

        $startUnix = strtotime($startUnix->format('Y-m-d H:i:s'));

        if (checkdate(intval($dateExploded[1]), 31, intval($dateExploded[0]))) {
            $endDate = $dateExploded[2] . '-' . $dateExploded[1] . '-31 23:59:59';
        }

        if (checkdate(intval($dateExploded[1]), 30, intval($dateExploded[0]))) {
            $endDate = $dateExploded[2] . '-' . $dateExploded[1] . '-30 23:59:59';
        }

        if (checkdate(intval($dateExploded[1]), 29, intval($dateExploded[0]))) {
            $endDate = $dateExploded[2] . '-' . $dateExploded[1] . '-29 23:59:59';
        }

        if (checkdate(intval($dateExploded[1]), 28, intval($dateExploded[0]))) {
            $endDate = $dateExploded[2] . '-' . $dateExploded[1] . '-28 23:59:59';
        }

        $endUnix = new DateTime($endDate);

        $endUnix->setTimezone(new DateTimeZone('UTC'));

        $endUnix = strtotime($endUnix->format('Y-m-d H:i:s'));

        $total = DB::table('tasks')->whereBetween('created_at', [
            $startUnix - 1, $endUnix + 1
        ])->count();
        
        return response()->json(['total' => $total]);
    }
}
