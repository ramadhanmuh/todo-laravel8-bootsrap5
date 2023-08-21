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
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['navbarActive'] = 'tasks';

        $data['input'] = $request->all();

        if (empty($data['input'])) {
            $data['total'] = DB::table('tasks')->where('user_id', '=', $request->get('userAuth')->id)
                                                ->count();

            $data['input'] = [
                'page' => 1,
                'page_total' => ceil($data['total'] / 12),
                'year' => null,
                'month' => null,
                'day' => null
            ];

            $data['items'] = DB::table('tasks')->where('user_id', '=', $request->get('userAuth')->id)
                                                ->orderBy('created_at', 'desc')
                                                ->offset(0)
                                                ->limit(12)
                                                ->get();
        } else {
            $data['input']['page'] = $request->page;

            if (empty($data['input']['page'])) {
                $data['input']['page'] = 1;
            } else {
                $data['input']['page'] = intval($data['input']['page']);
            }

            $data['input']['offset'] = $data['input']['page'] > 1 ? ($data['input']['page'] * 12) - 12 : 0;
            
            if (isset($request->month) || isset($request->day)) {
                if (empty($request->year)) {
                    $lastData = DB::table('tasks')->select('created_at')
                                                    ->where('user_id', '=', $request->get('userAuth')->id)
                                                    ->orderByDesc('created_at')
                                                    ->first();
    
                    $data['input']['year'] = intval(date('Y', $lastData->created_at));
                } else {
                    $data['input']['year'] = intval($data['input']['year']);
                }
            } else {
                if (strtotime($request->year)) {
                    $data['input']['year'] = intval($request->year);
                } else {
                    $lastData = DB::table('tasks')->select('created_at')
                                                    ->where('user_id', '=', $request->get('userAuth')->id)
                                                    ->orderByDesc('created_at')
                                                    ->first();
    
                    $data['input']['year'] = intval(date('Y', $lastData->created_at));
                }
            }

            if (isset($request->day)) {
                if (empty($request->month)) {
                    if (empty($lastData)) {
                        $lastData = DB::table('tasks')->select('created_at')
                                                        ->where('user_id', '=', $request->get('userAuth')->id)
                                                        ->orderByDesc('created_at')
                                                        ->first();
                    }
    
                    $data['input']['month'] = intval(date('m', $lastData->created_at));
                } else {
                    $data['input']['month'] = intval($data['input']['month']);
                }
            } else {
                if (strtotime($data['input']['year'] . '-' . $request->month)) {
                    $data['input']['month'] = intval($request->month);
                } else {
                    $data['input']['month'] = null;
                }
            }

            if (empty($request->day)) {    
                $data['input']['day'] = null;
            } else {
                $data['input']['day'] = intval($request->day);

                if (!$data['input']['day']) {
                    $data['input']['day'] = null;
                }
            }

            if (!empty($data['input']['year']) && empty($data['input']['month']) && empty($data['input']['day'])) {
                $stringYear = strval($data['input']['year']);

                $yearMonthTime = strtotime($stringYear . '-12');

                $lastDay = date('t', $yearMonthTime);

                $lastDateTime = strtotime($stringYear . '-12-' . $lastDay . ' 23:59:59');

                $startDateTime = strtotime($stringYear . '-01-01' . ' 00:00:00');

                $data['items'] = DB::table('tasks')->select('id', 'title', 'description')
                                                    ->where('user_id', '=', $request->get('userAuth')->id)
                                                    ->whereBetween('created_at', [$startDateTime - 1, $lastDateTime + 1])
                                                    ->orderByDesc('created_at')
                                                    ->offset($data['input']['offset'])
                                                    ->limit(12)
                                                    ->get();
            }
        }

        dd($data['items']);

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
