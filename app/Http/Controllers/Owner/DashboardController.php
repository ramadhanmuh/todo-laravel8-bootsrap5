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

        $timezone = $request->timezone;

        if (empty($timezone)) {
            $timezone = 'UTC';
        }

        $startDate = new DateTime($request->date . ' 00:00:00', new DateTimeZone($timezone));

        $startDate->setTimezone(new DateTimeZone('UTC'));

        $startDate = strtotime($startDate->format('Y-m-d H:i:s'));

        $endDate = new DateTime($request->date . ' 23:59:59');

        $endDate->setTimezone(new DateTimeZone('UTC'));

        $endDate = strtotime($endDate->format('Y-m-d H:i:s'));

        $test = new DateTime($request->date . ' 00:00:00', new DateTimeZone($timezone));

        $test->setTimezone(new DateTimeZone('UTC'));

        return response()->json([
            'total' => DB::table('tasks')->whereBetween('created_at', [
                $startDate, $endDate
            ])->count(),
            'test' => $test
        ]);
    }

    public function totalTasksThisMonth(Request $request) {
        $date = explode('-', $request->date);
        
        if (count($date) !== 3 || !checkdate($date[1], $date[2], $date[0])) {
            return response()->json([
                'total' => 0
            ]); 
        }

        $timezone = $request->timezone;

        if (empty($timezone)) {
            $timezone = 'UTC';
        }

        $startDate = new DateTime($date[0] . '-' . $date[1] . '-01' . ' 00:00:00', new DateTimeZone($timezone));

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
                $startDate, $endDate
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

        $timezone = $request->timezone;

        if (empty($timezone)) {
            $timezone = 'UTC';
        }

        $startDate = new DateTime($date[0] . '-01-01 00:00:00', new DateTimeZone($timezone));

        $startDate->setTimezone(new DateTimeZone('UTC'));

        $startDate = strtotime($startDate->format('Y-m-d H:i:s'));

        $endDate = new DateTime($date[0] . '-12-31 23:59:59');

        $endDate->setTimezone(new DateTimeZone('UTC'));

        $endDate = strtotime($endDate->format('Y-m-d H:i:s'));

        return response()->json([
            'total' => DB::table('tasks')->whereBetween('created_at', [
                $startDate, $endDate
            ])->count()
        ]);
    }

    public function totalTasksPerHour(Request $request) {
        $response = [
            [
                'startTime' => '00:00:00',
                'endTime' => '05:59:59',
                'total' => 0
            ],
            [
                'startTime' => '06:00:00',
                'endTime' => '11:59:59',
                'total' => 0
            ],
            [
                'startTime' => '12:00:00',
                'endTime' => '17:59:59',
                'total' => 0
            ],
            [
                'startTime' => '18:00:00',
                'endTime' => '23:59:59',
                'total' => 0
            ],
        ];

        $date = explode('-', $request->date);

        if (count($date) !== 3 || !checkdate($date[1], $date[2], $date[0])) {
            return response()->json($response); 
        }

        try {
            $timezone = new DateTimeZone($request->timezone);
        } catch (\Throwable $th) {
            $timezone = new DateTimeZone('UTC');
        }

        $selectRaw = 'id';

        foreach ($response as $key => $value) {
            $startDate = new DateTime($request->date . ' ' . $value['startTime'], $timezone);

            $startDate = $startDate->getTimestamp();

            $endDate = new DateTime($request->date . ' ' . $value['endTime'], $timezone);

            $endDate = $endDate->getTimestamp();

            $selectRaw .= ',(SELECT COUNT(*) FROM tasks WHERE created_at BETWEEN ' . $startDate . ' AND ' . $endDate . ') as "' . $value['startTime'] . '"';
        }

        $data = DB::table('tasks')->select(DB::raw($selectRaw))->limit(1)->get();

        if (empty($data)) {
            return response()->json($response);
        }

        foreach ($response as $key => $value) {
            foreach ($data[0] as $key2 => $value2) {
                if ($value['startTime'] === $key2) {
                    $response[$key]['total'] = $value2;
                }
            }
        }

        return response()->json($response);
    }

    public function totalDailyTasks(Request $request) {
        $response = [0, 0, 0, 0, 0, 0, 0];

        $date = explode('-', $request->date);

        if (count($date) !== 3 || !checkdate($date[1], $date[2], $date[0])) {
            return response()->json($response); 
        }

        try {
            $timezone = new DateTimeZone($request->timezone);
        } catch (\Throwable $th) {
            $timezone = new DateTimeZone('UTC');
        }

        $dateTimeDate = new DateTime($request->date . ' 00:00:00', $timezone);

        $numberOfDay = $dateTimeDate->format('N');

        $unixDate = $dateTimeDate->getTimestamp();

        $unixList = [];

        if ($numberOfDay === 1) {
            $unixList[] = [
                'startTime' => $unixDate,
                'endTime' => $unixDate + 86399
            ];

            for ($i=1; $i < 7; $i++) { 
                $startTime = $unixList[0] + ($i * 86400);

                $unixList[] = [
                    'startTime' => $startTime,
                    'endTime' => $startTime + 86399
                ];
            }
        } else {
            $unixList[$numberOfDay - 1] = [
                'startTime' => $unixDate,
                'endTime' => $unixDate + 86399
            ];

            $totalBeforeDay = 0;
            $totalNextDay = 0;

            for ($i=$numberOfDay; $i > 1; $i--) { 
                $totalBeforeDay++;
            }

            for ($i=$numberOfDay; $i < 7; $i++) { 
                $totalNextDay++;
            }

            for ($i=0; $i < $totalBeforeDay; $i++) { 
                $startTime = $unixDate - (($totalBeforeDay - $i) * 86400);

                $unixList[$i] = [
                    'startTime' => $startTime,
                    'endTime' => $startTime + 86399
                ];
            }

            for ($i=0; $i < $totalNextDay; $i++) { 
                $startTime = $unixDate + (($i + 1) * 86400);

                $unixList[$i + $numberOfDay] = [
                    'startTime' => $startTime,
                    'endTime' => $startTime + 86399
                ];
            }
        }

        ksort($unixList, SORT_NUMERIC);

        $selectRaw = 'id';

        foreach ($unixList as $key => $value) {
            $selectRaw .= ',(SELECT COUNT(*) FROM tasks WHERE created_at BETWEEN ' . $value['startTime'] . ' AND ' . $value['endTime'] . ') as "' . $key . '"';
        }

        $data = DB::table('tasks')->select(DB::raw($selectRaw))->limit(1)->get();

        if (empty($data)) {
            return response()->json($response);
        }

        foreach ($data[0] as $key => $value) {
            if ($key === 'id') {
                continue;
            }

            $response[$key] = $value;
        }

        return response()->json($response);
    }
}
