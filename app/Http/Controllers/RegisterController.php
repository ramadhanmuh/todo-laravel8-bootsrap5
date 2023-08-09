<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\RegisterRequest;

class RegisterController extends Controller
{
    public function show()
    {
        $data['application'] = Cache::rememberForever('application', function () {
            return DB::table('applications')->first();
        });

        return view('pages.auth.register', $data);
    }

    public function save(RegisterRequest $request) {
        return response()->json([
            'success' => true
        ]);
    }
}
