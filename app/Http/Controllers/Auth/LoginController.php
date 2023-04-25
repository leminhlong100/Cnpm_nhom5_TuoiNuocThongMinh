<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
        //Bước 6.1: Kiểm tra xem người dùng đã đăng nhập hay chưa, nếu có thì chuyển hướng đến trang "homeUs", nếu không thì trả về trang "auth.userLogin"
    public function showLoginUser(){
        if (session('UserID')) {
            return redirect()->route('homeUs');
        }
        return view('auth.userLogin');
        // Bước 6.2. Bên trang view :resources/views/auth/userLogin.blade.php bắt đầu từ dòng 40
    }
        // Bước 6.3, 6.4 của người dùng bên view
        // Bước 6.5 Bên trang view :resources/views/auth/userLogin.blade.php bắt đầu từ dòng 90(kiểm tra bên client)
    public function postUserLogin(Request $request)
    {
        $u = User::where('email', '=', $request->email) // Bước 6.6 Kiểm tra tài khoản có tồn tại trong cơ sở dữ liệu không
            ->where('active', '=', '1')->first(); // lưu ý tài khoản phải được active trước đó bên register
        if (!$u) { // (7.2) Nếu tài khoản không tồn tại hoặc chưa active thì thông báo lỗi
            return redirect()->back()->withInput($request->only('email'))->withErrors(['mes' => trans('login.error_register')]);
        } else {
            if ( Hash::check($request->pass, $u->password)) {  // Bước 6.7 Kiểm tra mật khẩu của người dùng nhập vào có trùng với mật khẩu của tài khoản đó trong cơ sở dữ liệu không
                Session::put('UserID', $u->id);
                // Bước 6.8 Tạo một entity user, hệ thống lưu user vào session và hiển thị trang chính của người dùng
                Session::put('User', $u); 
                return redirect()->route('homeUs');
                // Bước 6.9 Bên trang view :resources/views/layout/headerUser.blade.php bắt đầu từ dòng 34(kiểm tra bên client)
            } else { // (7.3) Nếu người dùng nhập sai mật khẩu kèm thông báo lỗi
                return redirect()->back()->withInput($request->only('pass'))->withErrors(['mes' => trans('login.error_pass_false')]);
            }
        }
    }
    // Chức năng logout
    public function getUserLogout(){
        // xóa trong Session UserID và User
        Session::forget('UserID');
        Session::forget('User');
        return redirect()->route('userLogin');
    }
}
