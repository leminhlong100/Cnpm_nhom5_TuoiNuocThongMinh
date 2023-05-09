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
    // bước 2.6.1. Người dùng ấn vào nút [Đăng ký tài khoản] ở màn hình [Đăng nhập] 
    // dòng số 52 :<a href="{{route('register')}}">
    // hiển thị trang đăng ký

    // bước 2.6.2. Hệ thống hiển thị màn hình [Đăng ký] ở trung tâm màn hình thiết bị gồm các cặp thuộc tính, mỗi cặp gồm có tên trường nằm bên trên và trường để nhập dữ liệu nằm bên dưới và mỗi cặp sẽ nằm trên mỗi dòng, thứ tự tên trường như sau: Họ tên, email, mật khẩu, xác nhận mật khẩu , ngày sinh , giới tính , số điện thoại, địa chỉ. Trong đó các trường bắt buộc phải nhập được thể hiện bằng các dấu “*” màu đỏ ở cuối mỗi tên trường , ví dụ “Họ tên *”
    // ở bên trang view :resources/views/auth/register.blade.php bắt đầu từ dòng 17
    public function showRegister()
    {
        return view('auth.register');
    }

    //bước 2.6.3 điền thông tin bên register.blade.php

    //bước 2.6.4 : Hệ thống sẽ kiểm tra tính hợp lệ của email, kiểm tra trên xem email này đã có tài khoản trên hệ thống chưa. (bên file check-form-event-input.js)
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


    // bước 2.6.5 : (bên file register.blade.php)
    // kiểm tra tính hợp lệ sau khi người dùng click [Đăng ký] ở trang (Đăng ký) ở dòng 127 
    // <input type="submit" class="col-sm-12 btn btn-theme" name="register" value="Đăng ký"
    public function doRegister(Request $request)
    {
        //bước 2.6.6 : Hệ thống sẽ kiểm tra dữ liệu lại lần nữa . Nếu dữ liệu hợp lệ thì sẽ lưu tài khoản này vào database với trạng thái là 0 (chưa kích hoạt)
        $request->validate([
            'r_email' => 'required|email',
            'r_userName' => 'required|min:3|max:50',
            'r_pass' => 'required|min:8',
            're_pass' => 'required|same:r_pass',
            'r_phone' => 'required|size:10',
        ], $this->messages());
        // query với điều kiện email
        $user = User::where('email', '=', $request->r_email)->first();
        // email chưa được đăng ký trên hệ thống
        if ($user == null) {
            $token = Str::random(40);
            // bước 2.6.6 : lưu tài khoản này vào database với trạng thái mặc định là 0 (chưa kích hoạt)
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

            // bước 2.6.7 Hệ thống tạo và gửi một email xác nhận tới địa chỉ email mà người dùng đã nhập. ( Chuyển qua ActiveAccountRegister để gửi mail)
            $user->notify(new ActiveAccountRegister());
            //bước 2.6.8 . Hệ thống chuyển qua màn hình [Đăng ký] và  hiển thị thông báo màu xanh lá cây: ”Tạo tài khoản thành công - kiểm tra email để kích hoạt tài khoản.” ở bên dưới các trường nhập dữ liệu 
            return redirect()->back()->withInput($request->only('ok'))->withErrors(['register' => 'Tạo tài khoản thành công - kiểm tra email để kích hoạt tài khoản.']);
        } else {
            // đã tồn tại active = 1 thông báo lỗi
            if ($user->active == 1) {
                return redirect()->back()->withErrors(['r_email' => 'Email này đã được đăng ký trước đó!'])->withInput();
            } else {
                // email tồn tại active = 0 gửi lại email
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
                // bước 2.6.7 Hệ thống tạo và gửi một email xác nhận tới địa chỉ email mà người dùng đã nhập. ( Chuyển qua ActiveAccountRegister để gửi mail)
                $user->notify(new ActiveAccountRegister());
                //bước 2.6.8 . Hệ thống chuyển qua màn hình [Đăng ký] và  hiển thị thông báo màu xanh lá cây: ”Tạo tài khoản thành công - kiểm tra email để kích hoạt tài khoản.” ở bên dưới các trường nhập dữ liệu 
                return redirect()->back()->withInput($request->only('ok'))->withErrors(['register' => 'Tạo tài khoản thành công - kiểm tra email để kích hoạt tài khoản.']);
            }
        }
    }

    // bước 2.6.9 - 2.6.10 xác thực tài khoản (ở bên file ActiveAccountRegister.php )
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
            if ($now->lt($kt)) { // mail này có hiệu lực trong 1h
                //bước 2.6.11 . Hệ thống cập nhật trạng thái (active) tài khoản vừa kích hoạt thành công từ ‘0’ sang ‘1’
                $u->active = 1;
                $u->key_time = null;
                $u->random_key = null;
                $u->update();
                // bước 2.6.12 : Hệ thống sẽ chuyển đến trang [Đăng nhập] và hiển thị thông báo "Xác nhận email thành công! Bạn có thể đăng nhập." ở bên trên trường nhập Email
                // (thông báo thành công bên file userLogin.blade.php)
                return redirect('login')->with('ok', 'Xác nhận email thành công! Bạn có thể đăng nhập.');
            } else { // xuất ra lỗi 404
                return redirect('404')->withErrors(['mes' => 'Liên kết đã hết hạn!']);
            }
        }
    }

    // định nghĩa thông báo lỗi
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