<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreTaskRequest;
use App\Http\Requests\User\UpdateTaskRequest;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use DateTimeZone;

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

        $listChanges = Cache::get('taskListChanges' . $request->get('userAuth')->id, null);

        $taskManaged = Cache::get('taskManaged' . $request->get('userAuth')->id, null);

        if ((empty($listChanges) && empty($taskManaged)) || (!empty($taskManaged) && $listChanges !== $taskManaged)) {
            if (empty($taskManaged)) {
                Cache::forever('taskListChanges' . $request->get('userAuth')->id, 1);
            } else {
                Cache::forever('taskListChanges' . $request->get('userAuth')->id, $taskManaged);
            }

            $data = $this->getListFromDatabase($data, $request);
        } else {
            $cache = Cache::get('taskList' . $request->get('userAuth')->id . json_encode($data['input']));

            if (empty($cache)) {
                $data = $this->getListFromDatabase($data, $request);
            } else {
                $data = $cache;
            }
        }

        Cache::forever('seeTaskList', $data['input']);

        if (empty($data['items']) && $data['input']['page'] > 1) {
            $data['input']['page'] = 1;
            
            return redirect()->route('user.tasks.index', $data['input']);
        }

        return view('pages.user.task.index', $data);
    }

    private function getListFromDatabase($data, $request) {
        if (empty($data['input']['year']) && ($data['input']['month'] || $data['input']['day'])) {
            $lastData = DB::table('tasks')
                            ->where('user_id', '=', $request->get('userAuth')->id)
                            ->orderByDesc('created_at')
                            ->first();

            $data['input']['year'] = date('Y', $lastData->created_at);
        }

        if (empty($data['input']['month']) && $data['input']['day']) {
            $data['input']['month'] = '1';
        }

        if (intval($data['input']['year']) > 9999) {
            $data['input']['year'] = '9999';
        }

        if (intval($data['input']['month']) > 12) {
            $data['input']['month'] = '12';
        }

        if ($data['input']['year'] && empty($data['input']['month']) && empty($data['input']['day'])) {
            $newDateTime = new DateTime($data['input']['year'] . '-01-01 00:00:00');
            $newDateTime->setTimezone(new DateTimeZone('UTC'));
            $startTime = intval(strtotime($newDateTime->format('Y-m-d H:i:s')));

            $lastDay = date('t', strtotime($data['input']['year'] . '-12-01 23:59:59'));

            $newDateTime = new DateTime($data['input']['year'] . '-12-' . $lastDay);
            $newDateTime->setTimezone(new DateTimeZone('UTC'));
            $endTime = intval(strtotime($newDateTime->format('Y-m-d H:i:s')));
        }

        if ($data['input']['year'] && $data['input']['month'] && empty($data['input']['day'])) {
            $newDateTime = new DateTime($data['input']['year'] . '-' . $data['input']['month'] . '-01 00:00:00');
            $newDateTime->setTimezone(new DateTimeZone('UTC'));
            $startTime = intval(strtotime($newDateTime->format('Y-m-d H:i:s')));

            $lastDay = date('t', strtotime($data['input']['year'] . '-' . $data['input']['month'] . '-01'));

            $newDateTime = new DateTime($data['input']['year'] . '-' . $data['input']['month'] . '-' . $lastDay . ' 23:59:59');
            $newDateTime->setTimezone(new DateTimeZone('UTC'));
            $endTime = intval(strtotime($newDateTime->format('Y-m-d H:i:s')));
        }

        if ($data['input']['year'] && $data['input']['month'] && $data['input']['day']) {
            if (!checkdate(intval($data['input']['month']), intval($data['input']['day']), intval($data['input']['year']))) {
                $data['input']['day'] = '1';
            }

            $newDateTime = new DateTime($data['input']['year'] . '-' . $data['input']['month'] . '-' . $data['input']['day'] . ' 00:00:00');
            $newDateTime->setTimezone(new DateTimeZone('UTC'));
            $startTime = intval(strtotime($newDateTime->format('Y-m-d H:i:s')));

            $newDateTime = new DateTime($data['input']['year'] . '-' . $data['input']['month'] . '-' . $data['input']['day'] . ' 23:59:59');
            $newDateTime->setTimezone(new DateTimeZone('UTC'));
            $endTime = intval(strtotime($newDateTime->format('Y-m-d H:i:s')));
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

        if ($data['totalPages'] > 500) {
            $data['totalPages'] = 500;
        }

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

        Cache::forever('taskList' . $request->get('userAuth')->id . json_encode($data['input']), $data);

        return $data;
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
        $errors = [];

        $start_time = $request->start_date;

        if (!empty($start_time)) {
            if (empty($request->start_time)) {
                $start_time .= ' 00:00:00';
            } else {
                $start_time .= ' ' . $request->start_time;
            }

            $start_time = strtotime($start_time);
        }

        if ($start_time === false) {
            $errors['start_time'] = 'The Start Time could not be created.';
        }

        $end_time = $request->end_date;

        if (!empty($end_time)) {
            if (empty($request->end_time)) {
                $end_time .= ' 00:00:00';
            } else {
                $end_time .= ' ' . $request->end_time;
            }

            $end_time = strtotime($end_time);
        }

        if ($end_time === false) {
            $errors['end_time'] = 'The End Time could not be created.';
        }

        if ((!empty($start_time) && !empty($end_time)) && ($start_time > $end_time)) {
            $errors['end_time'] = 'The Start Time needs to be more than equal to the End Time.';
        }

        if (count($errors) > 0) {
            return back()->withInput($request->all())->withErrors($errors);
        }

        $currentTime = time();

        $input = [
            'id' => $request->id,
            'user_id' => $request->get('userAuth')->id,
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'created_at' => $currentTime,
            'updated_at' => null
        ];

        $process = DB::table('tasks')->insert($input);

        if ($process) {
            $request->session()->flash('taskProcessSuccessfully', 'Berhasil menambah tugas.');
            Cache::forever('taskManaged' . $request->get('userAuth')->id, $currentTime);
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
    public function show($id, Request $request)
    {
        $data['item'] = DB::table('tasks')
                            ->select([
                                'id', 'title', 'description', 'start_time',
                                'end_time', 'created_at', 'updated_at'
                            ])
                            ->where('user_id', '=', $request->get('userAuth')->id)
                            ->where('id', '=', $id)
                            ->first();

        if (empty($data['item'])) {
            abort(404);
        }

        $data['item']->description = $this->preventJavascriptScript(
            $data['item']->description
        );

        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['navbarActive'] = 'tasks';

        return view('pages.user.task.detail', $data);
    }

    private function preventJavascriptScript($string) {
        if (!empty($string)) {
            $string = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $string);
            $string = preg_replace('/(on\w+\s*=)/i', '', $string);
        }

        return $string;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $data['item'] = DB::table('tasks')
                            ->select([
                                'id', 'title', 'description', 'start_time',
                                'end_time', 'created_at', 'updated_at'
                            ])
                            ->where('user_id', '=', $request->get('userAuth')->id)
                            ->where('id', '=', $id)
                            ->first();

        if (empty($data['item'])) {
            abort(404);
        }

        $data['item']->description = $this->preventJavascriptScript(
            $data['item']->description
        );

        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['navbarActive'] = 'tasks';

        $data['backURL'] = url()->previous();
        $segments = explode('/', $data['backURL']);
        $numSegments = count($segments); 
        $currentSegment = $segments[$numSegments - 1];
        $segment = explode('?', $currentSegment);
        $segment = $segment[0];

        if ($segment !== 'tasks') {
            $data['backURL'] = route('user.tasks.index');
        }

        return view('pages.user.task.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, $id)
    {
        $item = DB::table('tasks')
                            ->select([
                                'id'
                            ])
                            ->where('user_id', '=', $request->get('userAuth')->id)
                            ->where('id', '=', $id)
                            ->first();

        if (empty($item)) {
            abort(404);
        }

        $errors = [];

        $start_time = $request->start_date;

        if (!empty($start_time)) {
            if (empty($request->start_time)) {
                $start_time .= ' 00:00:00';
            } else {
                $start_time .= ' ' . $request->start_time;
            }

            $start_time = strtotime($start_time);
        }

        if ($start_time === false) {
            $errors['start_time'] = 'The Start Time could not be created.';
        }

        $end_time = $request->end_date;

        if (!empty($end_time)) {
            if (empty($request->end_time)) {
                $end_time .= ' 00:00:00';
            } else {
                $end_time .= ' ' . $request->end_time;
            }

            $end_time = strtotime($end_time);
        }

        if ($end_time === false) {
            $errors['end_time'] = 'The End Time could not be created.';
        }

        if ((!empty($start_time) && !empty($end_time)) && ($start_time > $end_time)) {
            $errors['end_time'] = 'The Start Time needs to be more than equal to the End Time.';
        }

        if (count($errors) > 0) {
            return back()->withInput($request->all())->withErrors($errors);
        }

        $currentTime = time();

        $input = [
            'title' => $request->title,
            'description' => $request->description,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'updated_at' => $currentTime
        ];

        $process = DB::table('tasks')->where('id', '=', $id)
                                    ->update($input);

        if ($process) {
            $request->session()->flash('taskProcessSuccessfully', 'Berhasil mengubah tugas.');
            Cache::forever('taskManaged' . $request->get('userAuth')->id, $currentTime);
        } else {
            $request->session()->flash('taskProcessFailed', 'Gagal mengubah tugas.');
        }

        $seeList = Cache::get('seeTaskList');

        if (empty($seeList)) {
            return redirect()->route('user.tasks.index');
        }

        return redirect()->route('user.tasks.index', $seeList);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $process = DB::table('tasks')->where('user_id', '=', $request->get('userAuth')->id)
                                    ->where('id', '=', $id)
                                    ->delete();

        if ($process) {
            $request->session()->flash('taskProcessSuccessfully', 'Berhasil menghapus tugas.');
            Cache::forever('taskManaged' . $request->get('userAuth')->id, time());
        } else {
            $request->session()->flash('taskProcessFailed', 'Gagal menghapus tugas.');
        }

        return redirect()->back();
    }
}
