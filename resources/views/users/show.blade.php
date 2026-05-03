@extends('layouts.app')
@section('title', '个人主页')

@section('content')

<style>
  .profile-page {
    background: linear-gradient(135deg, #fff8f7 0%, #fff5f0 50%, #fff9f7 100%);
    padding: 24px 12px 32px;
    border-radius: 14px;
  }
  .profile-layout {
    display: grid;
    grid-template-columns: 280px minmax(0, 1fr) 320px;
    gap: 24px;
    align-items: start;
  }
  @media (max-width: 1200px) {
    .profile-layout {
      grid-template-columns: 260px minmax(0, 1fr) 280px;
    }
  }
  @media (max-width: 992px) {
    .profile-layout {
      grid-template-columns: 1fr;
    }
    .profile-nav {
      position: static;
    }
  }
  .profile-sidebar,
  .profile-main,
  .profile-nav {
    width: 100%;
  }
  .profile-main {
    min-width: 0;
  }
  .profile-main > [class*="col-"] {
    margin-left: 0 !important;
    padding-left: 0;
    padding-right: 0;
    max-width: 100%;
    flex: 1 1 auto;
  }
  .profile-card {
    border: none;
    border-radius: 18px;
    box-shadow: 0 14px 38px rgba(226, 55, 41, 0.09);
    overflow: hidden;
    background: #fff;
  }
  .profile-card img {
    border-radius: 14px;
  }
  .profile-section-title {
    font-weight: 700;
    color: #c0392b;
  }
  .nav-card {
    border: none;
    border-radius: 14px;
    box-shadow: 0 10px 24px rgba(226, 55, 41, 0.08);
    overflow: hidden;
  }
  .profile-nav {
    position: sticky;
    top: 86px;
  }
  .profile-nav .nav-card {
    margin-bottom: 18px;
  }
  .nav-card .list-group-item {
    border: none;
  }
  .nav-card .list-group-item-action.active,
  .nav-card .list-group-item-action:hover {
    background: linear-gradient(120deg, #E23729, #EC5E29);
    color: #fff;
    font-weight: 600;
  }
</style>

<div class="profile-page">
  <div class="profile-layout">
    <div class="profile-sidebar mb-3 user-info">
      <div class="card profile-card p-3">
        <ul class="list-group list-group-flush">
          <a href="{{ route('user_edit_avatar',$user->id) }}">
            <img src="{{ $user->avatar }}" class="card-img-top mt-2" style="width: 100%; height:262px; object-fit: cover;" alt="{{ $user->name }}">
          </a>
          <div class="mt-3 mb-1" style="height:1px; background-color:#FCF1F0;"></div>

          <div class="card-body pt-2 pb-2">
            <h5 class="card-title profile-section-title"><strong>个性签名</strong></h5>
            <p class="card-text">{{ $user->signature }}</p>
          </div>
          <div class="mt-2 mb-1" style="height:1px; background-color:#FCF1F0;"></div>

          <div class="card-body pt-2 pb-2">
            <h5 class="card-title profile-section-title"><strong>信誉分</strong></h5>
            <p class="card-text">
              <span style="color: #E23729; font-weight: 600; font-size: 18px;">
                {{ number_format($user->rating_score ?? 0, 2) }}
              </span>
              <span style="color: #636b6f; font-size: 14px; margin-left: 8px;">
                ({{ $user->rating_count ?? 0 }}人评价)
              </span>
            </p>
          </div>
          <div class="mt-2 mb-1" style="height:1px; background-color:#FCF1F0;"></div>

          <div class="card-body pt-2 pb-3">
            <h5 class="card-title profile-section-title"><strong>注册于</strong></h5>
            <p class="card-text" title="{{ $user->created_at }}">{{ $user->created_at->diffForHumans() }}</p>
          </div>

          <div class="card-body pt-2 pb-3">
            @if(Auth::user()->can('update_user_info', $user))
            <a href="{{ route('user_edit',Auth::user()) }}" class="btn w-100 edit_info" style="border-radius:12px;border:1.5px solid #E23729;color:#E23729;font-weight:600;">
              <i class="fas fa-user-edit"></i>
              编辑个人资料
            </a>
            @endif
          </div>
        </ul>
      </div>
    </div>

    <!-- 中间信息 -->
    <div class="profile-main">
      @yield('user_info')
    </div>

    <!-- 右侧栏链�?-->
    <div class="profile-nav mt-3 mt-lg-0">
      <div class="list-group" style="width: 100%;">

        <div class="card nav-card">
          <li class="list-group-item ">
            <strong style="font-size: 16px; color:#c0392b;">
              @if (Auth::user()->can('update_user_info', $user))
              我的信息
              @else
              Ta 的信息
              @endif
            </strong>
          </li>

          <a href="{{ route('user_show',$user->id) }}" class="list-group-item list-group-item-action {{ user_center_active(0) }}">
            <i class="fas fa-user mr-2"></i>
              个人信息
          </a>

          <a href="{{ route('user_booking' , $user->id) }}?reply=no" class="list-group-item list-group-item-action {{ user_center_active(1) }}">
            <i class="fas fa-heart mr-1"></i>
            @if (Auth::user()->can('update_user_info', $user))
              我的预订
            @else
              Ta 的预订
            @endif
            
          </a>
          <a href="{{ route('user_comment' , $user->id)  }}" class="list-group-item list-group-item-action {{ user_center_active(2) }}">
            <i class="fab fa-twitch mr-1" style="font-size:16px"></i>
            @if (Auth::user()->can('update_user_info', $user))
              我的评论
            @else
              Ta 的评论
            @endif
            
          </a>

          <a href="{{ route('buy_goods',$user->id) }}?type=booking" class="list-group-item list-group-item-action  {{ user_center_active(3) }}">
            <i class="fas fa-shopping-cart " style="font-size: 15px;position:relative;left:-3px"></i>
            <span>订购商品</span>
          </a>

          <a href="{{ route('buyer_order',$user->id) }}?type=pending" class="list-group-item list-group-item-action {{ user_center_active(4) }}">
            <i class="fas fa-clipboard-list mr-1" style="font-size: 16px;"></i>
            <span>
            @if (Auth::user()->can('update_user_info', $user))
              我的订单
            @else
              Ta 的订单
            @endif
              
            </span>
          </a>

        </div>

        <div class="card nav-card mt-3">
          <li class="list-group-item "> 
            <strong style="font-size: 16px; color:#c0392b;">
            @if (Auth::user()->can('update_user_info', $user))
              我的店铺
            @else
              Ta 的店铺
            @endif
            </strong>
            
          </li>

          <a href="{{ route('sale_goods',$user->id) }}" class="list-group-item list-group-item-action {{ user_center_active(5) }}">
            <i class="fas fa-store"></i>
            <span>发布商品</span>
          </a>

          <a href="{{ route('booking_notice',$user->id) }}?reply=no" class="list-group-item list-group-item-action {{ user_center_active(6) }}">
            <i class="far fa-envelope" style="font-size:17px"></i>
            <span class="ml-1">预订通知</span>
          </a>

          <a href="{{ route('seller_order',$user->id) }}?type=pending" class="list-group-item list-group-item-action {{ user_center_active(7) }}">
            <img src="@if(user_center_active(7)=='active') /images/iconfont/order.png @else /images/iconfont/order_black.png @endif" alt="" style="width: 18px; height:18px; position:relative; top:-3px;left:0px">
            <span>出售订单</span>
          </a>

        </div>

        <div class="card nav-card mt-3">
          <li class="list-group-item "> 
            <strong style="font-size: 16px; color:#c0392b;">
            @if (Auth::user()->can('update_user_info', $user))
              我的心愿单
            @else
              Ta 的心愿单
            @endif
            </strong>
          </li>

          <a href="{{ route('sale_goods',$user->id) }}?mode=wish" class="list-group-item list-group-item-action {{ user_center_active(8) }}">
            <i class="far fa-star"></i>
            <span>发布心愿</span>
          </a>

          <a href="{{ route('booking_notice',$user->id) }}?mode=wish&reply=no" class="list-group-item list-group-item-action {{ user_center_active(9) }}">
            <i class="far fa-envelope" style="font-size:17px"></i>
            <span class="ml-1">预定通知</span>
          </a>

          <a href="{{ route('seller_order',$user->id) }}?mode=wish&type=pending" class="list-group-item list-group-item-action {{ user_center_active(10) }}">
            <i class="fas fa-hand-holding-heart" style="font-size: 16px;"></i>
            <span>实现心愿</span>
          </a>

        </div>

      </div>
    </div>

  </div>
</div>

@stop
