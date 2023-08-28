<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

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
            'start_date_created' => $request->start_date_created,
            'end_date_created' => $request->end_date_created,
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
            $dataIsFound = 0;

            $cache = Cache::get('userList');

            if (empty($cache)) {
                $data = $this->getListFromDatabase($data, $request);
            } else {
                foreach ($cache as $key => $value) {
                    if ($value['input'] === $data['input']) {
                        $data = $cache[$key];
                        $dataIsFound = 1;
                    }   
                }

                if (!$dataIsFound) {
                    $data = $this->getListFromDatabase($data, $request);
                }
            }
        }

        return view('pages.admin.user.index', $data);
    }

    private function getListFromDatabase($data, $request) {
        $data['totalItems'] = DB::table('users');

        $data['totalItems'] = $this->listQuery($data['totalItems'], $data['input'], $request)
                                    ->count();

        $data['totalPages'] = intval(ceil($data['totalItems'] / 15));

        if ($data['totalPages'] > 500) {
            $data['totalPages'] = 500;
        }

        $offset = $data['input']['page'] > 1 ? ($data['input']['page'] * 15) - 15 : 0;

        $data['items'] = DB::table('users')->select([
            'id', 'name', 'username', 'email', 'created_at'
        ]);

        $data['items'] = $this->listQuery($data['items'], $data['input'], $request)
                                ->orderBy('name', 'asc')
                                ->offset($offset)
                                ->limit(15)
                                ->get();

        $cache = Cache::get('userList');

        if (empty($cache)) {
            $cache = [];
            $cache[] = $data;
            Cache::forever('userList', $cache);
        } else {
            $cache[] = $data;
            Cache::forever('userList', $cache);
        }

        return $data;
    }

    private function listQuery($db, $input, $request) {
        $role = ['Administrator', 'User'];

        $db = $db->where('id', '!=', $request->get('adminAuth')->id);

        if (!empty($input['role'])) {
            if ($input['role'] === 'Administrator') {
                unset($role['User']);
            }

            if ($input['role'] === 'User') {
                unset($role['Administrator']);
            }
        }

        $db = $db->whereIn('role', $role);

        if (!empty($input['start_date_created']) && empty($input['end_date_created'])) {
            $db = $db->where('created_at', '>=', intval($input['start_date_created']));
        }

        if (empty($input['start_date_created']) && !empty($input['end_date_created'])) {
            $db = $db->where('created_at', '<=', intval($input['end_date_created']));
        }

        if (!empty($input['start_date_created']) && !empty($input['end_date_created'])) {
            $db = $db->whereBetween('created_at', [
                intval($input['start_date_created']) - 1,
                intval($input['end_date_created']) + 1
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
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['navbarActive'] = 'Users';

        return view('pages.admin.user.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $currentTime = time();

        $input = $request->validated();
        
        $input['password'] = Hash::make($input['password']);

        $input['email_verified_at'] = $currentTime;

        $input['created_at'] = $currentTime;

        $process = DB::table('users')->insert($input);

        if ($process) {
            $request->session()->flash('userProcessSuccessfully', 'Berhasil menambah pengguna.');
            Cache::forever('userManaged', $currentTime);
            Cache::forget('userList');
        } else {
            $request->session()->flash('userProcessFailed', 'Gagal menambah pengguna.');
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['navbarActive'] = 'Users';

        $data['item'] = DB::table('users')
                            ->where('role', '!=', 'Owner')
                            ->where('id', '=', $id)
                            ->first();

        if (empty($data['item']) || $data['item']->id === $request->get('adminAuth')->id) {
            abort(404);
        }

        return view('pages.admin.user.detail', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['navbarActive'] = 'Users';

        $data['item'] = DB::table('users')
                            ->select(['id', 'name', 'username', 'email', 'role'])
                            ->where('role', '!=', 'Owner')
                            ->where('id', '=', $id)
                            ->first();

        if (empty($data['item']) || $data['item']->id === $request->get('adminAuth')->id) {
            abort(404);
        }

        $data['backURL'] = url()->previous();
        $segments = explode('/', $data['backURL']);
        $numSegments = count($segments); 
        $currentSegment = $segments[$numSegments - 1];
        $segment = explode('?', $currentSegment);
        $segment = $segment[0];

        if ($segment !== 'users') {
            $data['backURL'] = route('admin.users.index');
        }

        return view('pages.admin.user.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $item = DB::table('users')
                            ->select(['id', 'password'])
                            ->where('role', '!=', 'Owner')
                            ->where('id', '=', $id)
                            ->first();

        if (empty($item) || $item->id === $request->get('adminAuth')->id) {
            abort(404);
        }

        $currentTime = time();

        $input = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'updated_at' => $currentTime
        ];

        if (empty($input['password'])) {
            $input['password'] = $item->password;
        }

        $process = DB::table('users')->where('id', '=', $id)
                                        ->update($input);

        if ($process) {
            $request->session()->flash('userProcessSuccessfully', 'Berhasil mengubah pengguna.');
            Cache::forever('userManaged', $currentTime);
            Cache::forget('userList');
        } else {
            $request->session()->flash('userProcessFailed', 'Gagal mengubah pengguna.');
        }

        return redirect()->route('admin.users.index');
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
