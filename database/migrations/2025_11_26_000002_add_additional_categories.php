<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AddAdditionalCategories extends Migration
{
    protected $newCategories = [
        ['name' => '食物',   'description' => '餐饮、零食、饮品'],
        ['name' => '跑腿',   'description' => '代买代办、取送服务'],
        ['name' => '租借',   'description' => '租借物品、共享服务'],
        ['name' => '心愿单', 'description' => '发布心愿、求购信息'],
    ];

    public function up()
    {
        $now = Carbon::now();
        foreach ($this->newCategories as $category) {
            $exists = DB::table('categories')->where('name', $category['name'])->exists();
            if (!$exists) {
                DB::table('categories')->insert([
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down()
    {
        foreach ($this->newCategories as $category) {
            DB::table('categories')->where('name', $category['name'])->delete();
        }
    }
}

