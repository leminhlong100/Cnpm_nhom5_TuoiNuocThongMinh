@extends('auth.auth')
@section('content-auth')
<div class="modal fade" style=" position: fixed;
  top: 60%;
  left: 50%;
  transform: translate(-50%, -50%);" id="emailInvalidModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ trans('login.thong_bao') }}</h4>
      </div>
      <div class="modal-body">
        <p>{{ trans('login.error_email') }}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('login.close') }}</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" style=" position: fixed;
  top: 60%;
  left: 50%;
  transform: translate(-50%, -50%);" id="passwordInvalidModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ trans('login.thong_bao') }}</h4>
      </div>
      <div class="modal-body">
        <p>{{ trans('login.error_pass')}}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('login.close') }}</button>
      </div>
    </div>
  </div>
</div>

    <div class="login-box">
        <h1 class="text-center mb-5" style="margin-bottom: 10px !important;">
            <img src="assets/img/logo.png" class="" style="width: 60px; height: 60px;"/>
            {{--            <i class="fa fa-rocket text-primary"></i>--}}
            {{ trans('login.hethong') }}</h1>
        <div class="column">
            <div class="login-box-form" style="width: 400px; text-align: center; margin: auto">
                <h3 class="mb-2" style="margin-bottom: 20px!important;"> {{ trans('login.login') }}</h3>
                <form method="post" action="{{route('postUsLogin')}}" class="mt-2" onsubmit="return validateForm()" name="myForm">
                    @csrf
                    @if(Session::has('ok'))
                        <small class="form-text text-success">{{ Session::get('ok') }}</small>
                    @endif
                    <p class="form">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-user"></i></span>
                        </div>
                        <input type="text" class="form-control mt-0" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" name="email" id="email" value="{{ old('email') }}" />
                    </div>
                    </p>

                    <p class="form">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><i class="fa fa-lock"></i></span>
                        </div>
                        <input type="password" class="form-control mt-0" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" name="pass" id="pass" />
                    </div>
                    </p>

                    <div class="form-group">
                        <input type="submit" class="btn btn-theme btn-block p-2 mb-1" name="login" value="Đăng nhập" />
                        <a href="{{route('register')}}">
                            <small class="text-theme" style="font-style: italic; float: left; margin-top: 10px; margin-left: 10px"><strong>Đăng ký tài khoản</strong></small>
                        </a>
                        <a href="{{route('forgotPass')}}">
                            <small class="text-theme" style="font-style: italic; float: right; margin-top: 10px; margin-right: 10px"><strong>Quên mật khẩu?</strong></small>
                        </a>
                    </div>
                </form>
            </div>
            <script>
   function validateForm() {
  var email = document.forms["myForm"]["email"].value;
  var password = document.forms["myForm"]["pass"].value;

  // Kiểm tra tính hợp lệ của email
  var emailPattern = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
  if (!emailPattern.test(email)) {
    $('#emailInvalidModal').modal('show');
    return false;
  }

  // Kiểm tra độ dài của mật khẩu
  if (password.length < 8) {
    $('#passwordInvalidModal').modal('show');
    return false;
  }
}

</script>

        </div>
    </div>
@endsection()

