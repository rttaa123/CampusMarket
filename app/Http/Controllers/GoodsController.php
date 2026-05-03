<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Good;
use App\Models\GoodTag;
use App\Models\Order;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GoodsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', [
            // 商品详情 / 列表、热门、搜索 对游客开放，其余需登录
            'except' => ['goods_search', 'goods_hot', 'goods_detail'],
        ]);
    }

    /* Publish goods */
    public function create_goods(GoodTag $good_tag)
    {
        $good_tag = GoodTag::get();
        $categories = Category::orderBy('id')->get();
        $selectedCategories = [];

        // pending booking?
        $booking = Booking::where('user_id', Auth::user()->id)
            ->where('user_state', Booking::seller_processing_booking)
            ->exists();

        // pending order?
        $order = Order::where('user_id', Auth::user()->id)
            ->where('seller_state', Order::seller_pending_order)
            ->orWhere(function ($query) {
                $query->where('buyer_id', Auth::user()->id)
                    ->where('buyer_state', Order::buyer_pending_order)
                    ->where('seller_state', Order::seller_confirm_order);
            })->exists();

        if ($booking || $order) {
            session()->flash('danger', 'You still have pending bookings or orders. Please handle them before publishing goods.');
            return redirect()->back();
        }

        return view('goods.create_edit_goods', compact('good_tag', 'booking', 'order', 'categories', 'selectedCategories'));
    }

    // Save goods
    public function create_goods_check(Request $request, Good $good, ImageUploadHandler $uploader)
    {
        // 1. 数据验证：新增地理位置字段的验证
        $this->validate($request, [
            'title' => ['required', 'max:255'],
            'description' => ['required', 'max:512'],
            // 定位字段现在是 'required'，因为禁用定位时也会传特殊字符串/数字
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $goods = $request->all();
        $publishType = $request->input('publish_type', 'good');
        $goods['type'] = $publishType === 'buy' ? Good::TYPE_BUY : Good::TYPE_GOOD;

        // **2. 清理地理位置数据：将特殊值（禁用定位的标识）转换为 NULL**
        // 定义特殊值 (必须与前端 JS 代码保持一致)
        $SPECIAL_STRING = 'NO';
        $SPECIAL_LAT_LNG = 0.0000000;
        
        $isLocationUnspecified = (
            $goods['address'] === $SPECIAL_STRING || 
            (isset($goods['latitude']) && (float)$goods['latitude'] == $SPECIAL_LAT_LNG) ||
            (isset($goods['longitude']) && (float)$goods['longitude'] == $SPECIAL_LAT_LNG)
        );

        // 如果检测到特殊值，则存为 NULL，以保持数据库整洁
        if ($isLocationUnspecified) {
            $goods['address'] = null;
            $goods['city'] = null;
            $goods['latitude'] = null;
            $goods['longitude'] = null;
        }
        
        // ----------------------------------------------------
        // ** 以下是您原有的图片和数据校验逻辑，保持不变 **
        // ----------------------------------------------------

        if ($goods['goods_old_img'] == null && !isset($goods['goods_img'])) {
            session()->flash('null_data', 'Missing required fields, please complete and submit again.');
            return redirect()->back();
        }

        if (in_array(null, $goods) && $goods['goods_old_img'] != null) {
            session()->flash('null_data', 'Missing required fields, please complete and submit again.');
            return redirect()->back();
        }

        $goods['image'] = '';

        if ($goods['goods_old_img']) { // edit
            $goods['goods_old_img'] = explode(',', $goods['goods_old_img']);
            for ($i = 0; $i < sizeof($goods['goods_old_img']); $i++) {
                if ($goods['goods_old_img'][$i] === 'update') {
                    $saved = $uploader->save($goods['goods_img'][$i], 'goods', Auth::user()->id);
                    if ($saved) {
                        $goods['image'] .= $saved['path'] . ',';
                    } else {
                        session()->flash('wrong_type', 'Please upload images in GIF / JPG / JPEG / PNG format.');
                        return redirect()->back();
                    }
                } else {
                    $goods['image'] .= $goods['goods_old_img'][$i] . ',';
                }
            }
        } else { // create
            for ($i = 0; $i < sizeof($goods['goods_img']); $i++) {
                $saved = $uploader->save($goods['goods_img'][$i], 'goods', Auth::user()->id);
                if ($saved) {
                    $goods['image'] .= $saved['path'] . ',';
                } else {
                    session()->flash('wrong_type', 'Please upload images in GIF / JPG / JPEG / PNG format.');
                    return redirect()->back();
                }
            }
        }

        $image_toArray = explode(',', $goods['image']);
        array_pop($image_toArray);
        $goods['image'] = $image_toArray;

        $goods['user_id'] = Auth::user()->id;
        $goods['state'] = $request->goods_state;
        $goods['tags'] = $request->tag_data;

        $categoryIds = collect(explode('-', $request->categories_data))
            ->filter(function ($value) {
                return $value !== '' && is_numeric($value);
            })
            ->map(function ($value) {
                return (int) $value;
            })
            ->unique()
            ->values();

        if ($categoryIds->isEmpty()) {
            session()->flash('null_data', '请选择至少一个分区。');
            return redirect()->back()->withInput();
        }

        $validCategoryIds = Category::whereIn('id', $categoryIds)->pluck('id');

        if ($validCategoryIds->isEmpty()) {
            session()->flash('null_data', '选择的分区无效，请重新选择。');
            return redirect()->back()->withInput();
        }

        // 如果是求购信息，则额外挂到「心愿单」分区下显示
        if (isset($goods['type']) && $goods['type'] == Good::TYPE_BUY) {
            $wishCategoryId = Category::where('name', '心愿单')->value('id');
            if ($wishCategoryId && !$validCategoryIds->contains($wishCategoryId)) {
                $validCategoryIds->push($wishCategoryId);
            }
        }

        $goods['category_id'] = $validCategoryIds->first();

        if ($goods['goods_old_img']) {
            $goodModel = Good::findOrFail($goods['id']);
            
            // 更新操作：包含地理位置字段
            $updateData = [
                'title' => $goods['title'],
                'description' => $goods['description'],
                'image' => $goods['image'],
                'state' => $goods['state'],
                'price' => $goods['price'],
                'old_price' => $goods['old_price'],
                'category_id' => $goods['category_id'],
                'tags' => $goods['tags'],
                // **新增：定位字段**
                'address' => $goods['address'],
                'city' => $goods['city'],
                'latitude' => $goods['latitude'],
                'longitude' => $goods['longitude'],
            ];

            $goodModel->update($updateData);
            $goodModel->categories()->sync($validCategoryIds->all());
        } else {
            // 新增操作：fill 方法会自动包含地理位置字段
            $good->fill($goods); 
            $good->save();
            $good->categories()->sync($validCategoryIds->all());
        }

        $routeParams = ['user' => Auth::user()->id];
        $isWishPublish = isset($goods['type']) && $goods['type'] == Good::TYPE_BUY;
        if ($isWishPublish) {
            $routeParams['mode'] = 'wish';
        }

        if ($goods['goods_state'] == Good::goods_state_in_check) {
            $routeParams['state'] = Good::goods_state_in_check;
            return redirect()->route('sale_goods', $routeParams)->with('success', 'Submitted for review.');
        } elseif ($goods['goods_state'] == Good::goods_state_in_release) {
            return redirect()->route('sale_goods', $routeParams)->with('success', 'Saved successfully.');
        }
    }

    // Edit goods
    public function edit_goods($goods_id, GoodTag $good_tag)
    {
        $good_tag = GoodTag::get();
        $goods_info = Good::with('categories')->where('id', $goods_id)->first();
        $categories = Category::orderBy('id')->get();

        $this->authorize('eidt_goods', $goods_info);
        
        // 【新增：判断是否已指定地址，用于前端初始化】
        $isLocationSet = $goods_info->latitude !== null && $goods_info->latitude != 0.0;
        
        $booking = Booking::where('user_id', Auth::user()->id)
            ->where('user_state', Booking::seller_processing_booking)
            ->exists();

        $order = Order::where('user_id', Auth::user()->id)
            ->where('seller_state', Order::seller_pending_order)
            ->orWhere(function ($query) {
                $query->where('buyer_id', Auth::user()->id)
                    ->where('buyer_state', Order::buyer_pending_order)
                    ->where('seller_state', Order::seller_confirm_order);
            })->exists();

        if ($booking || $order) {
            session()->flash('danger', 'You still have pending bookings or orders. Please handle them before editing goods.');
            return redirect()->back();
        }

        $tags = [];
        for ($i = 0; $i < strlen($goods_info->tags); $i++) {
            if ($goods_info->tags[$i] != '-') {
                $tags[] = $goods_info->tags[$i];
            }
        }
        $tags_data = GoodTag::whereIn('id', $tags)->get();

        $goods_info->image = implode(',', $goods_info->image);
        $selectedCategories = $goods_info->categories->pluck('id')->toArray();
        if (empty($selectedCategories) && $goods_info->category_id) {
            $selectedCategories = [$goods_info->category_id];
        }

        return view('goods.create_edit_goods', compact('goods_info', 'tags_data', 'good_tag', 'categories', 'selectedCategories', 'isLocationSet'));
    }

    /* Goods search */
    // ... (其他方法保持不变) ...
    public function goods_search(Request $request, Good $good, Category $category)
    {
        // ... (方法体保持不变) ...
        $builder = Good::query();

        if ($category_id = $request->input('category_id', '')) {
            $categories = Category::where('id', $category_id)->first();
            if ($categories) {
                $builder->where(function ($query) use ($category_id) {
                    $query->where('category_id', $category_id)
                        ->orWhereHas('categories', function ($subQuery) use ($category_id) {
                            $subQuery->where('categories.id', $category_id);
                        });
                });
            }
        }

        $search = $request->search;
        if ($search != null) {
            $like = '%' . $search . '%';
            $builder->where(function ($query) use ($like, $search) {
                $query->where('id', $search)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('title', 'like', $like);
            });
        }

        if ($state = $request->input('state', Good::goods_state_in_selling)) {
            $builder->where('state', $state);
        }

        if ($key = $request->input('key', '')) {
            if ($key == 'new') {
                if ($time = $request->input('time', '3')) {
                    $builder->where('created_at', '>=', Carbon::now()->subDays($time));
                }
            }
        }

        if ($order = $request->input('order', '1')) {
            if ($order == '1') {
                $builder->orderBy('created_at', 'desc');
            } elseif ($order == '2') {
                $builder->orderBy('created_at', 'asc');
            } elseif ($order == '3') {
                $builder->orderBy('price', 'asc');
            } elseif ($order == '4') {
                $builder->orderBy('price', 'desc');
            }
        }

        $goods = $builder->paginate(18);

        if (isset($categories)) {
            return view('pages.root', compact('goods', 'categories', 'search', 'order', 'state'));
        }
        return view('pages.root', compact('goods', 'search', 'order', 'state'));
    }

    // Hot goods
    public function goods_hot(Good $goods, Request $request)
    {
        $categoryId = $request->input('category_id');
        $categories = null;
        if ($categoryId) {
            $categories = Category::find($categoryId);
        }

        $hot_goods = $goods->getHotGoods($categoryId);
        if ($categories) {
            return view('pages.root', compact('hot_goods', 'categories'));
        }
        return view('pages.root', compact('hot_goods'));
    }

    /* Goods detail */
    public function goods_detail($goods_id, Request $request)
    {
        $goods_info = Good::where('id', $goods_id)->with('bookings', 'orders', 'categories')->first();

        $this->authorize('seller_goods_detail', $goods_info);

        $booking_data = $goods_info->bookings->whereIn('user_state', [Booking::seller_agree_booking, Booking::seller_processing_booking])->first();
        $orders_data = $goods_info->orders->first();

        // view count
        $viewRecorded = false;
        try {
            $redis = app("redis.connection");
            $res = $redis->sadd('user_' . Auth::user()->id, 'goods_' . $goods_id);
            $view_count = $redis->scard('user_' . Auth::user()->id);
            if ($res) {
                if ($view_count == 1) {
                    $redis->expire('user_' . Auth::user()->id, Good::user_view_goods);
                }
                $viewRecorded = true;
            }
        } catch (\Throwable $e) {
            $cacheKey = 'user_' . $goods_id . '_viewed_' . Auth::user()->id;
            if (Cache::add($cacheKey, true, Good::user_view_goods)) {
                $viewRecorded = true;
            }
        }

        if ($viewRecorded) {
            $old_view_count = $goods_info->view_count + 1;
            $goods_info->update([
                'view_count'  => $old_view_count,
            ]);
        }

        $length = count($goods_info->image);
        $images = $goods_info->image;

        $user = User::where('id', $goods_info->user_id)->first();

        $tags = [];
        for ($i = 0; $i < strlen($goods_info->tags); $i++) {
            if ($goods_info->tags[$i] != '-') {
                $tags[] = $goods_info->tags[$i];
            }
        }
        $tags_data = GoodTag::whereIn('id', $tags)->get();

        $comments = Comment::where('goods_id', $goods_id)->orderBy('created_at', 'desc')->paginate(5);

        return view('goods.detail', compact('comments', 'tags_data', 'booking_data', 'orders_data'), compact('images', 'length', 'goods_info', 'user'));
    }
}
