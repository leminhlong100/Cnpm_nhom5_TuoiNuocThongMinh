<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
// 20130316_LeMinhLong
class LoginController extends Controller
{
    // Đây là quy trình đăng nhập: 

        //(Đăng nhập) Bước 1.6.1:Người dùng truy cập vào hệ thống nếu chưa đăng nhập hệ thống sẽ tự động chuyển đến màn hình [Đăng nhập].
            // Kiểm tra xem người dùng đã đăng nhập hay chưa, nếu có thì chuyển hướng đến trang "homeUs", nếu không thì trả về trang "auth.userLogin"
    public function showLoginUser(){
        if (session('UserID')) {
            return redirect()->route('homeUs');
        }
        return view('auth.userLogin');
        //(Đăng nhập) Bước 1.6.2.Hệ thống hiển thị màn hình [Đăng nhập] gồm các trường để nhập thông tin như: Tên đăng nhập (dạng email), mật khẩu và button đăng nhập.
            //(Đăng nhập) (6.2) Bên trang view :resources/views/auth/userLogin.blade.php bắt đầu từ dòng 40
    }
        // (Đăng nhập) Bước: 1.6.3. Người dùng nhập thông tin tài khoản và mật khẩu vào các trường tương ứng trên giao diện đăng nhập.
        // (Đăng nhập) Bước: 6.4 Người dùng nhấn nút "Đăng nhập".
    
        // (Đăng nhập) Bước 1.6.5.  Hệ thống kiểm tra tài khoản(dang email) có phải là Email phải dùng định dạng xx@xx.xx và kiểm tra mật khẩu có trên 8 ký tự.
            //(Đăng nhập) (1.6.5) Bên trang view :resources/views/auth/userLogin.blade.php bắt đầu từ dòng 90(kiểm tra bên client)
    public function postUserLogin(Request $request)
    {
        //(Đăng nhập)  Bước 1.6.6 Kiểm tra tài khoản có tồn tại trong bảng User
        $u = User::where('email', '=', $request->email) 
            ->where('active', '=', '1')->first(); // 6.6 lưu ý tài khoản phải được active trước đó bên register
                    // (Đăng nhập) (1.6.6) Nếu email có trong bảng user thì sẽ tạo ra một entity User
        if (!$u) { 
            // (Đăng nhập) (1.7.2.2) Nếu tài khoản không tồn tại hoặc chưa active thì thông báo lỗi
            return redirect()->back()->withInput($request->only('email'))->withErrors(['mes' => trans('login.error_register')]);
        } else {
        //(Đăng nhập)  Bước 1.6.7 Kiểm tra mật khẩu của người dùng nhập vào có trùng với mật khẩu của tài khoản đó trong User không
            if ( Hash::check($request->pass, $u->password)) {  
                Session::put('UserID', $u->id);
        //(Đăng nhập)  Bước 1.6.8 Hệ thống lưu user vào session và hiển thị trang chính của người dùng
                Session::put('User', $u); 
                return redirect()->route('homeUs');
        // (Đăng nhập) Bước 1.6.9:Nếu entity user có là admin (role = 2) thì hệ thống hiển thị trong phần header sẽ hiển thị button cho phép truy cập vào trang quản lý (Web quản lý)
                //(Đăng nhập) (1.6.9)Bên trang view :resources/views/layout/headerUser.blade.php bắt đầu từ dòng 34(kiểm tra bên client)
            } else { // (Đăng nhập) (1.7.3.2) Nếu người dùng nhập sai mật khẩu kèm thông báo lỗi
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
