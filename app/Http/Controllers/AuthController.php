<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function doLogin(Request $request)
    {
        // dummy login
        session(['user_id' => 1, 'username' => 'Demo User']);
        return redirect('/');
    }

    public function logout()
    {
        session()->flush();
        return redirect('/');
    }
}

