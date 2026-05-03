@extends('layouts.app')
@section('title', '欢迎注册')

@section('content')
<style>
  .auth-page {
    min-height: calc(100vh - 140px);
    /* background: radial-gradient(circle at 20% 20%, #e7f7ff, #eff6ff 45%, #e5f5ef 80%); */
    background: linear-gradient(145deg, #fffaf9 0%, #fff5f2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px 16px;
  }
  .auth-card {
    width: 90%;
    max-width: 460px;
    border: none;
    border-radius: 18px;
    box-shadow: 0 14px 38px rgba(21, 45, 71, 0.15);
    overflow: hidden;
    background: #fff;
  }
  .auth-header {
    padding: 18px 22px;
    /* border-bottom: 1px solid #f1f3f5; */
     border-bottom: 1px solid #FEF1EF;
    font-weight: 700;
    font-size: 20px;
    letter-spacing: .2px;
    color: #0f2e3e;
    /* background: linear-gradient(120deg, #90b5e6ff, #e3f2f2); */
    background: transparent;
  }
  .auth-body {
    padding: 18px 20px 20px;
  }
  .auth-label {
    font-weight: 700;
    /* color: #243746; */
    color: #2C3E4E;
  }
  .auth-input {
    border-radius: 12px;
    padding: 10px 12px;
  }
  .auth-btn {
    width: 100%;
    border-radius: 12px;
    padding: 10px 16px;
    font-weight: 600;
    box-shadow: 0 8px 20px -6px rgba(226, 55, 41, 0.35);
    /* background: linear-gradient(120deg, #37c0a9, #6cc7f5); */
     background: linear-gradient(105deg, #E23729 0%, #EC5E29 100%);
    border: none;
    color: #000 !important;
  }
  .auth-btn:hover {
    filter: brightness(0.98);
     background: linear-gradient(105deg, #D42C1F 0%, #E35222 100%);
    color: #fff !important;
  }
  .captcha-box {
    max-width: 100%;
    padding: 0;
    margin-top: 8px;
  }
  .captcha-box img {
    border-radius: 10px;
    border: 1px solid #dbe4ec;
    box-shadow: none;
    cursor: pointer;
    width: 50%;
    max-width: 100%;
    height: auto;
    display: block;
    padding: 0;
    box-sizing: border-box;
  }
  /* 红橙配色 - 输入框正确/错误样式 + 对勾图标颜色 */
.form-control.is-valid {
    border-color: #EC5E29 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23EC5E29' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right calc(0.375em + 0.1875rem) center !important;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
}

.form-control.is-invalid {
    border-color: #E23729 !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23E23729' viewBox='-2 -2 7 7'%3e%3cpath stroke='%23E23729' d='M0 0l3 3m0-3L0 3'/%3e%3ccircle r='.5'/%3e%3ccircle cx='3' r='.5'/%3e%3ccircle cy='3' r='.5'/%3e%3ccircle cx='3' cy='3' r='.5'/%3e%3c/svg%3E") !important;
    background-repeat: no-repeat !important;
    background-position: right calc(0.375em + 0.1875rem) center !important;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
}

.valid-feedback {
    color: #EC5E29 !important;
}

.invalid-feedback {
    color: #E23729 !important;
}

.form-control:focus {
    border-color: #E23729 !important;
    box-shadow: 0 0 0 0.2rem rgba(226, 55, 41, 0.15) !important;
}
</style>

<div class="auth-page">
  <div class="auth-card" id="signup">
    <div class="auth-header">
      <h5 class="mb-0"><b>注册</b></h5>
    </div>
    <div class="auth-body">
      @include('shared._errors')
      <form method="POST" action="{{ route('users.register') }}">
        {{ csrf_field() }}

        <div class="form-group">
          <label for="name" class="auth-label">名称</label>
          <input type="text" maxlength="25" name="name" id="user_name" class="form-control auth-input uname {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" placeholder="请输入您的名称" required>
          <div class="{{ $errors->has('name') ? ' invalid-feedback' : '' }}" id="uname_tip">
            <strong>{{ $errors->first('name') }}</strong>
          </div>
        </div>

        <div class="form-group mt-3">
          <label for="email" class="auth-label">邮箱</label>
          <input type="text" maxlength="64" name="email" id="user_email" class="form-control auth-input uemail {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="请输入您的校园邮箱（@lzu.edu.cn）" required>
          <div class="{{ $errors->has('email') ? ' invalid-feedback' : '' }}" id="uemail_tip">
            <strong>{{ $errors->first('email') }}</strong>
          </div>
        </div>

        <div class="form-group mt-3">
          <label for="password" class="auth-label">密码</label>
          <input type="password" maxlength="32" name="password" id="user_pwd" class="form-control auth-input upwd" value="{{ old('password') }}" placeholder="密码不能少于6位" required>
          <div class="{{ $errors->has('password') ? ' invalid-feedback' : '' }}" id="upwd_tip">
            <strong>{{ $errors->first('password') }}</strong>
          </div>
        </div>

        <div class="form-group mt-3">
          <label for="password_confirmation" class="auth-label">确认密码</label>
          <input type="password" maxlength="32" name="password_confirmation" id="user_pwd2" class="form-control auth-input upwd2" value="{{ old('password_confirmation') }}" required>
          <div id="upwd2_tip"></div>
        </div>

        <div class="form-group mt-3">
          <label for="captcha" class="auth-label">验证码</label>
          <input autocomplete="off" id="captcha" maxlength="6" class="form-control auth-input{{ $errors->has('captcha') ? ' is-invalid' : '' }}" name="captcha" required>
          <div id="captcha_tip" class="{{ $errors->has('captcha') ? ' invalid-feedback' : '' }}">
            <strong>{{ $errors->first('captcha') }}</strong>
          </div>
          <div class="captcha-box mt-3 text-center">
            <img class="thumbnail captcha" src="{{ captcha_src('flat') }}" onclick="this.src='/captcha/flat?'+Math.random()" title="点击图片重新获取验证码">
          </div>
        </div>

        <button type="submit" autocomplete="off" class="btn btn-primary auth-btn btn_reg" id="btn_register" disabled><b>注册</b></button>
      </form>
    </div>
  </div>
</div>

@stop

@section('scriptsAfterJs')
<script>
  $(document).ready(function() {

    // 验证用户名
    $('#user_name').blur(function(){


      // 长度3-25
      // ajax-用户名唯一
      if($('#user_name').val().length>=3 && $('#user_name').val().length<=25 ){
        axios.get('/ajax_name/' + $(this).val()).then(function(res){
          //console.log(res.data)
          if(JSON.stringify(res.data)=='false'){
            $('#user_name').addClass('is-invalid').removeClass('is-valid');
            $("#uname_tip").addClass('invalid-feedback').removeClass('valid-feedback');
            $("#uname_tip").html('<strong>该名称已被注册，请重新填写！!</strong>');
          }else if(JSON.stringify(res.data)=='true'){
            $('#user_name').addClass('is-valid').removeClass('is-invalid');
            $("#uname_tip").addClass('valid-feedback').removeClass('invalid-feedback');
            $("#uname_tip").html('');
          }
          is_reg()
        },function(error){    // 请求错误
          if(error.response.status === 422){      // 验证不通过
            console.log(error.response.data.errors)
          }
          console.log(error.response.data.errors)
        })
      }else{
        $('#user_name').addClass('is-invalid').removeClass('is-valid');
        $("#uname_tip").addClass('invalid-feedback').removeClass('valid-feedback');
        $('#uname_tip').html('<strong>名称长度应为 3~25 ！！</strong>')
        is_reg()
      }
    })

    // 验证邮箱
    $('#user_email').blur(function(){
      //console.log('邮箱')
      var email = $("#user_email").val();
      var reg=/^[a-zA-Z\d]+@lzu\.edu\.cn$/;
      var res_data
      if(reg.test(email)){     // 邮箱格式正确
        axios.get('/ajax_email/'+ email).then(function(res){
          // console.log(res.data)
          res_data=res.data
          if(JSON.stringify(res.data)=='false'){
            $("#user_email").addClass('is-invalid').removeClass('is-valid');
            $("#uemail_tip").addClass('invalid-feedback').removeClass('valid-feedback');
            $("#uemail_tip").html('<strong>该邮箱已被注册，请重新填写！!</strong>');
          }else if(JSON.stringify(res.data)=='true'){
            $("#user_email").addClass('is-valid').removeClass('is-invalid');
            $("#uemail_tip").addClass('valid-feedback').removeClass('invalid-feedback');
            $("#uemail_tip").html('');
          }
          is_reg()
        }, function(error) {
          // AJAX 请求失败时的处理
          $("#user_email").addClass('is-invalid').removeClass('is-valid');
          $("#uemail_tip").addClass('invalid-feedback').removeClass('valid-feedback');
          $("#uemail_tip").html('<strong>邮箱验证失败，请稍后重试！</strong>');
          is_reg()
        })
      }else{
        //console.log('wrong')
        $('#user_email').addClass('is-invalid').removeClass('is-valid');
        $("#uemail_tip").addClass('invalid-feedback').removeClass('valid-feedback');
        $('#uemail_tip').html('<strong>请输入正确的校园邮箱格式（@lzu.edu.cn）！！</strong>')
        is_reg()
      }

    })

    // 验证密码
    $('#user_pwd').blur(function(){
      //console.log('密码')
      if($('#user_pwd').val().length==0){
        $('#user_pwd').addClass('is-invalid').removeClass('is-valid');
        $("#upwd_tip").addClass('invalid-feedback').removeClass('valid-feedback');
        $('#upwd_tip').html('<strong>请输入密码！！</strong>')
      }else if($('#user_pwd').val().length>0 && $('#user_pwd').val().length<6){
        $('#user_pwd').addClass('is-invalid').removeClass('is-valid');
        $("#upwd_tip").addClass('invalid-feedback').removeClass('valid-feedback');
        $('#upwd_tip').html('<strong>密码长度不能小于6位！！</strong>')
      }else{
        $('#user_pwd').addClass('is-valid').removeClass('is-invalid');
        $("#upwd_tip").addClass('valid-feedback').removeClass('invalid-feedback');
        $("#upwd_tip").html('');
      }

      is_reg();
    })
    // 确认密码
    $('#user_pwd2').blur(function(){
      //console.log('密码2')
      if($('#user_pwd').val().length == 0){
        $('#user_pwd2').addClass('is-invalid').removeClass('is-valid');
        $("#upwd2_tip").addClass('invalid-feedback').removeClass('valid-feedback');
        $('#upwd2_tip').html('<strong>请输入密码！！</strong>')
      }
      else if(($('#user_pwd').val() != $('#user_pwd2').val()) ){
        //console.log('密码不一致')
        $('#user_pwd2').addClass('is-invalid').removeClass('is-valid');
        $("#upwd2_tip").addClass('invalid-feedback').removeClass('valid-feedback');
        $('#upwd2_tip').html('<strong>两次密码输入不一致！！</strong>')
      }else{
        $('#user_pwd2').addClass('is-valid').removeClass('is-invalid');
        $("#upwd2_tip").addClass('valid-feedback').removeClass('invalid-feedback');
        $("#upwd2_tip").html('');
      }

      is_reg();
    })

    // 验证码
    $('#captcha').blur(function(){
      if($('#captcha').val().length > 0 ){
        $('#captcha').removeClass('is-valid').removeClass('is-invalid');
        $("#captcha_tip").removeClass('valid-feedback').removeClass('invalid-feedback');
        $("#captcha_tip").html('');
      }

      is_reg()
    })


    // 自定函数-判断是否有错误样式，注册按钮是否可用
    function is_reg(){
      var name=$('#user_name').val().length
      var email=$('#user_email').val().length
      var pwd=$('#user_pwd').val().length
      var pwd2=$('#user_pwd2').val().length
      var captcha=$('#captcha').val().length


      if($('div').hasClass('invalid-feedback') ){   // 有错误提示
        $('.btn_reg').attr('disabled','true')
      }else{
        if(name==0 ){       // 输入为空
          $('.btn_reg').attr('disabled','true')
        }else if(email==0){
          $('.btn_reg').attr('disabled','true')
        }else if(pwd==0){
          $('.btn_reg').attr('disabled','true')
        }else if(pwd2==0){
          $('.btn_reg').attr('disabled','true')
        }else if(captcha==0){
          $('.btn_reg').attr('disabled','true')
        }else{
          // 输入不为空 && 且没有错误提示
          $('.btn_reg').removeAttr('disabled')    // 可以使用注册按钮
        }

      }
    }

  })

</script>
@stop
