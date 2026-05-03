<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddGoodsMenuToAdminMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 检查菜单是否已存在
        $menuExists = DB::table('admin_menu')->where('uri', 'goods')->exists();
        
        if (!$menuExists) {
            // 获取当前最大 order 值
            $maxOrder = DB::table('admin_menu')->max('order') ?? 0;
            
            // 插入商品管理菜单项
            DB::table('admin_menu')->insert([
                'parent_id' => 0,
                'order' => $maxOrder + 1,
                'title' => '商品管理',
                'icon' => 'fa-shopping-bag',
                'uri' => 'goods',
                'permission' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 删除商品管理菜单项
        DB::table('admin_menu')->where('uri', 'goods')->delete();
    }
}
