<?php

use Illuminate\Support\Facades\Route;

// ！！！ 确保所有控制器类都已正确引入 ！！！
use App\Http\Controllers\PagesController;
use App\Http\Controllers\GoodsController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserRatingController;

Route::redirect('/', 'goods')->name('root'); // 首页跳转

/* 商品展示 */
Route::get('goods', [PagesController::class, 'root'])->name('home'); // 主页-商品列表
Route::get('/category/{category_id}', [PagesController::class, 'category_show'])->name('category'); // 商品列表分类
Route::get('/goods_search', [GoodsController::class, 'goods_search'])->name('goods_search'); // 处理商品搜索
Route::get('/goods/{goods_id}/detail', [GoodsController::class, 'goods_detail'])->name('goods_detail'); // 商品详情页
Route::get('/goods/goods_hot', [GoodsController::class, 'goods_hot'])->name('goods_hot'); // 热门商品


/*发布商品*/
Route::get('/create_goods', [GoodsController::class, 'create_goods'])->name('create_goods'); // 发布商品页面
// 处理创建商品的 POST 请求
Route::post('/goods', [GoodsController::class, 'create_goods_check'])->name('create_goods_check'); // 【已修改】使用 create_goods_check 作为路由名
Route::get('/edit_goods/{goods_id}', [GoodsController::class, 'edit_goods'])->name('edit_goods'); // 编辑商品页面
// 处理编辑商品的 PUT 请求
Route::put('/goods/{goods_id}', [GoodsController::class, 'create_goods_check'])->name('goods.update'); // 处理编辑商品


/* 评论 */
Route::post('/goods/{goods_id}/detail/comments', [CommentsController::class, 'goods_detail_comment'])->name('goods_comment'); // 参与评论
Route::delete('/goods/{goods_id}/{comments_id}/delete', [CommentsController::class, 'delete_comment'])->name('delete_comment'); // 删除评论
Route::get('/users/{user}/user_comment/search', [CommentsController::class, 'search_comment'])->name('search_comment'); // 搜索


/* 预订商品 */
Route::post('/goods/{goods}/{user_id}', [BookingsController::class, 'ajax_booking_goods'])->name('ajax_booking_goods');
Route::get('/goods/booking_count/{goods}', [BookingsController::class, 'ajax_booking_count'])->name('ajax_booking_count'); // 预定次数
Route::delete('/goods/cancel_booking/{goods}', [BookingsController::class, 'ajax_cancel_booking'])->name('ajax_cancel_booking'); // 取消预定
Route::get('/goods/ajax_operate_premise', [BookingsController::class, 'ajax_operate_premise'])->name('ajax_operate_premise'); // 预定前提


/* 注册 */
Route::get('/signup', [UsersController::class, 'create'])->name('signup'); // 注册页面
Route::post('/register', [UsersController::class, 'register'])->name('users.register'); // 处理注册表单信息
Route::get('ajax_name/{name}', [UsersController::class, 'name_ajax'])->name('name_ajax'); // ajax验证用户名
Route::get('ajax_email/{email}', [UsersController::class, 'email_ajax'])->name('email_ajax'); // ajax验证邮箱


/* 登录 */
Route::get('login', [SessionsController::class, 'login'])->name('login'); // 登录页面
Route::post('login', [SessionsController::class, 'login_check'])->name('login_check'); // 处理登录信息
Route::post('logout', [SessionsController::class, 'login_out'])->name('login_out'); // 退出登录

/* 重设密码 */
Route::get('password/reset', [SessionsController::class, 'password_send_email'])->name('password_send_email'); // 重设密码
Route::post('password/reset', [SessionsController::class, 'verify_password_send_email'])->name('verify_password_send_email'); // 处理重设密码 邮件
Route::get('password/reset/{token}', [SessionsController::class, 'reset_password'])->name('reset_password'); // 密码修改
Route::post('password/reset/verify', [SessionsController::class, 'verify_reset_password'])->name('verify_reset_password'); // 处理面修改


/* 用户个人中心 */
Route::get('/users/{user}', [UsersController::class, 'user_show'])->name('user_show'); // 主页展示
Route::get('/users/{user}/user_booking', [UsersController::class, 'user_booking'])->name('user_booking'); // 我的预订
Route::get('/users/{user}/user_comment', [UsersController::class, 'user_comment'])->name('user_comment'); // 我的评论
Route::get('/users/{user}/buy_goods', [UsersController::class, 'buy_goods'])->name('buy_goods'); // 订购商品(买家)


Route::get('/users/{user}/buyer_order', [UsersController::class, 'buyer_order'])->name('buyer_order'); // 我的订单(买家)
Route::get('/users/{user}/buyer_order/search', [UsersController::class, 'search_buyer_order'])->name('search_buyer_order'); // 搜索-我的订单(买家)
Route::post('/buyer_refuse_order/{order}', [OrdersController::class, 'buyer_refuse_order'])->name('buyer_refuse_order'); // 拒绝订单(买家)
Route::post('/buyer_confirm_order/{order}', [OrdersController::class, 'buyer_confirm_order'])->name('buyer_confirm_order'); // 同意订单(买家)
Route::post('/buyer_cancel_order/{order}', [OrdersController::class, 'buyer_cancel_order'])->name('buyer_cancel_order'); // 取消订单(买家)


Route::get('/users/{user}/sale_goods/{state?}', [UsersController::class, 'sale_goods'])->name('sale_goods'); // 发布商品
Route::delete('ajax_del_gods/{goods}', [UsersController::class, 'del_goods_ajax'])->name('del_goods_ajax'); // ajax删除 发布商品
Route::get('/users/{user}/sale_goods/{state}/search', [UsersController::class, 'search_sale_goods'])->name('search_sale_goods'); // 搜索-发布商品


Route::get('/users/{user}/booking_notice', [UsersController::class, 'booking_notice'])->name('booking_notice'); // 预订通知
Route::post('/agree_booking/{booking_id}', [BookingsController::class, 'agree_booking'])->name('agree_booking'); // 接受预订
Route::post('/refuse_booking/{booking_id}', [BookingsController::class, 'refuse_booking'])->name('refuse_booking'); // 拒绝预订

Route::get('/users/{user}/seller_order', [UsersController::class, 'seller_order'])->name('seller_order'); // 出售订单(卖家)
Route::get('/users/{user}/seller_order/search', [UsersController::class, 'search_seller_order'])->name('search_seller_order'); // 出售订单(卖家)
Route::post('/seller_send_order/{order}', [OrdersController::class, 'seller_send_order'])->name('seller_send_order'); // 发送确认订单(卖家)
Route::post('/seller_cancel_order/{order}', [OrdersController::class, 'seller_cancel_order'])->name('seller_cancel_order'); // 取消订单(卖家)

Route::get('/users/{user}/settings/edit', [UsersController::class, 'edit'])->name('user_edit'); // 修改个人信息
Route::post('/users/{user}/settings/check_edit', [UsersController::class, 'edit_check'])->name('user_edit_check'); // 修改基本信息表单

Route::get('/users/{user}/settings/edit_avatar', [UsersController::class, 'edit_avatar'])->name('user_edit_avatar'); // 修改头像
Route::put('/users/{user}/settings/check_avatar', [UsersController::class, 'avatar_check'])->name('user_edit_avatar_check');// 修改头像表单

Route::get('/users/{user}/settings/edit_password', [UsersController::class, 'edit_password'])->name('user_edit_password'); // 修改密码
Route::post('/users/{user}/settings/check_password', [UsersController::class, 'password_check'])->name('user_edit_password_check');// 修改密码表单

Route::get('/users/{user}/settings/edit_visible', [UsersController::class, 'edit_visible'])->name('user_edit_visible'); // 显示设置
Route::get('ajax_visible', [UsersController::class, 'ajax_visible'])->name('ajax_visible'); // ajax获取显示设置
Route::post('/ajax_visible_data/{user_visible}', [UsersController::class, 'ajax_visible_data'])->name('ajax_visible_data'); // ajax修改显示设置


/* 邮箱认证 */
Route::get('/signup/email/verify/{user}', [SessionsController::class, 'show_verify'])->name('show_verify'); // 验证界面
Route::post('/signup/email/verify/{user}', [SessionsController::class, 'second_send_email'])->name('second_send_email'); // 再次发送邮件
Route::get('/signup/email/activate/{token}', [SessionsController::class, 'signup_verify'])->name('signup_verify'); // 邮箱激活


// 消息通知
Route::get('/notifications', [NotificationsController::class, 'notifications'])->name('notifications'); //

// 站内信路由组
Route::prefix('messages')->middleware('auth')->group(function () {
    // GET /messages (收件箱入口)
    Route::get('/', [MessageController::class, 'index'])->name('messages.index');
    
    // GET /messages/{user} (显示对话内容)
    Route::get('/{user}', [MessageController::class, 'show'])
         ->name('messages.show')
         ->where('user', '[0-9]+'); 
         
    // GET /messages/null (处理没有用户ID的情况)
    Route::get('/null', [MessageController::class, 'show'])->name('messages.show_empty'); 

    // POST /messages/{user} (处理消息发送)
    Route::post('/{user}', [MessageController::class, 'send'])->name('messages.send');
});
Route::get('/messages/{user}/latest', [MessageController::class, 'fetchLatestMessages'])->name('messages.latest'); // 获取最新消息AJAX
Route::get('/messages/conversations', [MessageController::class, 'fetchConversationList'])->name('messages.conversations');

Route::middleware('auth')->group(function () {
    
    // 对话列表入口（index 会重定向到最近的对话）
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    
    // 显示特定用户的对话
    Route::get('/messages/{user?}', [MessageController::class, 'show'])->name('messages.show');
    
    // 发送消息
    Route::post('/messages/{user}', [MessageController::class, 'send'])->name('messages.send');

    // AJAX 获取当前对话最新消息 (右侧面板轮询)
    Route::get('/messages/fetch/{user}', [MessageController::class, 'fetchLatestMessages'])->name('messages.fetch');
    
    // AJAX 获取侧边栏对话列表 (左侧面板轮询)
    Route::get('/messages/conversations', [MessageController::class, 'fetchConversationList'])->name('messages.conversations');

});

// 信誉评价
Route::post('/user-ratings/{order}', [UserRatingController::class, 'store'])->name('user_ratings.store');