<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationFieldsToGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goods', function (Blueprint $table) {
            $table->string('address', 255)->default(NULL)->comment('商品地址')->nullable();
            // 纬度，使用 DOUBLE 类型存储高精度浮点数
            $table->double('latitude', 10, 6)->default(NULL)->comment('纬度')->nullable();
            // 经度
            $table->double('longitude', 10, 6)->default(NULL)->comment('经度')->nullable();
            // 城市信息
            $table->string('city', 50)->default(NULL)->comment('城市')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goods', function (Blueprint $table) {
            $table->dropColumn(['address', 'latitude', 'longitude', 'city']);
        });
    }
}
