<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id('message_id'); 
            
            // <<< 修正点 1：使用 unsignedInteger 匹配旧系统的 INT 类型
            $table->unsignedInteger('sender_id'); 
            $table->unsignedInteger('receiver_id');
            // -----------------------------------------------------------------
            
            $table->text('content'); 
            $table->timestamp('timestamp')->useCurrent();
            $table->boolean('is_read')->default(false); 
            
            $table->index(['sender_id', 'receiver_id', 'timestamp']);
            
            // <<< 修正点 2：手动添加外键约束，明确引用 'id'
            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('receiver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
