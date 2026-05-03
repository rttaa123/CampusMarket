<?php

namespace App\Http\Controllers;

use App\Models\UserRating;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class UserRatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 提交评分
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:5',
        ]);

        $order = Order::findOrFail($orderId);
        
        // 检查订单是否已生效
        $isEffective = ($order->seller_state == 1 && ($order->buyer_state == 2 || $order->buyer_state == 4));
        
        if (!$isEffective) {
            return response()->json(['error' => '订单尚未生效，无法评分'], 400);
        }

        $currentUserId = Auth::id();
        $isBuyer = ($order->buyer_id == $currentUserId);
        $isSeller = ($order->user_id == $currentUserId);

        if (!$isBuyer && !$isSeller) {
            return response()->json(['error' => '无权访问此订单'], 403);
        }

        // 确定被评分者
        $ratedUserId = $isBuyer ? $order->user_id : $order->buyer_id;

        // 检查是否已经评分过
        $existingRating = UserRating::where('order_id', $orderId)
            ->where('rater_id', $currentUserId)
            ->first();

        if ($existingRating) {
            return response()->json(['error' => '您已经对此订单评分过了'], 400);
        }

        // 创建评分记录
        $rating = UserRating::create([
            'order_id' => $orderId,
            'rater_id' => $currentUserId,
            'rated_id' => $ratedUserId,
            'score' => $request->score,
            'comment' => $request->comment,
        ]);

        // 更新被评分者的信誉分
        $ratedUser = User::find($ratedUserId);
        $ratedUser->updateRating($request->score);

        return response()->json([
            'success' => true,
            'message' => '评分成功',
            'rating' => $rating,
        ]);
    }
}
