<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd(date('Y-m-d H:i:s', 1692537522), strtotime('2023-08-20 13:18:42'));
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['navbarActive'] = 'tasks';

        $data['input'] = [
            'page' => intval($request->page),
            'year' => $request->year,
            'month' => $request->month,
            'day' => $request->day
        ];

        if ($data['input']['page'] < 1) {
            $data['input']['page'] = 1;
        }

        if ($data['input']['page'] > 500) {
            $data['input']['page'] = 500;
        }

        if (empty($data['input']['year']) && ($data['input']['month'] || $data['input']['day'])) {
            $lastData = DB::table('tasks')
                            ->where('user_id', '=', $request->get('userAuth')->id)
                            ->orderByDesc('created_at')
                            ->first();

            $data['input']['year'] = date('Y', $lastData->created_at);
        }

        if (empty($data['input']['month']) && $data['input']['day']) {
            if (!isset($lastData)) {
                $lastData = DB::table('tasks')
                                ->where('user_id', '=', $request->get('userAuth')->id)
                                ->orderByDesc('created_at')
                                ->first();
            }

            $data['input']['month'] = date('m', $lastData->created_at);
        }

        if ($data['input']['year'] && empty($data['input']['month']) && empty($data['input']['day'])) {
            $startTime = intval(strtotime($data['input']['year'] . '-01-01 00:00:00'));
            $lastDay = date('t', strtotime($data['input']['year'] . '-12-01 23:59:59'));
            $endTime = intval(strtotime($data['input']['year'] . '-12-' . $lastDay));
        }

        if ($data['input']['year'] && $data['input']['month'] && empty($data['input']['day'])) {
            $startTime = intval(strtotime($data['input']['year'] . '-' . $data['input']['month'] . '-01'));
            $lastDay = date('t', strtotime($data['input']['year'] . '-' . $data['input']['month'] . '-01'));
            $endTime = intval(strtotime($data['input']['year'] . '-' . $data['input']['month'] . '-' . $lastDay));
        }

        if ($data['input']['year'] && $data['input']['month'] && $data['input']['day']) {
            $startTime = intval(strtotime($data['input']['year'] . '-' . $data['input']['month'] . '-' . $data['input']['day']));
            $endTime = intval(strtotime($data['input']['year'] . '-' . $data['input']['month'] . '-' . $data['input']['day']));
        }

        if (isset($startTime)) {
            $startTime -= 1;
            $endTime += 1;
        }
        
        $data['totalItems'] = DB::table('tasks')
                            ->where('user_id', '=', $request->get('userAuth')->id);
        
        if (isset($startTime)) {
            $data['totalItems'] = $data['totalItems']->whereBetween('created_at', [$startTime, $endTime])
                                            ->count();
        } else {
            $data['totalItems'] = $data['totalItems']->count();
        }

        $data['totalPages'] = intval(ceil($data['totalItems'] / 12));

        $data['items'] = DB::table('tasks')
                            ->select('id', 'title', 'description')
                            ->where('user_id', '=', $request->get('userAuth')->id);

        if (isset($startTime)) {
            $data['items'] = $data['items']->whereBetween('created_at', [$startTime, $endTime]);
        }

        $offset = $data['input']['page'] > 1 ? ($data['input']['page'] * 12) - 12 : 0;

        $data['items'] = $data['items']->orderByDesc('created_at')
                                        ->offset($offset)
                                        ->limit(12)
                                        ->get();

        // dd($startTime, $endTime);

        return view('pages.user.task.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['navbarActive'] = 'tasks';

        return view('pages.user.task.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    {
        if (empty($request->start_date)) {
            $start_time = null;
            $end_time = null;
        } else {
            $start_time = $request->start_date;

            if (empty($request->start_time)) {
                $start_time .= ' 00:00:00';
            } else {
                $start_time .= ' ' . $request->start_time . ':00';
            }

            $start_time = strtotime($start_time);

            if (!empty($request->end_date)) {
                $end_time = $request->end_date;
            }

            if (empty($request->end_time)) {
                $end_time .= ' 00:00:00';
            } else {
                $end_time .= ' ' . $request->end_time . ':00';
            }
            
            $end_time = strtotime($end_time);

            if (!empty($end_time) && $start_time >= $end_time) {
                return back()->withErrors([
                    'end_time' => 'Waktu selesai harus lebih dari waktu mulai.',
                ])->withInput($request->all());
            }
        }

        $input = [
            'id' => $request->id,
            'user_id' => $request->get('userAuth')->id,
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'created_at' => time(),
            'updated_at' => null
        ];

        $process = DB::table('tasks')->insert($input);

        if ($process) {
            $request->session()->flash('taskProcessSuccessfully', 'Berhasil menambah tugas.');
        } else {
            $request->session()->flash('taskProcessFailed', 'Gagal menambah tugas.');
        }

        return redirect()->route('user.tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
