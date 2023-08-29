<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateTimeZone;

class DashboardController extends Controller
{
    public function index() {
        // dd(date('w'));
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['navbarActive'] = 'dashboard';

        return view('pages.owner.dashboard', $data);
    }

    public function totalUsers() {
        return response()->json([
            'total' => DB::table('users')->where('role', '=', 'Users')
                                            ->whereNotNull('email_verified_at')
                                            ->count()
        ]);
    }

    public function totalAdministrators() {
        return response()->json([
            'total' => DB::table('users')->where('role', '=', 'Administrators')
                                            ->whereNotNull('email_verified_at')
                                            ->count()
        ]);
    }

    public function totalOwners() {
        return response()->json([
            'total' => DB::table('users')->where('role', '=', 'Owners')
                                            ->whereNotNull('email_verified_at')
                                            ->count()
        ]);
    }

    public function totalTasksToday(Request $request) {
        $date = explode('-', $request->date);
        
        if (count($date) !== 3 || !checkdate($date[1], $date[2], $date[0])) {
            return response()->json([
                'total' => 0
            ]); 
        }

        $startDate = new DateTime($request->date . ' 00:00:00');

        $startDate->setTimezone(new DateTimeZone('UTC'));

        $startDate = strtotime($startDate->format('Y-m-d H:i:s'));

        $endDate = new DateTime($request->date . ' 23:59:59');

        $endDate->setTimezone(new DateTimeZone('UTC'));

        $endDate = strtotime($endDate->format('Y-m-d H:i:s'));

        return response()->json([
            'total' => DB::table('tasks')->whereBetween('created_at', [
                $startDate - 1, $endDate + 1
            ])->count()
        ]);
    }

    public function totalTasksThisMonth(Request $request) {
        $date = explode('-', $request->date);
        
        if (count($date) !== 3 || !checkdate($date[1], $date[2], $date[0])) {
            return response()->json([
                'total' => 0
            ]); 
        }

        $startDate = new DateTime($date[0] . '-' . $date[1] . '-01' . ' 00:00:00');

        $startDate->setTimezone(new DateTimeZone('UTC'));

        $startDate = strtotime($startDate->format('Y-m-d H:i:s'));

        for ($i=28; $i < 32; $i++) { 
            if (checkdate($date[1], $i, $date[0])) {
                $endDate = new DateTime($date[0] . '-' . $date[1] . '-' . $i . ' 23:59:59');
        
                $endDate->setTimezone(new DateTimeZone('UTC'));
        
                $endDate = strtotime($endDate->format('Y-m-d H:i:s'));
            }
        }

        return response()->json([
            'total' => DB::table('tasks')->whereBetween('created_at', [
                $startDate - 1, $endDate + 1
            ])->count()
        ]);
    }

    public function totalTasksThisYear(Request $request) {
        $date = explode('-', $request->date);
        
        if (count($date) !== 3 || !checkdate($date[1], $date[2], $date[0])) {
            return response()->json([
                'total' => 0
            ]); 
        }

        $startDate = new DateTime($date[0] . '-01-01 00:00:00');

        $startDate->setTimezone(new DateTimeZone('UTC'));

        $startDate = strtotime($startDate->format('Y-m-d H:i:s'));

        $endDate = new DateTime($date[0] . '-12-31 23:59:59');

        $endDate->setTimezone(new DateTimeZone('UTC'));

        $endDate = strtotime($endDate->format('Y-m-d H:i:s'));

        return response()->json([
            'total' => DB::table('tasks')->whereBetween('created_at', [
                $startDate - 1, $endDate + 1
            ])->count()
        ]);
    }

    public function totalDailyTask(Request $request) {
        $date = explode('-', $request->date);

        if (count($date) !== 3 || !checkdate($date[1], $date[2], $date[0])) {
            return response()->json([
                'total' => [0, 0, 0, 0, 0, 0, 0]
            ]); 
        }


    }
}
