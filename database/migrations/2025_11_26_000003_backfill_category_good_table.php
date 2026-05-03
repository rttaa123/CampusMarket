<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class BackfillCategoryGoodTable extends Migration
{
    public function up()
    {
        $goods = DB::table('goods')->select('id', 'category_id')->get();

        foreach ($goods as $good) {
            if (!$good->category_id) {
                continue;
            }

            $exists = DB::table('category_good')
                ->where('good_id', $good->id)
                ->where('category_id', $good->category_id)
                ->exists();

            if (!$exists) {
                DB::table('category_good')->insert([
                    'good_id' => $good->id,
                    'category_id' => $good->category_id,
                ]);
            }
        }
    }

    public function down()
    {
        DB::table('category_good')->truncate();
    }
}

