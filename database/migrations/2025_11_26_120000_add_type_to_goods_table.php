<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToGoodsTable extends Migration
{
  public function up()
  {
    Schema::table('goods', function (Blueprint $table) {
      // 1=发布商品，2=发布求购信息
      $table->tinyInteger('type')->default(1)->after('category_id');
    });
  }

  public function down()
  {
    Schema::table('goods', function (Blueprint $table) {
      $table->dropColumn('type');
    });
  }
}


