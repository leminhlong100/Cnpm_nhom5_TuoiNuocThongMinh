<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ActiveAccountRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    // bước 1. khi người dùng click [Đăng ký tài khoản] ở trang (Đăng nhập) 
    // dòng số 52 :<a href="{{route('register')}}">
    // hiển thị trang đăng ký

    // bước 2. ở bên trang view :resources/views/auth/register.blade.php bắt đầu từ dòng 17
    public function showRegister()
    {
        return view('auth.register');
    }

    // đăng ký tài khoản
    // kiểm tra tính hợp lệ sau khi người dùng click [Đăng ký] ở trang (Đăng ký) ở dòng 127 
    // <input type="submit" class="col-sm-12 btn btn-theme" name="register" value="Đăng ký"
    public function doRegister(Request $request)
    {
        // kiểm tra dữ liệu người dùng nhập vào 1 lần nữa
        $request->validate([
            'r_email' => 'required|email',
            'r_userName' => 'required|min:3|max:50',
            'r_pass' => 'required|min:8',
            're_pass' => 'required|same:r_pass',
            'r_phone' => 'required|size:10',
        ], $this->messages());
        // query với điều kiện email
        $user = User::where('email', '=', $request->r_email)->first();
        // email không tồn tại gửi email mơi
        if ($user == null) {
            $token = Str::random(40);
            $user = User::create([
                'email' => $request->r_email,
                'password' => Hash::make($request->r_pass),
                'name' => $request->r_userName,
                'birthday' => $request->r_birthday,
                'gender' => $request->r_gender,
                'phone' => $request->r_phone,
                'address' => $request->r_address,
                'random_key' => $token,
                'role_id' => 1,
                'key_time' => Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s')
            ]);

            // gửi email kích hoạt tài khoản
            $user->notify(new ActiveAccountRegister());
            // redirect về trang (Đăng nhập) và thông báo thành công
            return redirect()->back()->withInput($request->only('ok'))->withErrors(['register' => 'Tạo tài khoản thành công - kiểm tra email để kích hoạt tài khoản.']);
        } else {
            // đã tồn tại active = 1 thông báo lỗi
            if ($user->active == 1) {
                return redirect()->back()->withErrors(['r_email' => 'Email này đã được đăng ký trước đó!'])->withInput();
            } else {
                // email tồn tại active =0 gửi lại email
                $token = Str::random(40);
                $user->email = $request->r_email;
                $user->password = Hash::make($request->r_pass);
                $user->name = $request->r_userName;
                $user->birthday = $request->r_birthday;
                $user->gender = $request->r_gender;
                $user->phone = $request->r_phone;
                $user->address = $request->r_address;
                $user->role_id = 1;
                $user->random_key = $token;
                $user->key_time = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');
                $user->update();
                $user->notify(new ActiveAccountRegister());
                // quay lại trang đăng ký
                return redirect()->back()->withInput($request->only('ok'))->withErrors(['register' => 'Tạo tài khoản thành công - kiểm tra email để kích hoạt tài khoản.']);
            }
        }
    }

    // xác thực tàfi khoản
    public function confirmEmail($email, $key)
    {
        //		Session::forget( 'signup' );

        $u = User::select('id', 'email', 'key_time', 'active')
            ->where('email', '=', $email)
            ->where('random_key', $key)
            ->where('active', '=', '0')
            ->first();

        if ($u == null) {
            return redirect('404')->withErrors(['mes' => 'Xác nhận email không thành công! Email hoặc mã xác thực không đúng. ']);
        } else {
            $kt = Carbon::parse($u->key_time)->addHours(1); // thêm 1 giờ vào thời gian key_time
            $now = Carbon::now();
            // kiểm tra xem giờ hiện tại còn hiệu lực không
            // nếu còn thì cập nhật trạng thái cho tài khoản
            if ($now->lt($kt)) {
                $u->active = 1;
                $u->key_time = null;
                $u->random_key = null;
                $u->update();
                return redirect('login')->with('ok', 'Xác nhận email thành công! Bạn có thể đăng nhập.');
            } else { // xuất ra lỗi 404
                return redirect('404')->withErrors(['mes' => 'Liên kết đã hết hạn!']);
            }
        }
    }
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->where('active', 1)->first();
        if ($user) {
            return response()->json([
                'exists' => true,
                'message' => 'Email này đã được đăng ký trước đó'
            ]);
        } else {
            return response()->json([
                'exists' => false,
                'message' => 'Email chưa được sử dụng'
            ]);
        }
    }

    private function messages()
    {
        return [
            'r_userName.required' => 'Bạn cần nhập họ tên',
            'r_userName.min' => 'Họ tên cần lớn hơn 2 kí tự',
            'r_userName.max' => 'Họ tên cần bé hơn 50 kí tự',
            'r_email.required' => 'Bạn cần nhập Email.',
            'r_email.email' => 'Định dạng Email bị sai.',
            'r_email.unique' => 'Email đã tồn tại',
            'r_phone.required' => 'Bạn cần nhập số điện thoại liên lạc.',
            'r_phone.size' => 'Số điện thoại phải có đúng 10 số',
            'r_pass.required' => 'Cần phải nhập mật khẩu đăng nhập.',
            'r_pass.min' => 'Mật khẩu phải đủ 8 ký tự trở lên.',
            're_pass.required' => 'Cần nhập xác nhận mật khẩu.',
            're_pass.same' => 'Xác nhận mật khẩu không trùng với mật khẩu.',
        ];
    }
}