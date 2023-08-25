<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateApplicationRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function index() {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['navbarActive'] = 'application';

        $data['item'] = DB::table('applications as a')
                            ->join('users as b', 'a.user_id', '=', 'b.id', 'left')
                            ->select([
                                'a.name', 'a.tagline', 'a.description',
                                'a.copyright', 'a.created_at', 'a.updated_at',
                                'b.name as user_name', 'b.username'
                            ])
                            ->first();

        return view('pages.admin.application.index', $data);
    }

    public function edit() {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        $data['navbarActive'] = 'application';

        return view('pages.admin.application.edit', $data);
    }

    public function update(UpdateApplicationRequest $request) {
        $application = DB::table('applications')->first();

        $input = $request->validated();

        $input['user_id'] = $request->get('adminAuth')->id;

        $input['updated_at'] = time();

        $process = DB::table('applications')
                        ->where('id', '=', $application->id)
                        ->update($input);

        if (!$process) {
            return back()->withErrors([
                'name' => 'Gagal mengubah infomasi aplikasi.',
            ]);
        }

        Cache::forget('application');

        $request->session()->flash('applicationChangedSuccessfully', 'OK');

        return redirect()->route('admin.application.index');
    }
}
