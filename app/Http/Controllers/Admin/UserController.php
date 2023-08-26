<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
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

        $data['navbarActive'] = 'Users';

        $data['input'] = [
            'page' => intval($request->page),
            'keyword' => $request->keyword,
            'start_date_created' => intval($request->start_date_created),
            'end_date_created' => intval($request->end_date_created),
            'role' => $request->role
        ];

        if ($data['input']['page'] < 1) {
            $data['input']['page'] = 1;
        }

        if ($data['input']['page'] > 500) {
            $data['input']['page'] = 500;
        }

        $listChanges = Cache::get('userListChanges');

        $listManaged = Cache::get('userManaged');

        if ((empty($listChanges) && empty($listManaged)) || (!empty($listManaged) && $listChanges !== $listManaged)) {
            if (empty($listManaged)) {
                Cache::forever('userListChanges', 1);
            } else {
                Cache::forever('userListChanges', $listManaged);
            }

            $data = $this->getListFromDatabase($data, $request);
        } else {
            $cache = Cache::get('userList' . json_encode($data['input']));

            if (empty($cache)) {
                $data = $this->getListFromDatabase($data, $request);
            } else {
                $data = $cache;
            }
        }

        return view('pages.admin.user.index', $data);
    }

    private function getListFromDatabase($data, $request) {
        $data['totalItems'] = DB::table('users');

        $data['totalItems'] = $this->listQuery($data['totalItems'], $data['input'])
                                    ->count();

        $data['totalPages'] = intval(ceil($data['totalItems'] / 15));

        if ($data['totalPages'] > 500) {
            $data['totalPages'] = 500;
        }

        $offset = $data['input']['page'] > 1 ? ($data['input']['page'] * 15) - 15 : 0;

        $data['items'] = DB::table('users');

        $data['items'] = $this->listQuery($data['items'], $data['input'])
                                ->orderBy('name', 'asc')
                                ->offset($offset)
                                ->limit(15)
                                ->get();

        Cache::forever('userList' . json_encode($data['input']), $data);

        return $data;
    }

    private function listQuery($db, $input) {
        if (!empty($input['role'])) {
            $db = $db->where('role', '=', $input['role']);
        }

        if (!empty($input['start_date_created']) && empty($input['end_date_created'])) {
            $db = $db->where('created_at', '>=', $input['start_created_date']);
        }

        if (empty($input['start_date_created']) && !empty($input['end_date_created'])) {
            $db = $db->where('created_at', '>=', $input['end_created_date']);
        }

        if (!empty($input['start_date_created']) && !empty($input['end_date_created'])) {
            $db = $db->whereBetween('created_at', [
                $input['start_created_date'] - 1,
                $input['end_created_date'] + 1
            ]);
        }

        if (!empty($input['keyword'])) {
            $db = $db->where(function ($query) use ($input) {
                $query->where('id', 'LIKE', '%' . $input['keyword'] . '%')
                        ->orWhere('name', 'LIKE', '%' . $input['keyword'] . '%') 
                        ->orWhere('username', 'LIKE', '%' . $input['keyword'] . '%') 
                        ->orWhere('email', 'LIKE', '%' . $input['keyword'] . '%');
            });
        }

        return $db;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
