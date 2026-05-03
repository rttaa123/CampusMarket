<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id')->comment('订单ID');
            $table->unsignedInteger('rater_id')->comment('评分者ID');
            $table->unsignedInteger('rated_id')->comment('被评分者ID');
            $table->decimal('score', 3, 2)->comment('评分(0-5分)');
            $table->text('comment')->nullable()->comment('评价内容');
            $table->timestamps();
            
            $table->index('order_id');
            $table->index('rater_id');
            $table->index('rated_id');
            $table->unique(['order_id', 'rater_id']); // 每个订单每个评分者只能评分一次
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_ratings');
    }
}
