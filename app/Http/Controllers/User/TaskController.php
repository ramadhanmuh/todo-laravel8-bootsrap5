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
    public function index()
    {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['navbarActive'] = 'tasks';

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
