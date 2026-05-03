<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Encore\Admin\Traits\DefaultDatetimeFormat;

class UserRating extends Model
{
    use DefaultDatetimeFormat;

    protected $fillable = [
        'order_id',
        'rater_id',
        'rated_id',
        'score',
        'comment',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    // 评分者
    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_id');
    }

    // 被评分者
    public function rated()
    {
        return $this->belongsTo(User::class, 'rated_id');
    }

    // 订单
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}

