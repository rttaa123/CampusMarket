@extends('layouts.app')
@section('title', '消息通知')

@section('content')

<style>
:root{
  --tb-red:#E23729;
  --tb-red-2:#EB483E;
  --tb-red-soft:#FCF1F0;
  --tb-orange:#EC5E29;
  --tb-orange-soft:#FDF1EC;
  --tb-gradient:linear-gradient(90deg,var(--tb-red),var(--tb-orange));
  --tb-border:rgba(226,55,41,.16);
}

.notifications-taobao .card{
  border: none;
  border-radius: 16px;
  box-shadow: 0 14px 38px rgba(20,20,20,.10);
}
.notifications-taobao .card-body{
  padding: 22px 22px 10px;
}
.notifications-taobao h3{
  display:flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  margin-bottom: 10px;
  color: rgba(0,0,0,.78);
}
.notifications-taobao h3 i{
  color: var(--tb-red);
}
.notifications-taobao hr{
  margin-top: 12px;
  border-top-color: rgba(0,0,0,.06);
}

.notifications-taobao .list-group{
  padding: 4px 6px 10px;
}
.notifications-taobao .list-group-item{
  border: 1px solid rgba(0,0,0,.06);
  border-radius: 14px;
  margin-bottom: 12px;
  background: #fff;
}
.notifications-taobao .list-group-item:hover{
  border-color: var(--tb-border);
  background:
    linear-gradient(0deg, rgba(252,241,240,.55), rgba(252,241,240,.18)),
    #fff;
}
.notifications-taobao a{
  color: var(--tb-red);
}
.notifications-taobao a:hover{
  color: var(--tb-red-2);
}

.notifications-taobao .tb-state-pass{
  color: var(--tb-orange);
  font-weight: 600;
}
.notifications-taobao .tb-state-fail{
  color: var(--tb-red);
  font-weight: 600;
}
.notifications-taobao .tb-danger{
  color: var(--tb-red);
  font-weight: 600;
}
.notifications-taobao #time{
  opacity: .92;
}

.notifications-taobao .pagination{
  margin: 12px 0 0;
  gap: 6px;
  justify-content: center;
}
.notifications-taobao .pagination .page-link{
  color: #8a4b3d;
  border: 1px solid #ffd1c1;
  background: linear-gradient(180deg, #fffdfc 0%, #fff2ee 100%);
  border-radius: 12px;
  box-shadow: 0 6px 16px rgba(255, 138, 128, 0.10);
  margin: 0 2px;
}
.notifications-taobao .pagination .page-item.active .page-link{
  color: #5a2d2b;
  border-color: #d97706;
  background: linear-gradient(120deg, #ffb199 0%, #ff8a80 45%, #ffd1c1 100%);
}
.notifications-taobao .pagination .page-link:hover{
  color: #5a2d2b;
  border-color: #f59e0b;
  background: linear-gradient(120deg, #ffe7dd 0%, #ffd4c8 100%);
}
</style>

<div class="container notifications-taobao">
  <div class="col-md-10 offset-md-1">
    <div class="card ">

      <div class="card-body">

        <h3 class="text-xs-center">
          <i class="far fa-bell" aria-hidden="true"></i> 
           消息通知 （{{ $count }}）
        </h3>
        <hr>

        
        <ul class="list-group list-group-flush">

          @if(count($notifications) > 0)
          @foreach($notifications as $notification=>$value)

          
            <!-- 判断消息类型 -->
            @if($value->type == 'App\Notifications\BookingGoods')       <!-- 预定通知 -->
              <li class="list-group-item">
                <a href="{{ route('user_show' , $value->data['booker_id']) }}" target="_blank">
                  <img src="{{ $value->data['booker_avatar'] }}" alt="" class="img-thumbnail img-responsive img-circle" width="45px" height="45px" style="border-radius: 50%;">
                </a>
                <a href="{{ route('user_show' , $value->data['booker_id']) }}">{{ $value->data['booker_name'] }}</a> 预定了你的商品！
                <a href="{{ route('booking_notice', Auth::id()) }}?reply=no">点击查看</a>

                
                <div id="time" class="text-secondary mt-2 mr-1  row" style="float:right;display:flex;" >
                  <span class="ml-3" title="{{ $value->created_at }}">
                    <i class="far fa-clock" style="font-size: 15px;"></i>
                    <span class="ml-2" style="font-size: 12px;">预定于{{ $value->created_at->diffForHumans() }}</span>
                  </span>
                </div>
              </li>
            @elseif($value->type == 'App\Notifications\CheckGoods')       <!-- 审核通知 -->
              @php
                $goodsType = $value->data['goods_type'] ?? \App\Models\Good::TYPE_GOOD;
                $itemLabel = $goodsType == \App\Models\Good::TYPE_BUY ? '你的求购' : '你的商品';
              @endphp
              <li class="list-group-item">
                <span style="color: #6c757d; font-size:16px">{{ $itemLabel }}</span> 
                <a href="{{ route('goods_detail' ,$value->data['goods_id']) }}" target="_blank">
                  {{ $value->data['title'] }}
                </a>
                <span style="font-size:16px;color: #6c757d;">审核</span>

                
                @if($value->data['is_check'] == 'true')
                  <span class="tb-state-pass" style="font-size:16px">【通过】</span>
                @else
                  <span class="tb-state-fail" style="font-size:16px">【不通过】</span>
                  <span style="font-size:16px;color: #6c757d;">，原因：</span> 
                  <span class="tb-state-fail">{{ $value->data['reason'] }}</span>
                  
                  @if(!empty($value->data['other_reason'] ?? null))
                  <span style="font-size:16px;color: #6c757d;">，其他原因：</span> 
                  <span class="tb-state-fail">{{ $value->data['other_reason'] }}</span>
                  @endif

                @endif
                <div id="time" class="text-secondary  mr-1  row" style="float:right;display:flex;" >
                  <span class="ml-3" title="{{ $value->created_at }}">
                    <i class="far fa-clock" style="font-size: 15px;"></i>
                    <span class="ml-2" style="font-size: 12px;">审核于{{ $value->created_at->diffForHumans() }}</span>
                  </span>
                </div>
              </li>
            @elseif($value->type == 'App\Notifications\CancelOrders')
              <li class="list-group-item">
                @if( $value->data['seller_state'] == 0)      {{-- 卖家取消 --}}
                  
                  @if( $value->notifiable_id == $value->data['buyer_id'])
                    卖家
                    <a href="{{ route('user_show' , $value->data['seller_id']) }}" target="_blank">
                      <img src="{{ $value->data['seller_avatar'] }}" alt="" class="img-thumbnail img-responsive img-circle" width="45px" height="45px" style="border-radius: 50%;">
                    </a>
                    <a href="{{ route('user_show' , $value->data['seller_id']) }}">
                      {{ $value->data['seller_name'] }}
                    </a>
                    
                    <span class="tb-danger">取消</span> 订单。
                    订单号：{{ $value->data['no'] }}

                    订单商品：
                    <a href="{{ route('goods_detail' ,$value->data['order_goods_id']) }}" target="_blank">
                      {{ $value->data['order_goods_title'] }}
                    </a>
                  @elseif( $value->notifiable_id == $value->data['seller_id'])
                    你
                    <span class="tb-danger">取消</span>订单。
                    
                    订单号：{{ $value->data['no'] }}。
                    订单商品：
                    <a href="{{ route('goods_detail' ,$value->data['order_goods_id']) }}" target="_blank">
                      {{ $value->data['order_goods_title'] }}
                    </a>
                   
                  @endif

                @else   {{-- 买家取消 --}}
                  @if(Auth::user()->id == $value->data['buyer_id'])
                    你
                    <span class="tb-danger">取消</span> 订单。
                    订单号：{{ $value->data['no'] }}

                    订单商品：
                    <a href="{{ route('goods_detail' ,$value->data['order_goods_id']) }}" target="_blank">
                      {{ $value->data['order_goods_title'] }}
                    </a>
                  @elseif(Auth::user()->id == $value->data['seller_id'] )
                    买家
                    <a href="{{ route('user_show' , $value->data['buyer_id']) }}" target="_blank">
                      <img src="{{ $value->data['buyer_avatar'] }}" alt="" class="img-thumbnail img-responsive img-circle" width="45px" height="45px" style="border-radius: 50%;">
                    </a>
                    <a href="{{ route('user_show' , $value->data['seller_id']) }}">
                      {{ $value->data['buyer_name'] }}
                    </a>

                    <span class="tb-danger">取消</span>  订单。
                   
                    订单号：{{ $value->data['no'] }}
                    订单商品：
                    <a href="{{ route('goods_detail' ,$value->data['order_goods_id']) }}" target="_blank">
                      {{ $value->data['order_goods_title'] }}
                    </a>
                  @endif

                @endif
                <div id="time" class="text-secondary  mr-1  row" style="float:right;display:flex;" >
                  <span class="ml-3" title="{{ $value->created_at }}">
                    <i class="far fa-clock" style="font-size: 15px;"></i>
                    <span class="ml-2" style="font-size: 12px;">取消于{{ $value->created_at->diffForHumans() }}</span>
                  </span>
                </div>
              </li>

            @endif
          @endforeach
          <li class="list-group-item">
            <div class="mt-2">
              {!! $notifications->render() !!}
            </div>
          </li>

          @else
          <div class="empty-block">没有消息通知！</div>
          @endif

        </ul>
      </div>
    </div>
  </div>
</div>



@stop