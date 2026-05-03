@extends('layouts.app')
@section('title', '欢迎登录')

@section('content')

<style>
  .auth-page {
    min-height: calc(100vh - 140px);
    /* background: radial-gradient(circle at 20% 20%, #e7f7ff, #eff6ff 45%, #e5f5ef 80%); */
    background: linear-gradient(145deg, #fffaf9 0%, #fff5f2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 36px 16px;
  }

  .auth-card {
    width: 100%;
    max-width: 460px;
    border: none;
    border-radius: 18px;
    /* box-shadow: 0 14px 38px rgba(21, 45, 71, 0.15); */
    box-shadow: 0 20px 40px -12px rgba(226, 55, 41, 0.12), 0 4px 12px rgba(0, 0, 0, 0.02);
    overflow: hidden;
    background: #fff;
  }

  .auth-header {
    padding: 20px 24px;
    /* border-bottom: 1px solid #f1f3f5; */
    border-bottom: 1px solid #FEF1EF;
    font-weight: 700;
    font-size: 20px;
    letter-spacing: .2px;
    /* color: #0f2e3e; */
    color: #E23729;
    /* background: linear-gradient(120deg, #90b5e6ff, #e3f2f2); */
    background: transparent;
  }

  .auth-body {
    padding: 24px;
  }

  .auth-label {
    font-weight: 600;
    /* color: #243746; */
    color: #2C3E4E;
  }

  /* .auth-input {
    border-radius: 12px;
    padding: 12px 14px;
  } */
  .auth-input {
    border-radius: 12px;
    padding: 12px 14px;
    border: 1px solid #FFE0D6;
    transition: all 0.2s ease;
  }

  /* 覆盖 Bootstrap 默认蓝色聚焦样式 - 更高优先级 */
  .form-control:focus,
  input.form-control:focus,
  .auth-input:focus,
  #email:focus,
  #password:focus {
    border-color: #E23729 !important;
    box-shadow: 0 0 0 0.2rem rgba(226, 55, 41, 0.25) !important;
    outline: none !important;
  }

  /* 同时覆盖输入框的蓝色外发光 */
  .form-control:focus {
    border-color: #E23729 !important;
    box-shadow: 0 0 0 0.2rem rgba(226, 55, 41, 0.25) !important;
  }

  /* 确保所有输入框聚焦都是红色 */
  input:focus {
    border-color: #E23729 !important;
    box-shadow: 0 0 0 0.2rem rgba(226, 55, 41, 0.25) !important;
    outline: none !important;
  }

  .auth-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .auth-btn {
    min-width: 120px;
    border-radius: 12px;
    padding: 12px 18px;
    font-weight: 600;
    box-shadow: 0 8px 20px -6px rgba(226, 55, 41, 0.35);
    /* box-shadow: 0 10px 24px rgba(55, 192, 169, 0.25); */
    /* background: linear-gradient(120deg, #37c0a9, #6cc7f5); */
    background: linear-gradient(105deg, #E23729 0%, #EC5E29 100%);
    border: none;
    /* color: #0f2e3e; */
    color: #ffffff !important;
  }

  .auth-btn:hover {
    filter: brightness(0.98);
    box-shadow: 0 4px 14px -4px rgba(226, 55, 41, 0.45);
    background: linear-gradient(105deg, #D42C1F 0%, #E35222 100%);
    color: #ffffff !important;
  }

  .auth-link {
    font-weight: 600;
    /* color: #2d4d63; */
    color: #E23729;
  }

  .auth-check {
    display: flex;
    align-items: center;
    margin-top: 6px;
    gap: 8px;
    padding-left: 25px;
  }

  /* 输入框正确/错误样式 - 红橙配色 */
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

  /* 输入框聚焦时边框和阴影 */
  .form-control:focus {
    border-color: #E23729 !important;
    box-shadow: 0 0 0 0.2rem rgba(226, 55, 41, 0.15) !important;
  }

  /* 错误提示文字 */
  .invalid-feedback {
    color: #E23729 !important;
  }

  /* 链接 hover 颜色 */
  .auth-link:hover {
    color: #EC5E29 !important;
    text-decoration: none;
    transition: color 0.2s ease;
  }

  /* checkbox 选中后变红橙配色 */
  .form-check-input:checked {
    background-color: #E23729 !important;
    border-color: #E23729 !important;
  }

  /* checkbox 聚焦边框 */
  .form-check-input:focus {
    border-color: #EC5E29 !important;
    box-shadow: 0 0 0 0.2rem rgba(226, 55, 41, 0.1) !important;
  }
</style>

<div class="auth-page">
  <div class="auth-card">
    <div class="auth-header">登录</div>

    <div class="auth-body">
      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
          <label for="email" class="auth-label">邮箱</label>
          <div>
            <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror @if(session()->has('email_no_pwd')) is-invalid @endif" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @error('email')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror

            @if(session()->has('email_no_pwd'))
            <span class="invalid-feedback">
              <strong>{{ session()->get('email_no_pwd') }}</strong>
            </span>
            @endif
          </div>
        </div>

        <div class="form-group mt-3">
          <label for="password" class="auth-label">密码</label>
          <div>
            <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            @error('password')
            <span class="invalid-feedback" role="alert">
              <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
        </div>

        <div class="form-group mt-3 auth-check">
          <input class="form-check-input mt-0" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
          <label class="form-check-label mb-0" for="remember"> 记住我</label>
        </div>

        <div class="form-group mt-4 auth-actions">
          <button type="submit" class="btn btn-primary auth-btn">登录</button>
          <a class="auth-link" href="{{ route('password_send_email') }}">忘记密码？</a>
        </div>
      </form>

      <div class="text-center mt-3" style="padding-top: 16px; border-top: 1px solid #FEE8E4;">
        <a href="/admin" class="auth-link" style="font-size: 14px;">
          <i class="fas fa-user-shield"></i> 管理员入口
        </a>
      </div>
    </div>
  </div>
</div>

@stop