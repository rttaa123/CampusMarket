@extends('layouts.app')

@section('content')
<style>
  .auth-page {
    min-height: calc(100vh - 140px);
    /* background: linear-gradient(135deg, #fff8f7 0%, #fff5f0 50%, #fff9f7 100%); */
    /* 柔和白底 + 顶部极浅红橙渐变，留白更多 */
    background: linear-gradient(145deg, #fffaf9 0%, #fff5f2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 36px 16px;
  }

  .auth-card {
    width: 100%;
    max-width: 520px;
    border: none;
    border-radius: 18px;
    /* box-shadow: 0 14px 38px rgba(226, 55, 41, 0.12); */
    /* 干净卡片 + 轻盈阴影 */
    box-shadow: 0 20px 40px -12px rgba(226, 55, 41, 0.12), 0 4px 12px rgba(0, 0, 0, 0.02);
    overflow: hidden;
    background: #fff;
  }

  .auth-header {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f3f5;
    font-weight: 700;
    font-size: 20px;
    letter-spacing: .2px;
    /* color: #c0392b; */
    /* 主标题直接使用明艳红 #E23729，但只用文字色，保持通透 */
    color: #E23729;
    background: linear-gradient(120deg, #FCF1F0, #FDF1EC);
  }

  .auth-body {
    padding: 24px;
    color: #4a2020;
    line-height: 1.6;
  }

  /* 主按钮 橙红渐变 + 高级感 */
  .auth-btn {
    width: 100%;
    border-radius: 12px;
    padding: 12px 18px;
    font-weight: 600;
    /* box-shadow: 0 10px 24px rgba(55, 192, 169, 0.25); */
    box-shadow: 0 8px 20px -6px rgba(226, 55, 41, 0.35);
    /* background: linear-gradient(120deg, #E23729, #EC5E29); */
    background: linear-gradient(105deg, #E23729 0%, #EC5E29 100%);
    border: none;
    /* color: #c0392b; */
    color: white;
  }

  .auth-btn:hover {
    /* filter: brightness(0.98); */
    transform: scale(0.98);
    box-shadow: 0 4px 14px -4px rgba(226, 55, 41, 0.45);
    background: linear-gradient(105deg, #D42C1F 0%, #E35222 100%);
    color: white;
  }

  .alert-soft {
    /* background: #f0fbf7; */
    background: #FCF1F0;
    /* 透明浅红 */
    /* border: 1px solid #cde9e3; */
    border: none;
    /* color: #1f6f5e; */
    color: #C23D2F;
    border-radius: 12px;
    padding: 12px 14px;
    font-weight: 600;
  }
</style>

<div class="auth-page">
  <div class="auth-card">
    <div class="auth-header">验证您的邮箱</div>
    <div class="auth-body">
      @if (session('resent'))
      <div class="alert-soft mb-3">
        新的验证链接已发送到您的邮箱。
      </div>
      @endif

      <p>请先前往邮箱点击验证链接，再继续使用本站功能。</p>
      <p class="mb-4">如果没有收到邮件，可以点击下面按钮重新发送：</p>

      <form method="POST" action="{{ route('second_send_email',$user->id) }}">
        @csrf
        <button type="submit" class="btn auth-btn">重新发送验证邮件</button>
      </form>
    </div>
  </div>
</div>
@endsection