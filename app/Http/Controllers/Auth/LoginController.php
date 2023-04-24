<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginUser(){
        if (session('UserID')) {
            return redirect()->route('homeUs');
        }
        return view('auth.userLogin');
    }

    public function postUserLogin(Request $request)
    {
//        check email đã active hay chưa &  check tài khoản admin với role_id = 2
        $u = User::where('email', '=', $request->email)
            ->where('active', '=', '1')->first();
        if (!$u) {
            return redirect()->back()->withInput($request->only('email'))->withErrors(['mes' => trans('login.error_register')]);
        } else {
            if ( Hash::check($request->pass, $u->password)) {
                Session::put('UserID', $u->id);
                Session::put('User', $u);
                return redirect()->route('homeUs');
            } else {
                return redirect()->back()->withInput($request->only('pass'))->withErrors(['mes' => trans('login.error_pass_false')]);
            }
        }
    }

    public function getUserLogout(){
        Session::forget('UserID');
        Session::forget('User');
        return redirect()->route('userLogin');
    }
}
