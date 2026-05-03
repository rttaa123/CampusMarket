<?php

namespace App\Models;

// 【删除这行】use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- 移除工厂引用
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    // 【删除这行】use HasFactory; // <-- 移除工厂特性

    protected $table = 'messages';
    protected $primaryKey = 'message_id'; 
    public $timestamps = false; // 禁用 Laravel 默认时间戳
    
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'is_read',
    ];

    // 关系：消息属于发送者 (User Model)
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'id'); 
    }

    // 关系：消息属于接收者 (User Model)
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }
}