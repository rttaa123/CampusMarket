<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Encore\Admin\Traits\DefaultDatetimeFormat;    // 后台日期格式
use Auth;

class User extends Authenticatable
{
  //use Notifiable;
  use DefaultDatetimeFormat;

  use Notifiable {
    notify as protected BookingNotify;    // 重命名，需要重写
  }


  protected $fillable = [
    'name', 'email', 'password', 'avatar', 'activated', 'activation_token',
    'sex', 'signature', 'phone',  'faculty', 'number', 'r_name',      // 禁止修改学校 
    'rating_count', 'rating_score',  // 信誉评价相关
  ];

  protected $hidden = [
    'password', 'remember_token',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
  ];


  public static function boot() // boot-用户模型类完成初始化之后进行加载
  {
    parent::boot();

    static::creating(function ($user) {
      $user->activation_token = Str::random(10);  // 生成邮箱令牌
    });
  }

  


  // 预定通知-重写notify，未读+1
  public function notify($instance)
  {
    
    // if ($this->id == Auth::id()) {
    //   return;
    // }

    // 判断是否有该方法，是数据库类型通知才提醒
    if (method_exists($instance, 'toDatabase')) {
      $this->increment('notification_count');       // 未读+1
    }

    $this->BookingNotify($instance);
  }


  // 消除已读
  public function ClearRead(){
    $this->notification_count = 0;
    $this->save();
    $this->unreadNotifications->markAsRead();   // 通过更新 read_at 时间
  }
  


  public function goods()
  {
    return $this->hasMany(Good::class);     // 一个用户有多个商品
  }

  public function comments()
  {
    return $this->hasMany(Comment::class);     // 一个用户有多个评论
  }

  public function bookings()
  {
    return $this->hasMany(Booking::class, 'booker_id');     // 一个买家有多个预订
  }
  public function bookingsUser()
  {
    return $this->hasMany(Booking::class, 'user_id');     // 一个卖家有多个预订通知
  }

  public function userVisibles()       // 一个用户有一个 可见设置
  {
    return $this->hasOne(UserVisible::class);
  }


  // 一个用户有多个订单
  public function buyerOrders()
  {
    return $this->hasMany(Order::class, 'buyer_id')->where('is_delete',Order::not_deleted);     // 一个买家有多个订单
  }
  public function sellerOrders()
  {
    return $this->hasMany(Order::class, 'user_id');     // 一个卖家有多个订单
  }

  // 收到的评分
  public function receivedRatings()
  {
    return $this->hasMany(\App\Models\UserRating::class, 'rated_id');
  }

  // 给出的评分
  public function givenRatings()
  {
    return $this->hasMany(\App\Models\UserRating::class, 'rater_id');
  }

  // 更新信誉分
  public function updateRating($newScore)
  {
    $oldCount = $this->rating_count ?? 0;
    $oldScore = $this->rating_score ?? 0.00;

    if ($oldCount == 0) {
      // 第一次评分
      $this->rating_score = round($newScore, 2);
      $this->rating_count = 1;
    } else {
      // 计算新的平均分：(原评价人数*原信誉分+新信誉分)/(原评价人数+1)
      $newAverage = ($oldCount * $oldScore + $newScore) / ($oldCount + 1);
      $this->rating_score = round($newAverage, 2);
      $this->rating_count = $oldCount + 1;
    }

    $this->save();
  }
}
