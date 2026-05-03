<!--  -->
@extends('layouts.app')

@section('title', isset($categories) ? $categories->name : '首页')

@section('content')

<!-- 开屏动画 -->


<style>
  /* 动画样式 */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(40px) scale(0.95);
    }
    to {
      opacity: 1;
      transform: translateY(0) scale(1);
    }
  }
  
  @keyframes logoAppear {
    from {
      opacity: 0;
      transform: scale(0);
    }
    to {
      opacity: 1;
      transform: scale(1);
    }
  }
  
  @keyframes loadingPulse {
    0% {
      width: 0;
      transform: translateX(-100%);
    }
    50% {
      width: 100%;
      transform: translateX(0);
    }
    100% {
      width: 0;
      transform: translateX(100%);
    }
  }
  
  @keyframes float {
    0%, 100% {
      transform: translate(0, 0) rotate(0deg);
    }
    33% {
      transform: translate(30px, -50px) rotate(5deg);
    }
    66% {
      transform: translate(-20px, 20px) rotate(-5deg);
    }
  }

  .goods-wrapper {
    background: radial-gradient(circle at 18% 18%, #fff4ef, #ffe9e1 45%, #fff7f2 82%);
    padding: 18px 12px 32px;
    border-radius: 14px;
  }
  .goods-container {
    max-width: 1280px;
    margin: 0 auto;
  }
  .goods-shell {
    border: none;
    border-radius: 18px;
    box-shadow: 0 14px 38px rgba(255, 138, 128, 0.18);
    overflow: hidden;
    background: linear-gradient(180deg, #fffdfc 0%,rgb(255, 255, 255) 100%);
  }
  .goods-shell .list-group-item {
    border: none;
  }
  .new_hot a {
    border-radius: 12px !important;
    font-weight: 700;
    color: #8a4b3d;
    padding: 8px 12px;
  }
  .new_hot a.active,
  .new_hot a:hover {
    background: linear-gradient(120deg, #ffb199 0%, #ff8a80 45%, #ffd1c1 100%);
    color: #5a2d2b !important;
  }
  .card_div {
    padding: 0 20px;
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 15px;
  }
  .goods_list {
    border: none;
    border-radius: 18px;
    background: linear-gradient(180deg, #fffdfc 0%,rgb(255, 255, 255) 100%);
    box-shadow: 0 10px 24px rgba(144, 142, 142, 0.24);
    overflow: hidden;
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    height: 320px; /* 固定卡片高度 */
    position: relative;
    transition: transform .2s ease, box-shadow .2s ease;
  }
  .goods_list:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 30px rgba(255, 138, 128, 0.22);
  }
  .goods_img {
    border-radius: 12px;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .card_price {
    color: #e85f4d;
    font-weight: 800;
    font-size: 18px;
    line-height: 24px;
    white-space: nowrap;
    text-shadow: 0 1px 2px rgba(0,0,0,0.08);
  }
.btn_search {
    border-radius: 0;
    padding: 0 14px;
    font-weight: 600;
    line-height: calc(1.5em + .75rem + 2px);
    border: 1px solid #ced4da;
    border-left: none;
  }
  .input-group {
    position: relative;
  }
  
  /* 确保输入框左侧有圆角，右侧没有圆角，与按钮匹配 */
  .input-group .form-control {
    border-top-left-radius: 0.25rem;
    border-bottom-left-radius: 0.25rem;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
  }
  /* 分页控件样式 */
  .pagination {
    margin-top: 20px;
    gap: 6px;
  }
  .pagination .page-item .page-link {
    color: #8a4b3d;
    border-color: #ffd1c1;
    background: linear-gradient(180deg, #fffdfc 0%, #fff2ee 100%);
    border-radius: 12px;
    box-shadow: 0 6px 16px rgba(255, 138, 128, 0.10);
    margin: 0 2px;
  }
  .pagination .page-item.active .page-link {
    background: linear-gradient(120deg, #ffb199 0%, #ff8a80 45%, #ffd1c1 100%);
    border-color: #d97706;
    color: #5a2d2b;
  }
  .pagination .page-item .page-link:hover {
    color: #5a2d2b;
    background: linear-gradient(120deg, #ffe7dd 0%, #ffd4c8 100%);
    border-color: #f59e0b;
  }
.list-item-content {
    display: flex;
    flex-direction: column;
    height: 100%; /* 占满卡片高度 */
    padding: 0;
}
.list-item-image {
    width: 100%;
    height: 180px;
    overflow: hidden;
    margin-left: 0;
}
.list-item-details {
    padding: 12px;
    padding-bottom: 40px; /* 为价格区域留出固定空间 */
    display: flex;
    flex-direction: column;
    flex: 1;
}
.list-item-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 6px;
    color: #5a2d2b;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    line-clamp: 2;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}
.list-item-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: absolute;
    bottom: 12px; /* 固定距离下边框12px */
    left: 12px;
    right: 12px;
}
.list-item-info {
    font-size: 11px;
    color: #9a6a5f;
    margin-bottom: 8px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
  .category-intro {
    margin-bottom: 16px;
    border: none;
    border-radius: 16px;
    background: linear-gradient(120deg,rgb(255, 255, 255) 0%,rgb(255, 255, 255) 100%);
    color: #5a2d2b;
    box-shadow: 0 10px 24px rgba(203, 201, 201, 0.14);
    padding: 14px 18px;
  }
  .category-intro strong {
    color: #c85a47;
  }
  .custom-select {
  border-color: #f2a38d;
  }
  .custom-select:focus {
  border-color: #c85a47;
  }
</style>

@if (isset($categories))
<div class="category-intro" role="alert">
  <strong>{{ $categories->name }}</strong> ：{{ $categories->description }}
</div>
@endif
<div class="goods-wrapper">
<div class="goods-container">
<div class="card ml-4 goods-shell" style="width: 1230px;margin-bottom:120px; ">
  <ul class="list-group list-group-flush card_box">
    <!--这里设为动态高度-->

    <li class="list-group-item ">
      <div class="d-flex align-items-center justify-content-between new_hot" style="padding-left: 12px; padding-right: 12px;">
        <div class="d-flex align-items-center">
          <a id="new_goods" style="border-radius: 0.25rem;"  class="nav-link new {{ active_class( if_query('key','new')) }}" href="{{ route('goods_search') }}?key=new&time=3&category_id={{ isset($categories) ? $categories->id : '' }}">
            最新发布
          </a>

          <div class="dropdown-menu" id="new_goods_time" style="top:80%">
            <div class="form-check form-check-inline ">
              <input id="three_day" autocomplete="off" class="form-check-input" time="3" type="radio" name="inlineRadioOptions" value="{{ route('goods_search') }}?key=new&time=3&category_id={{ isset($categories) ? $categories->id : '' }}" {{ new_goods_times(3) }}>
              <label id="three_label" class="form-check-label" for="three_day">3天</label>
              
            </div>
            <div class="form-check form-check-inline">
              <input autocomplete="off" class="form-check-input" type="radio" time="7" name="inlineRadioOptions" id="seven_day" value="{{ route('goods_search') }}?key=new&time=7&category_id={{ isset($categories) ? $categories->id : '' }}" {{ new_goods_times(7) }}>
              <label class="form-check-label" for="seven_day">7天</label>
            </div>
            <div class="form-check form-check-inline">
              <input autocomplete="off" class="form-check-input" type="radio" time="15" name="inlineRadioOptions" id="fifteen_day" value="{{ route('goods_search') }}?key=new&time=15&category_id={{ isset($categories) ? $categories->id : '' }}" {{ new_goods_times(15) }}>
              <label class="form-check-label" for="fifteen_day">15天</label>
            </div>
          </div>
        
          

          <a style="border-radius: 0.25rem;" class="nav-link hot {{ active_class(if_route('goods_hot')) }}" href="{{ route('goods_hot', ['category_id' => isset($categories) ? $categories->id : null]) }}">
            热门发布 
          </a>
        </div>

        @if(!isset($hot_goods))
        <div class="d-flex align-items-center" style="margin-left: auto;">
          <select class="custom-select" id="order_select" style="width: 105px; height: calc(1.5em + .75rem + 2px); box-shadow: 0 8px 8px rgba(203, 201, 201, 0.86);">
            <option value="1" {{ !isset($order) || $order=='1' ? 'selected': '' }}>时间降序</option>
            <option value="2" {{ isset($order) && $order=='2' ? 'selected': '' }}>时间升序</option>
            <option value="3" {{ isset($order) && $order=='3' ? 'selected': '' }}>价格升序</option>
            <option value="4" {{ isset($order) && $order=='4' ? 'selected': '' }}>价格降序</option>
          </select>
          <select class="custom-select" id="state_select" style="width: 105px; height: calc(1.5em + .75rem + 2px); margin-left: 10px; box-shadow: 0 8px 8px rgba(192, 191, 191, 0.7);">
            <option value="2" {{ !isset($state) || $state=='2' ? 'selected': '' }}>出售中</option>
            <option value="3" {{ isset($state) && $state=='3' ? 'selected': '' }}>预定中</option>
            <option value="4" {{ isset($state) && $state=='4' ? 'selected': '' }}>已出售</option>
          </select>
        </div>
        @endif
      </div>
    </li>

    <div class="card_div" style="padding: 0 20px;">
      @if(isset($hot_goods))
        @if(count($hot_goods) > 0)
        <?php $index=1; ?>
        @foreach ($hot_goods as $value)
        <div class="card goods_list" id="goods_list" style="overflow: hidden;">
          <a href="{{ route('goods_detail',$value->id) }}?from={{ isset($categories) ? $categories->id : 'all' }} " style="text-decoration: none;">
            <div class="list-item-content">
              <div class="list-item-image">
                <img src="{{ $value->image[0] }}" class="goods_img" alt="...">
              </div>
              <div class="list-item-details">
                <div class="list-item-title">{{ $value->title }}</div>
                <div class="list-item-info">
                  <span><i class="far fa-clock"></i> 发布于 {{ $value->created_at->diffForHumans() }}</span>
                  <span class="ml-3"><i class="far fa-eye"></i> {{$value->view_count}} 浏览</span>
                  <span class="ml-3"><i class="far fa-comment-dots"></i> {{$value->reply_count}} 评论</span>
                </div>
                <div class="list-item-meta">
                  <div class="list-item-rank">
                    @if($index == 1)
                    <i class="fab fa-hotjar" style="color:#d73038;"> <span class="ml-1">【1】</span></i>
                    @elseif($index== 2)
                    <i class="fab fa-hotjar" style="color:#ff5c38;"> <span class="ml-1">【2】</span></i>
                    @elseif($index== 3)
                    <i class="fab fa-hotjar" style="color:#ffb821;"> <span class="ml-1">【3】</span></i>
                    @else
                    <i class="fab fa-hotjar" style="color:#7f7f8c;"> <span class="ml-1">【{{$index}}】</span></i>
                    @endif
                  </div>
                  <div class="card_price">￥ {{ $value->price }}元</div>
                </div>
              </div>
            </div>
          </a>
        </div>
        <?php $index++; ?>
        @endforeach
        @else
        <div class="card-body">
          <div class="" style="color:#ccc; text-align: center;line-height: 60px; margin: 10px;">
            暂无物品 ~_~
          </div>
        </div>
        @endif

      @else
      @if(count($goods) > 0)
      @foreach ($goods as $good => $value)

      <div class="card goods_list" id="goods_list" style="overflow: hidden;">
        <a href="{{ route('goods_detail',$value->id) }}?from={{ isset($categories) ? $categories->id : 'all' }} " style="text-decoration: none;">
          <div class="list-item-content">
            <div class="list-item-image">
              <img src="{{ $value->image[0] }}" class="goods_img" alt="...">
            </div>
            <div class="list-item-details">
              <div class="list-item-title">{{ $value->title }}</div>
              <div class="list-item-info">
                <span><i class="far fa-clock"></i> 发布于 {{ $value->created_at->diffForHumans() }}</span>
                <span class="ml-3"><i class="far fa-eye"></i> {{$value->view_count}} 浏览</span>
                <span class="ml-3"><i class="far fa-comment-dots"></i> {{$value->reply_count}} 评论</span>
              </div>
              <div class="list-item-meta">
                <div></div>
                <div class="card_price">￥ {{ $value->price }}元</div>
              </div>
            </div>
          </div>
        </a>
      </div>
      @endforeach
    </div>

    <li class="list-group-item " style="top:10px"></li>
    <div class="card-body" style="margin:0 auto;margin-bottom:50px; position:relative; top:25px">
      <!-- {!! $goods->render() !!} -->
      {!! $goods->appends(Request::except('page'))->render() !!}
    </div>
  </ul>

    @else
    <div class="card-body">
      <div class="" style="color:#ccc; text-align: center;line-height: 60px; margin: 10px;">
        暂无物品 ~_~
      </div>
    </div>
    @endif
    @endif
  </div>
</div>
</div>
@stop

@section('scriptsAfterJs')
<script>
  // 开屏动画控制
  window.addEventListener('load', function() {
    setTimeout(function() {
      const splashScreen = document.getElementById('splash-screen');
      splashScreen.style.opacity = '0';
      setTimeout(function() {
        splashScreen.style.display = 'none';
      }, 500); // 等待过渡动画完成
    }, 2000); // 开屏动画显示2秒
  });

  $(document).ready(function() {

    // 搜索
    $('.btn_search').click(function(){
      if($('.new').hasClass('active')){
        //console.log('最新')
        $('.btn_new_hot').val('new')
      }else if($('.hot').hasClass('active')){
        //console.log('最热')
        $('.btn_new_hot').val('hot')
      }
      $('#search_form').submit();
    })

    // 排序方式-自动提交表单
    $('#order_select').change(function() {
      if($('.new').hasClass('active')){
        $('.btn_new_hot').val('new')
      }else if($('.hot').hasClass('active')){
        $('.btn_new_hot').val('hot')
      }
      $('#search_form').submit();
    })

    // 商品状态
    $('#state_select').change(function() {
      if($('.new').hasClass('active')){
        $('.btn_new_hot').val('new')
      }else if($('.hot').hasClass('active')){
        $('.btn_new_hot').val('hot')
      }
      $('#search_form').submit();
    })




    // 最新发布 - 时间样式
    var this_btn
    $('#new_goods').click(function(e){
      if($(this).hasClass('active')){
        if($('#new_goods_time').is(':hidden')){
          $('#new_goods_time').show()
          this_btn = $(this)
        }else{
          $('#new_goods_time').hide()
          this_btn=null
        }
        return false;
      }
    })
    $('#new_goods_time').click(function(e){
      e.stopPropagation();
    })
    $(document).click(function() { //document-关闭时间菜单
      if (this_btn) {
        this_btn.trigger('click')
      }
      this_btn = null
    })
    // 选择时间
    $three=$('#three_day').val()
    $seven=$('#seven_day').val()
    $fifteen=$('#fifteen_day').val()
    
    //btn_new_time
    $('#new_goods_time input:radio:checked').each(function(){
      $('.btn_new_time').val($(this).attr('time'))
     
    })

    $('#three_day').click(function(){
      window.location.href= $three
    })
    $('#three_label').click(function(){
      window.location.href=$three
    })
    $('#seven_day').click(function(){
      window.location.href=$seven
    })
    $('#seven_label').click(function(){
      window.location.href=$seven
    })
    $('#fifteen_day').click(function(){
      window.location.href=$fifteen
    })
    $('#fifteen_label').click(function(){
      window.location.href=$fifteen
    })


  })
</script>
@stop
