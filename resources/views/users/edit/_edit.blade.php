@extends('layouts.app')
@section('title', '编辑资料')

@section('content')

<style>
  .edit-page {
    background: linear-gradient(135deg, #fff8f7 0%, #fff5f0 50%, #fff9f7 100%);
    padding: 24px 16px 32px;
    border-radius: 14px;
  }
  .edit-nav {
    border: none;
    border-radius: 16px;
    box-shadow: 0 12px 30px rgba(226, 55, 41, 0.09);
    overflow: hidden;
    background: #fff;
  }
  .edit-nav .list-group-item {
    border: none;
    font-weight: 600;
    color: #4a2020;
  }
  .edit-nav .list-group-item-action.active,
  .edit-nav .list-group-item-action:hover {
    background: linear-gradient(120deg, #E23729, #EC5E29);
    color: #c0392b;
  }
</style>

<div class="edit-page">
  <div class="row">
    <!-- 左侧·路由 -->
    <div class="col-lg-3 col-md-4 mb-3">
      <div class="list-group edit-nav">
        <a href="{{ route('user_edit', Auth::user()) }}" class="list-group-item list-group-item-action {{ user_info_active(0) ? 'active' : '' }}">
          <i class="fas fa-user mr-2"></i> 基本信息
        </a>

        <a href="{{ route('user_edit_avatar', Auth::user()) }}" class="list-group-item list-group-item-action {{ user_info_active(1) ? 'active' : '' }}">
          <i class="far fa-image mr-2" style="font-size: 18px;"></i> 修改头像
        </a>
        <a href="{{ route('user_edit_password', Auth::user()) }}" class="list-group-item list-group-item-action {{ user_info_active(2) ? 'active' : '' }}">
          <i class="fas fa-lock mr-2"></i> 修改密码
        </a>
        <a href="{{ route('user_edit_visible' , Auth::user()) }}" class="list-group-item list-group-item-action {{ user_info_active(3) ? 'active' : '' }}">
          <i class="fas fa-eye mr-2"></i> 显示设置
        </a>
      </div>
    </div>

    <!-- 右边信息 -->
    @yield('edit_info')

  </div>
</div>

@stop

@section('scriptsAfterJs')
<script>
  $(document).ready(function() {

  })
</script>


@stop
