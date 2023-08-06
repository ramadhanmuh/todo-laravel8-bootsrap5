<?php

namespace App\Http\Controllers;

class RegisterController extends Controller
{
    public function show()
    {
        return view('pages.auth.register');
    }
}
