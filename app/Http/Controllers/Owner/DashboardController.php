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
        
        $year = intval($date[0]);
        $month = intval($date[1]);
        $day = intval($date[2]);

        if (count($date) !== 3 || !checkdate($month, $day, $year)) {
            return response()->json(['total' => 0]); 
        }

        if ($month < 10) {
            $month = '0' . $month;
        }

        if ($day < 10) {
            $day = '0' . $day;
        }

        $date = $year . '-' . $month . '-' . $day;

        $timezone = $request->timezone;

        if (empty($timezone)) {
            $timezone = 'UTC';
        }

        try {
            $timezone = new DateTimeZone($timezone);
        } catch (\Throwable $th) {
            $timezone = new DateTimeZone('UTC');
        }

        $startDate = new DateTime($date . ' 00:00:00', $timezone);

        $startDate = $startDate->getTimestamp();

        $endDate = new DateTime($date . ' 23:59:59');

        $endDate = $endDate->getTimestamp();

        $test = new DateTime($date . ' 00:00:00', $timezone);

        $test->setTimezone(new DateTimeZone('UTC'));

        return response()->json([
            'total' => DB::table('tasks')->whereBetween('created_at', [
                $startDate, $endDate
            ])->count()
        ]);
    }

    public function totalTasksThisMonth(Request $request) {
        $date = explode('-', $request->date);
        
        $year = intval($date[0]);
        $month = intval($date[1]);
        $day = intval($date[2]);

        if (count($date) !== 3 || !checkdate($month, $day, $year)) {
            return response()->json(['total' => 0]); 
        }

        if ($month < 10) {
            $month = '0' . $month;
        }

        if ($day < 10) {
            $day = '0' . $day;
        }

        $date = $year . '-' . $month . '-' . $day;

        $timezone = $request->timezone;

        if (empty($timezone)) {
            $timezone = 'UTC';
        }

        try {
            $timezone = new DateTimeZone($timezone);
        } catch (\Throwable $th) {
            $timezone = new DateTimeZone('UTC');
        }

        $startDate = new DateTime($year . '-' . $month . '-01' . ' 00:00:00', $timezone);

        $startDate = $startDate->getTimestamp();
        
        for ($i=28; $i < 32; $i++) { 
            if (checkdate($month, $i, $year)) {
                $endDate = new DateTime($date[0] . '-' . $date[1] . '-' . $i . ' 23:59:59', $timezone);
        
                $endDate = $endDate->getTimestamp();
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
        
        $year = intval($date[0]);
        $month = intval($date[1]);
        $day = intval($date[2]);

        if (count($date) !== 3 || !checkdate($month, $day, $year)) {
            return response()->json(['total' => 0]); 
        }

        if ($month < 10) {
            $month = '0' . $month;
        }

        if ($day < 10) {
            $day = '0' . $day;
        }

        $date = $year . '-' . $month . '-' . $day;

        $timezone = $request->timezone;

        if (empty($timezone)) {
            $timezone = 'UTC';
        }

        try {
            $timezone = new DateTimeZone($request->timezone);
        } catch (\Throwable $th) {
            $timezone = new DateTimeZone('UTC');
        }

        $startDate = new DateTime($year . '-01-01 00:00:00', $timezone);

        $startDate = $startDate->getTimestamp();

        $endDate = new DateTime($year . '-12-31 23:59:59', $timezone);

        $endDate = $endDate->getTimestamp();

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

        if (count($date) !== 3) {
            return response()->json($response);
        }

        $year = intval($date[0]);
        $month = intval($date[1]);
        $day = intval($date[2]);

        if (!checkdate($month, $day, $year)) {
            return response()->json($response);
        }

        if ($month < 10) {
            $month = '0' . $month;
        }

        if ($day < 10) {
            $day = '0' . $day;
        }

        $date = $year . '-' . $month . '-' . $day;

        try {
            $timezone = new DateTimeZone($request->timezone);
        } catch (\Throwable $th) {
            $timezone = new DateTimeZone('UTC');
        }

        $selectRaw = 'id';

        foreach ($response as $key => $value) {
            $startDate = new DateTime($date . ' ' . $value['startTime'], $timezone);

            $startDate = $startDate->getTimestamp();

            $endDate = new DateTime($date . ' ' . $value['endTime'], $timezone);

            $endDate = $endDate->getTimestamp();
            
            $selectRaw .= ',(SELECT COUNT(*) FROM tasks WHERE created_at BETWEEN ' . $startDate . ' AND ' . $endDate . ') as "' . $value['startTime'] . '"';
        }

        try {
            $data = DB::table('tasks')->select(DB::raw($selectRaw))->first();
        } catch (\Throwable $th) {
            $data = null;
        }

        if (empty($data)) {
            foreach ($response as $key => $value) {
                $response[$key]['total'] = 0;
            }
        } else {
            foreach ($response as $key => $value) {
                foreach ($data as $key2 => $value2) {
                    if ($value['startTime'] === $key2) {
                        $response[$key]['total'] = $value2;
                    }
                }
            }
        }

        return response()->json($response);
    }

    public function totalDailyTasks(Request $request) {
        $response = [0, 0, 0, 0, 0, 0, 0];

        $date = explode('-', $request->date);

        $year = intval($date[0]);
        $month = intval($date[1]);
        $day = intval($date[2]);

        if (count($date) !== 3 || !checkdate($month, $day, $year)) {
            return response()->json($response); 
        }

        if ($month < 10) {
            $month = '0' . $month;
        }

        if ($day < 10) {
            $day = '0' . $day;
        }

        $date = $year . '-' . $month . '-' . $day;

        try {
            $timezone = new DateTimeZone($request->timezone);
        } catch (\Throwable $th) {
            $timezone = new DateTimeZone('UTC');
        }

        $dateTimeDate = new DateTime($date . ' 00:00:00', $timezone);

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

        $data = DB::table('tasks')->select(DB::raw($selectRaw))->first();

        if (empty($data)) {
            return response()->json($response);
        }

        foreach ($data as $key => $value) {
            if ($key === 'id') {
                continue;
            }

            $response[$key] = $value;
        }

        return response()->json($response);
    }

    public function totalMonthlyTasks(Request $request) {
        $response = [
            0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
        ];

        $input = [
            'date' => $request->date,
            'timezone' => $request->timezone
        ];

        $dateArray = explode('-', $input['date']);

        if (count($dateArray) !== 3) {
            return response()->json($response);
        }

        $year = intval($dateArray[0]);
        $month = intval($dateArray[1]);
        $day = intval($dateArray[2]);

        if (!checkdate($month, $day, $year)) {
            return response()->json($response);
        }

        try {
            $timezone = new DateTimeZone($input['timezone']);
        } catch (\Throwable $th) {
            $timezone = new DateTimeZone('UTC');
        }

        $unixList = [];

        for ($i=0; $i < 12; $i++) { 
            $unixMonth = $i + 1;

            $startDate = new DateTime($year . '-' . $unixMonth . '-01 00:00:00', $timezone);

            $endDate = new DateTime($startDate->format('Y-m-t') . ' 23:59:59', $timezone);

            $unixList[$i] = [
                'startTime' => $startDate->getTimestamp(),
                'endTime' => $endDate->getTimestamp()
            ];
        }

        $selectRaw = '';

        foreach ($unixList as $key => $value) {
            if ($key === 11) {
                $selectRaw .= '(SELECT COUNT(*) FROM tasks WHERE created_at BETWEEN ' . $value['startTime'] . ' AND ' . $value['endTime'] . ') as "month_' . ($key + 1) . '"';
            } else {
                $selectRaw .= '(SELECT COUNT(*) FROM tasks WHERE created_at BETWEEN ' . $value['startTime'] . ' AND ' . $value['endTime'] . ') as "month_' . ($key + 1) . '",';
            }
        }

        return response()->json(
            DB::table('tasks')->select(DB::raw($selectRaw))->first()
        );
    }

    public function userGrowth(Request $request) {
        $defaultYear = date('Y');

        $response = [
            [
                'year' => intval($defaultYear) - 4,
                'total' => 0
            ],
            [
                'year' => intval($defaultYear) - 3,
                'total' => 0
            ],
            [
                'year' => intval($defaultYear) - 2,
                'total' => 0
            ],
            [
                'year' => intval($defaultYear) - 1,
                'total' => 0
            ],
            [
                'year' => intval($defaultYear),
                'total' => 0
            ]
        ];

        $input = [
            'date' => $request->date,
            'timezone' => $request->timezone
        ];

        $dateArray = explode('-', $input['date']);

        if (count($dateArray) !== 3) {
            return response()->json($response);
        }

        $year = intval($dateArray[0]);
        $month = intval($dateArray[1]);
        $day = intval($dateArray[2]);

        if (!checkdate($month, $day, $year)) {
            return response()->json($response);
        }

        try {
            $timezone = new DateTimeZone($input['timezone']);
        } catch (\Throwable $th) {
            $timezone = new DateTimeZone('UTC');
        }

        $previousYearLimit = $year - 4;

        $endTimes = [];

        $endTimesIndex = 0;

        for ($i=$previousYearLimit; $i <= $year; $i++) {
            $endTimes[$endTimesIndex] = [
                'year' => $i,
                'unix' => new DateTime($i . '-12-31 23:59:59', $timezone)
            ];

            $endTimes[$endTimesIndex]['unix'] = $endTimes[$endTimesIndex]['unix']->getTimestamp();
            $endTimesIndex++;
        }

        $selectRaw = '';

        foreach ($endTimes as $key => $value) {
            if ($key === 4) {
                $selectRaw .= '(SELECT COUNT(*) FROM users WHERE email_verified_at IS NOT NULL AND role="User" AND created_at BETWEEN 0 AND ' . $value['unix'] . ') as "year_' . $value['year'] . '"';
            } else {
                $selectRaw .= '(SELECT COUNT(*) FROM users WHERE email_verified_at IS NOT NULL AND role="User" AND created_at BETWEEN 0 AND ' . $value['unix'] . ') as "year_' . $value['year'] . '",';
            }
        }

        $data = DB::table('users')->select(DB::raw($selectRaw))
                                    ->first();

        $response = [];

        foreach ($data as $key => $value) {
            $response[] = [
                'year' => intval(explode('_', $key)[1]),
                'total' => $value
            ];
        }

        return response()->json($response);
    }
}
