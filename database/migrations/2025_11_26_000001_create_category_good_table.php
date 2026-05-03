<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryGoodTable extends Migration
{
    public function up()
    {
        Schema::create('category_good', function (Blueprint $table) {
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('good_id');

            $table->primary(['category_id', 'good_id']);
            $table->index('good_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_good');
    }
}

