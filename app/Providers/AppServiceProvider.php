<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    // 开发环境加载-用户切换工具包
    if (app()->isLocal()) {   // isLocal(.env文件的APP_ENV=local)-是local则返回true，表示只在本地环境注册
      $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
    }
  }


  public function boot()
  {
    \App\Models\Booking::observe(\App\Observers\BookingObserver::class);  // 预定模型

    if (Schema::hasTable('categories')) {
      View::composer('layouts._header', function ($view) {
        $view->with('navCategories', Category::orderBy('id')->get());
      });
    }

    // 在所有视图中注入管理员登录页面的返回链接脚本
    View::composer('*', function ($view) {
      if (request()->is('admin/auth/login') || request()->path() === 'admin/auth/login') {
        $view->with('injectUserLoginLink', true);
      }
    });
  }
}
