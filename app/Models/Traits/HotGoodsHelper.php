<?php

namespace App\Models\Traits;

use App\Models\Good;
use Carbon\Carbon;
use Arr;
use Cache;

trait HotGoodsHelper
{

  // 基本信息
  protected $view_weight = 40; // 浏览量权重
  protected $comment_weight = 60; // 评论权重
  protected $capita_comment_max_number = 3;     // 人均评论数最大值
  protected $at_least_from_today = 1;    // 1天。发布时间需要大于等于 1 天
  protected $hot_goods_top = 30; // 需要热度Top几的商品
  protected $hot_goods_nearly_week = 2;    // 近几周的商品

  // 缓存配置
  protected $cache_key = 'onestore_hot_goods';
  protected $cache_expire_in_seconds = 60 * 60 ;   // 1 小时


  public function getHotGoods($categoryId = null)
  {
    $cacheKey = $this->cache_key . ($categoryId ? ('_'.$categoryId) : '');
    // 先取缓存，没有则计算后缓存
    return Cache::remember($cacheKey, $this->cache_expire_in_seconds, function () use ($categoryId, $cacheKey) {
      return $this->calculateAndCacheHotGoods($categoryId, $cacheKey);
    });
  }

  public function calculateAndCacheHotGoods($categoryId = null, $cacheKey = null)
  {
    // 取得 热度商品数据
    $hot_goods = $this->calculateScore($categoryId);

    // 并做缓存
    $this->cacheHotGoods($hot_goods, $cacheKey ?: $this->cache_key);
    return $hot_goods;
  }

  // 计算 热度分数
  private function calculateScore($categoryId = null)
  {
    $query = Good::where('created_at', '>=', Carbon::now()->subWeeks($this->hot_goods_nearly_week))
      ->whereNotIn('state',[Good::goods_state_in_release,Good::goods_state_in_check]);

    // 分区过滤：主分类或多对多分类均匹配
    if ($categoryId) {
      $query->where(function($q) use ($categoryId) {
        $q->where('category_id', $categoryId)
          ->orWhereHas('categories', function($sub) use ($categoryId) {
            $sub->where('categories.id', $categoryId);
          });
      });
    }

    $goods = $query->with('comments')->get();
    $score_sort = [];   // 关联数组，记录得分

    for ($i = 0; $i < count($goods); $i++) {
      // T- 发布天数
      $diff_in_days = Carbon::parse($goods[$i]->created_at)->diffInDays(Carbon::now(), false);

      // 获取 评论人数 - C
      $users_id=[];   // 记录评论的用户
      for($j=0;$j<count($goods[$i]->comments);$j++){
        array_push($users_id,$goods[$i]->comments[$j]->user_id);    // 存放 每个商品的评论用户id
      }
      $comment_users=count(array_unique($users_id));   // 通过去重，得到不重复的评论人数

      if ($diff_in_days >= $this->at_least_from_today) {
        $view_score = $this->view_weight * $goods[$i]->view_count / $diff_in_days;   // 浏览得分
        $comment_score = 0; // 默认评论得分为0，防止无评论时报未定义

        if ($comment_users > 0) {
          // 评论得分
          if ($goods[$i]->reply_count < $this->capita_comment_max_number * $comment_users) {
            $comment_score = $this->comment_weight * $comment_users + ($this->comment_weight / ($this->capita_comment_max_number * $comment_users) * $goods[$i]->reply_count);
          } else {
            $comment_score = $this->comment_weight * $comment_users + $this->comment_weight;
          }
        }
        $score_sort[$i] = round($view_score + $comment_score);   // 热度总分
      }
    }

    // 创建集合 - 存放热门商品
    $hot_goods = collect();

    $score_sort = array_reverse(Arr::sort($score_sort), true);   // 排序
    $index = 0;
    foreach ($score_sort as $key => $value) {        // 按照排序 插入
      if ($index == $this->hot_goods_top) break;
      $hot_goods->push($goods[$key]);
      $index++;
    }

    return $hot_goods;    // 热度商品
  }

  private function cacheHotGoods($hot_goods, $cacheKey = null)
  {
    // 将数据放入缓存
    Cache::put($cacheKey ?: $this->cache_key, $hot_goods, $this->cache_expire_in_seconds);
    
  }
}
