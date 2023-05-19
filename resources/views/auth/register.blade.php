@extends('auth.auth')
@section('content-auth')
<div class="login-box">
    <h2 class="text-center mb-5" style="margin-bottom: 10px !important;">
        <img src="assets/img/logo.png" class="" style="width: 60px; height: 60px;" />
        {{--            <i class="fa fa-rocket text-primary"></i>--}}
        Hệ thống tưới nước tự động thông minh
    </h2>
    <div class="column">
        <div class="login-box-form" style="padding: 15px 25px 0.5px 25px!important;">
            <p class="form" style="color: red; font-size: 15px!important;">
                @error('changePass')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </p>
            <h3 class="mb-2" style="margin-bottom: 20px!important; text-align: center">Đăng ký tài khoản</h3>

            <!-- Mỗi trường nhập liệu sẽ nằm trong class form của thẻ <p class="form"> -->
            <!-- Người dùng sẽ nhập thông tin trong thẻ <form></form>
        Lưu ý: các trường <label></label> có chứa dấu (*) là các trường mà người dùng bắt buộc phải nhập như:
        Họ tên, email, mật khẩu, xác nhận mật khẩu ,số điện thoại 
        -->
            <form method="post" action="{{route('postRegister')}}" class="form-horizontal mt-4 mb-5"
                style="margin-top: 0px!important;">
                @csrf
                <div class="form-group row" style="margin-bottom: 0px">
                    <!-- Họ tên (*)-->
                    <div class="col-sm-6">
                        <p class="form">
                            <label class="control-label" for="input-1">Họ tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="input-1" name="r_userName"
                                value="{{old('r_userName')}}" />
                            @error('r_userName')
                            <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </p>
                    </div>
                    <!-- Email (*)-->
                    <div class="col-sm-6">
                        <p class="form">
                            <label class="control-label" for="input-2">Email <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="input-2" name="r_email"
                                value="{{old('r_email')}}" placeholder="abc@gmail.com, xy@st.com.vn" />
                            @error('r_email')
                            <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </p>
                    </div>
                </div>

                <div class="form-group row" style="margin-bottom: 0px">
                    <!-- Password (*)-->
                    <div class="col-sm-6">
                        <p class="form">
                            <label class="control-label" for="input-3">Mật khẩu đăng nhập <span
                                    class="text-danger">*</span></label>
                            <input type="password" class="form-control" aria-label="Password"
                                aria-describedby="basic-addon1" id="input-3" name="r_pass" />
                            @error('r_pass')
                            <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </p>
                    </div>
                    <!-- RePassword (*)-->
                    <div class="col-sm-6">
                        <p class="form">
                            <label class="control-label" for="input-4">Xác nhận mật khẩu <span
                                    class="text-danger">*</span></label>
                            <input type="password" class="form-control" aria-label="Repeat Password"
                                aria-describedby="basic-addon1" id="input-4" name="re_pass" />
                            @error('re_pass')
                            <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </p>
                    </div>
                </div>

                <div class="form-group row" style="margin-bottom: 0px">
                    <!-- Birthday -->
                    <div class="col-sm-6">
                        <p class="form">
                            <label class="control-label" for="input-5">Ngày sinh</label>
                            <input type="date" class="form-control" id="input-5" name="r_birthday"
                                value="{{old('r_birthday')}}" />
                            @error('r_birthday')
                            <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </p>
                    </div>
                    <!-- Gender -->
                    <div class="col-sm-6">
                        <p class="form">
                            <label class="control-label" for="exampleFormControlSelect1">Giới tính</label>
                            <select class="form-control" id="exampleFormControlSelect1" name="r_gender">
                                <option value="">Chọn...</option>
                                <option value="Nam" @if(old('r_gender')=='Nam' ) selected @endif>Nam</option>
                                <option value="Nữ" @if(old('r_gender')=='Nữ' ) selected @endif>Nữ</option>
                                <option value="khác" @if(old('r_gender')=='khác' ) selected @endif>Khác</option>
                            </select>
                            @error('r_gender')
                            <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </p>
                    </div>
                </div>

                <div class="form-group row" style="margin-bottom: 0px">
                    <!-- Phone (*)-->
                    <div class="col-sm-6">
                        <p class="form">
                            <label class="control-label" for="input-6">Số điện thoại <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="input-6" name="r_phone"
                                value="{{old('r_phone')}}" placeholder="0XX XXXX XXX" />
                            @error('r_phone')
                            <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </p>
                    </div>
                    <!-- Address -->
                    <div class="col-sm-6">
                        <p class="form">
                            <label class="control-label" for="input-7">Địa chỉ</label>
                            <input type="text" class="form-control" id="input-7" name="r_address"
                                value="{{old('r_address')}}" />
                            @error('r_address')
                            <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </p>
                    </div>
                </div>
                <div class="form-group row" style="margin-top: 40px">
                    <p class="form">
                        <!-- Thoát -->
                    <div class="col-sm-6">
                        <a href="{{route('homeUs')}}" class="col-sm-12 btn btn-danger" id="show-emp" data-toggle="modal"
                            data-target="#modal-up" style="color: white">Thoát</a>
                    </div>
                    <!-- bước 2.6.5 Nếu email chưa có và các dữ liệu người dùng nhập hợp lệ, người dùng nhấn nút [Đăng ký] -->
                    <!-- (bước 2.6.6 bên file RegisterController.php)  -->
                    <div class="col-sm-6">
                        <input type="submit" class="col-sm-12 btn btn-theme" name="register" value="Đăng ký"
                            id="register" style="padding: 8px 0; " data-target="" />
                    </div>
                    @error('register')
                    <div class="text-center col-sm-12"><small class="form-text text-success"
                            style="margin-top: 20px; font-size: 18px; margin-left: 15px">{{ $message }}</small></div>
                    @enderror
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- modal log hiển thị giao diện tùy chọn khi người dùng bấm nút Thoát -->
<div class="modal fade" id="modal-up" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="column">
                    <div class="login-box-form" style="padding: 15px 25px 0.5px 25px!important;">

                        <h4 class="mb-2" style="margin-bottom: 20px!important; text-align: center">Bạn có muốn thoát
                            không?
                        </h4>


                        <div class="form-group row" style="margin-top: 40px">
                            <p class="form">
                            <div class="col-sm-6">
                                <a class="col-sm-12 btn btn-theme" href="#" id="show-emp" data-toggle="modal"
                                    data-target="#modal-up" data-dismiss="modal" style="color: white">Không</a>

                            </div>
                            <div class="col-sm-6">

                                <a class="col-sm-12 btn btn-danger" href="{{route('homeUs')}}" style="color: white">
                                    Có</a>

                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
<!-- modal log thông báo cho người dùng biết chưa nhập đầy đủ thông tin cần thiết -->
<div class="modal fade" id="modal-check" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="column">
                    <div class="login-box-form" style="padding: 15px 25px 0.5px 25px!important;">
                        <h3 class="text-center text-danger"><i class="fas fa-exclamation-triangle"></i></i></h3>
                        <h5 class="text-center ">Chưa đầy đủ thông tin cần thiết(<span class="text-danger">*</span>)
                        </h5>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

@endsection

<!-- Script to active register
    ================================================== -->
@section('lastScript')
@if ($register ?? ''!='')
<script>
$(document).ready(function() {
    $('.tabs-nav a[href$="{{$register}}"]').parent("li").click();
});
</script>
@endif
@endsection()