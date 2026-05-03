
<!-- 购买订单  -->
@extends('users.show')
@section('user_info')

<div class="col-lg-6 col-md-7 col-sm-12 col-xs-12 " style="margin-left: 60px;margin-bottom:75px">
  <div class="card ">
    <div class="card-body">
      <div class="row ">
        <i class="far fa-envelope mr-2 ml-3 mt-2" style="font-size: 26px;color:#636b6f"></i>
        <h1 class="ml-2 mt-2" style="line-height: 24px;color:#636b6f; font-size:20px;font-weight:bold; ">
          {{ $user->name }}
          
          <span style="letter-spacing:2px"> 购买订单</span>
          （{{ count($user->buyerOrders) }}）
        </h1>
      </div>

      <form class="form-inline mt-2 ml-2" method="get" action="{{ route('search_buyer_order',$user->id)}}">
        <input class="form-control mr-sm-2" name="content" type="search" placeholder="输入订单号..." aria-label="Search" style="width: 300px;">
        <input type="text"  name="type" value="{{$type}}" hidden>
        <button class="btn my-2 my-sm-0" style="border:1.5px solid #E23729;color:#E23729;" type="submit">搜索</button>
      </form>
    </div>

    <hr style="width: 650px;margin:0 auto;">

    <div class="card-body ">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item pending" role="presentation">
          <a class="nav-link  @if(seller_orders_active('pending')) active @endif" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="@if(seller_orders_active('pending')) true @else false @endif">
            待处理
            @if(seller_orders_active('pending'))
              @if(!Auth::user()->can('update_user_info', $user))
              【*】
              @else
              【{{ $pending_orders_count }}】
              @endif
            @endif
          </a>
        </li>
        <li class="nav-item processed" role="presentation">
          <a class="nav-link @if(seller_orders_active('processed')) active @endif" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="@if(seller_orders_active('processed')) true @else false @endif">
            已处理
            @if(seller_orders_active('processed'))
              @if(!Auth::user()->can('update_user_info', $user))
                【*】
              @else
                【{{ $processed_orders_count }}】
              @endif
            @endif
          </a>
        </li>
      </ul>

      <input type="text" value="{{ $pending_orders_count/5 }}" name="" id="pending_orders_count" hidden>   <!-- 待处理页数 -->
      <input type="text" value="{{ $processed_orders_count/5 }}" name="" id="processed_orders_count" hidden>   <!-- 已处理页数-->

      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade @if(seller_orders_active('pending')) show active @endif" id="home" role="tabpanel" aria-labelledby="home-tab">
          <ul class="list-group list-group-flush">
            @if(!Auth::user()->can('update_user_info', $user))
              <div class="card-body">
                <div class="" style="color:#ccc; text-align: center;line-height: 60px; margin: 10px;">
                  限制访问 ~_~
                </div>
              </div>
            @else
              @if( count($pending_orders) > 0 )
              @foreach ($pending_orders as $orders => $value)
              <div class="accordion" id="accordionExample">

                <div class="card">
                  <div class="card-header" id="heading{{ $value->id }}">
                    <h2 class="mb-0">
                      <button class="btn  btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{ $value->id }}" aria-expanded="false" aria-controls="collapse{{ $value->id }}">
                        <span class="" style="float:left;">订单号：{{ $value->no }} </span> 
                        <span class="" style="float:right">{{ $value->created_at }}</span>
                      </button>
                    </h2>
                  </div>

                  <div id="collapse{{ $value->id }}" class="collapse " aria-labelledby="heading{{ $value->id }}" data-parent="#accordionExample" >
                    
                    <div class="card-body row" style="display: flex;align-items:center;padding:9px">
                      <div class="goods_title ml-3" style="">
                        <span style="margin:0 auto">商品信息</span> 
                      </div>
                      <div class="ml-2 mr-2" style="width: 2px;height:100px;background-color:#eee"></div>
                      <div class="ml-1">  
                        <a href="{{ route('goods_detail',$value->goods->id ) }}" target="_blank">
                          <img src="{{ $value->goods->image[0] }}" style="width: 100px; height:100px;" alt="...">
                        </a>
                      </div>
                      <div class="ml-4">
                        <h5 class="card-title">
                          <a href="{{ route('goods_detail', $value->goods->id) }}" style="" target="_blank">{{ Str::limit($value->goods->title,30,'...')}}</a>
                        </h5>
                        <div class="mt-3">
                          <span class="" style="color: #E23729;height:25px;margin-bottom:0" title="售价">
                            ￥{{ $value->goods->price }}元
                          </span>
                          <span class="ml-4">
                            <small title="{{ $value->goods->created_at }}" class="text-muted">发布于
                              {{$value->goods->created_at->diffForHumans() }}</small>
                          </span>
                        </div>

                        <div class="mt-1">
                          <!-- 浏览量 + 评论量 -->
                          <span class="card-text  mt-1 mr-1 eye" style="position:relative; font-size:12px; left:0px" title="浏览量">
                            <i class="far fa-eye"></i> <span class="ml-1">{{$value->goods->view_count}}</span>
                          </span>
                          <span class="card-text ml-2 mt-1 mr-1 reply" style="position:relative; font-size:12px; left:0px;" title="评论量">
                            <i class="far fa-comment-dots"></i> <span class="ml-1">{{$value->goods->reply_count}}</span>
                          </span>
                        </div>
                      </div>
                    </div>

                    <hr class="" style="width: 530px;margin:0 auto;">

                    <div class="card-body row" style="display: flex;align-items:center;padding:9px">
                      <div class="buyer_title ml-3" style="width:56px">
                        <span style="margin:0 auto">用户信息</span> 
                      </div>
                      <div class="ml-2 mr-2" style="width: 2px;height:100px;background-color:#eee"></div>

                      <div class="ml-1" >
                        <div class="seller " >
                          <span class="mr-2" style="color: #636b6f;">卖家</span>
                          <a href="{{ route('user_show' , $value->user->id) }}" target="_blank">
                            <img src="{{ $value->user->avatar }}" alt="" class="img-thumbnail img-responsive img-circle" width="45px" height="45px" style="border-radius: 50%;">
                          </a>
                          <a href="{{ route('user_show' , $value->user->id) }}" target="_blank">{{ $value->user->name }}</a>
                          @if($value->user->sex == '男')
                            <img src="/images/iconfont/boy.png" title="男" class="mr-2" alt="" style="width: 23px;height:23px;margin-left:3px">
                          @else
                            <img src="/images/iconfont/girl.png" title="女" class="mr-2" alt="" style="width: 23px;height:23px;margin-left:3px">
                          @endif
                        </div>
                        <div class="buyer">
                          <span class="mr-2" style="color: #636b6f;">买家</span> 
                          <a href="{{ route('user_show' , $value->buyer->id) }}" target="_blank">
                            <img src="{{ $value->buyer->avatar }}" alt="" class="img-thumbnail img-responsive img-circle" width="45px" height="45px" style="border-radius: 50%;">
                          </a>
                          <a href="{{ route('user_show' , $value->buyer->id) }}" target="_blank">{{ $value->buyer->name }}</a> 
                          @if($value->buyer->sex == '男')
                            <img src="/images/iconfont/boy.png" title="男" class="mr-2" alt="" style="width: 23px;height:23px;margin-left:3px">
                          @else
                            <img src="/images/iconfont/girl.png" title="女" class="mr-2" alt="" style="width: 23px;height:23px;margin-left:3px">
                          @endif
                        </div>
                      </div>
                    </div>
                    <hr class="" style="width: 530px;margin:0 auto;">

                    <div  class="card-body row" style="display: flex;align-items:center; padding:9px">
                      
                      <div class="buyer_title ml-3" style=" width:56px">
                        <span style="margin:0 auto">支付信息</span> 
                      </div>
                      <div class="ml-2 mr-2" style="width: 2px;height:100px;background-color:#eee"></div>
                      
                      <div class="ml-1">
                        <div class="mb-1" >
                          <label for="amount" style="color: #636b6f;">金额</label>

                          {{-- 卖家未发送则显示默认价格；否则显示订单的价格 --}}
                          @if($value->seller_state == 2)
                            <input type="text" autocomplete="off" value="{{ $value->goods->price }}" class="form-control ml-2" id="amount" style="width:171px;display:inline" disabled>
                          @elseif($value->seller_state == 1)
                            <input type="text" autocomplete="off" value="{{ $value->payment_amount }}" class="form-control ml-2" id="amount" style="width:171px;display:inline" disabled>
                          @endif

                          <!-- <span class="ml-2" style="color:#636b6f;font-size:12px">填写范围为 0.1 ~ 9999.9，最多保留一位小数</span> -->
                          <!-- <div class="wrong_tip_price ml-5" style="font-size: 11px;"></div> -->
                        </div>
                        <div class="mt-2">
                          <label for="method" style="color: #636b6f;">方式</label>
                          <select class="form-control ml-2" id="method" style="width:171px;display:inline " autocomplete="off" disabled>
                            <option value="0" @if($value->payment_method == 0) selected @endif>微信</option>
                            <option value="1" @if($value->payment_method == 1) selected @endif>支付宝</option>
                            <option value="2" @if($value->payment_method == 2) selected @endif>现金</option>
                            <option value="3" @if($value->payment_method == 3) selected @endif>其他</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <hr class="" style="width: 530px;margin:0 auto;">

                    <div  class="card-body row" style="display: flex;align-items:center; padding:7px">
                      <div class="buyer_title ml-3" style=" width:56px">
                        <span style="margin:0 auto">订单状态</span>
                      </div>

                      <div class="ml-2 mr-2" style="width: 2px;height:100px;background-color:#eee"></div>
                      <div class="ml-2" style="width: 480px;">
                          @php
                            $isEffective = ($value->seller_state == 1 && ($value->buyer_state == 2 || $value->buyer_state == 4));
                            $rating = \App\Models\UserRating::where('order_id', $value->id)
                              ->where('rater_id', Auth::id())
                              ->first();
                            $hasRated = $rating !== null;
                            $ratedScore = $hasRated ? $rating->score : null;
                          @endphp
                          @if($isEffective)
                            <div>
                              <span style="color: #38c172;font-size:16px;letter-spacing:1px">
                                订单已生效
                              </span>
                            </div>
                            <div class="mt-2">
                              <span style="color:#636b6f;font-weight:600;font-size:14px;letter-spacing:0.7px">
                                【注】{{ buyer_order_processed($value->seller_state,$value->buyer_state)[2] ?? '双方已确认订单信息！' }}
                              </span>
                            </div>
                            <div class="mt-3" style="display: flex; align-items: center; gap: 10px;">
                              @if(!$hasRated)
                              <button type="button" class="btn btn-sm btn_rating" style="color:#E23729;border:1px solid #E23729;background:transparent;" 
                                data-order-id="{{ $value->id }}" 
                                data-rated-id="{{ $value->user_id }}"
                                style="width: 120px; height:35px;">
                                <i class="fas fa-star"></i> 评价卖家
                              </button>
                              @else
                              <span style="color: #6c757d; font-size:14px;">
                                <i class="fas fa-check-circle" style="color: #38c172;"></i> 已评价
                              </span>
                              <span style="color: #2fba9e; font-weight: 600; font-size: 16px;">
                                <i class="fas fa-star" style="color: #ffc107;"></i> {{ number_format($ratedScore, 1) }}分
                              </span>
                              @endif
                            </div>
                          @else
                            <div>
                              <span style="color: #E23729;font-size:16px;letter-spacing:1px">
                                订单未生效
                              </span>
                              <span style="color: #636b6f; font-size:13px;letter-spacing:0.7px">
                              @if($value->seller_state == 1 && $value->buyer_state == 3)
                                (&lowast;请在
                                  <span title="超时日期：{{ $value->updated_at->addDays(3) }}">
                                    {{ $value->updated_at->addDays(3)->diffForHumans(null,true) }}内
                                  </span>      
                                核对订单，超时系统将默认同意订单！)
                               @else  
                                (&lowast;{{ buyer_order_pending($value->seller_state,$value->buyer_state)[1] }}) 
                              @endif
                                
                              </span>
                            </div>
                            <div class="mt-2">
                              <span style="color:#636b6f;font-weight:600;font-size:14px;letter-spacing:0.7px">
                                【注】{{ buyer_order_pending($value->seller_state,$value->buyer_state)[2] }}
                              </span>
                            </div>
                          @endif

                      </div>
                    </div>
                    <hr class="" style="width: 530px;margin:0 auto;">

                    <div  class="card-body row" style="display: flex;align-items:center; padding:7px">
                      <div class="buyer_title ml-3" style=" width:56px">
                        <span style="margin:0 auto">订单备注</span>
                      </div>

                      <div class="ml-2 mr-2" style="width: 2px;height:100px;background-color:#eee"></div>
                      <div class="mb-1">
                        <textarea autocomplete="off" class="form-control" id="remark" rows="2" placeholder="备注..." name="remark" style="width:400px;height:48px;max-height: 95px;min-height: 48px;" disabled>{{$value->remark}}</textarea>
                      
                      </div>
                    </div>
                    <hr class="" style="width: 530px;margin:0 auto;">
                    @if(buyer_order_pending($value->seller_state,$value->buyer_state)[3])

                      @if($value->seller_state==2 && $value->buyer_state==3 )
                      <div  class="card-body row" style="display: flex;align-items:center; padding:5px">
                        <div class="card-body mt-2">
                          <button type="button" data-id="{{ $value->id }}" class="btn btn-sm btn-outline-danger ml-2 btn_cancel" style="width: 95px; height:33px;">取消订单</button>
                        </div>
                      </div>
                      @else
                      <div  class="card-body row" style="display: flex;align-items:center; padding:5px">
                        <div class="card-body mt-2">
                          <button type="button" data-id="{{ $value->id }}" class="btn btn-sm btn-outline-danger ml-2 btn_cancel" style="width: 95px; height:33px;">取消订单</button>
                          <button type="button" data-id="{{ $value->id }}" class="btn btn-sm btn-outline-success ml-5 btn_send" style="width: 95px; height:33px;">核对同意</button>
                        </div>
                        
                      </div>
                      <div  class="ml-3 row mb-2" style="">
                        <span style="color: #636b6f;">&lowast;若订单信息有误，请选择</span>
                        <button type="button" title="订单信息有误" data-id="{{ $value->id }}" class="btn btn-sm btn-outline-secondary ml-1 btn_refuse" style="width: 80px; height:33px;top:-5px;position:relative">
                          核对拒绝
                        </button>
                      </div>
                      @endif
                    @endif


                  </div>
                </div>
              </div>

              @endforeach
              <div class="card-body">
                {!! $pending_orders->appends(Request::except('page'))->render() !!}
              </div>

              @else
              <div class="card-body">
                <div class="" style="color:#ccc; text-align: center;line-height: 60px; margin: 10px;">
                  暂无数据 ~_~
                </div>
              </div>
              @endif
            @endif
          </ul>  
        </div>

        <!-- 已处理 -->
        <div class="tab-pane fade @if(seller_orders_active('processed')) show active @endif" id="profile" role="tabpanel" aria-labelledby="profile-tab">
          <ul class="list-group list-group-flush">
            @if(!Auth::user()->can('update_user_info', $user))
              <div class="card-body">
                <div class="" style="color:#ccc; text-align: center;line-height: 60px; margin: 10px;">
                  限制访问 ~_~
                </div>
              </div>
            @else
              @if( count($processed_orders) > 0 )
              
              @foreach ($processed_orders as $orders => $value)
              <div class="accordion" id="accordionExample">

                <div class="card">
                  <div class="card-header" id="heading{{ $value->id }}">
                    <h2 class="mb-0">
                      <button class="btn  btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse{{ $value->id }}" aria-expanded="false" aria-controls="collapse{{ $value->id }}">
                        <span class="" style="float:left;">订单号：{{ $value->no }} </span> 
                        <span class="" style="float:right">{{ $value->created_at }}</span>
                      </button>
                    </h2>
                  </div>

                  <div id="collapse{{ $value->id }}" class="collapse " aria-labelledby="heading{{ $value->id }}" data-parent="#accordionExample" >
                    
                    <div class="card-body row" style="display: flex;align-items:center;padding:9px">
                      <div class="goods_title ml-3" style="">
                        <span style="margin:0 auto">商品信息</span> 
                      </div>
                      <div class="ml-2 mr-2" style="width: 2px;height:100px;background-color:#eee"></div>
                      <div class="ml-1">  
                        <a href="{{ route('goods_detail',$value->goods->id ) }}" target="_blank">
                          <img src="{{ $value->goods->image[0] }}" style="width: 100px; height:100px;" alt="...">
                        </a>
                      </div>
                      <div class="ml-4">
                        <h5 class="card-title">
                          <a href="{{ route('goods_detail', $value->goods->id) }}" style="" target="_blank">{{ Str::limit($value->goods->title,30,'...')}}</a>
                        </h5>
                        <div class="mt-3">
                          <span class="" style="color: #E23729;height:25px;margin-bottom:0" title="售价">
                            ￥{{ $value->goods->price }}元
                          </span>
                          <span class="ml-4">
                            <small title="{{ $value->goods->created_at }}" class="text-muted">发布于
                              {{$value->goods->created_at->diffForHumans() }}</small>
                          </span>
                        </div>

                        <div class="mt-1">
                          <!-- 浏览量 + 评论量 -->
                          <span class="card-text  mt-1 mr-1 eye" style="position:relative; font-size:12px; left:0px" title="浏览量">
                            <i class="far fa-eye"></i> <span class="ml-1">{{$value->goods->view_count}}</span>
                          </span>
                          <span class="card-text ml-2 mt-1 mr-1 reply" style="position:relative; font-size:12px; left:0px;" title="评论量">
                            <i class="far fa-comment-dots"></i> <span class="ml-1">{{$value->goods->reply_count}}</span>
                          </span>
                        </div>
                      </div>
                    </div>

                    <hr class="" style="width: 530px;margin:0 auto;">

                    <div class="card-body row" style="display: flex;align-items:center;padding:9px">
                      <div class="buyer_title ml-3" style="width:56px">
                        <span style="margin:0 auto">用户信息</span> 
                      </div>
                      <div class="ml-2 mr-2" style="width: 2px;height:100px;background-color:#eee"></div>

                      <div class="ml-1" >
                        <div class="seller " >
                          <span class="mr-2" style="color: #636b6f;">卖家</span>
                          <a href="{{ route('user_show' , $value->user->id) }}" target="_blank">
                            <img src="{{ $value->user->avatar }}" alt="" class="img-thumbnail img-responsive img-circle" width="45px" height="45px" style="border-radius: 50%;">
                          </a>
                          <a href="{{ route('user_show' , $value->user->id) }}" target="_blank">{{ $value->user->name }}</a>
                          @if($value->user->sex == '男')
                            <img src="/images/iconfont/boy.png" title="男" class="mr-2" alt="" style="width: 23px;height:23px;margin-left:3px">
                          @else
                            <img src="/images/iconfont/girl.png" title="女" class="mr-2" alt="" style="width: 23px;height:23px;margin-left:3px">
                          @endif
                        </div>
                        <div class="buyer">
                          <span class="mr-2" style="color: #636b6f;">买家</span> 
                          <a href="{{ route('user_show' , $value->buyer->id) }}">
                            <img src="{{ $value->buyer->avatar }}" alt="" class="img-thumbnail img-responsive img-circle" width="45px" height="45px" style="border-radius: 50%;">
                          </a>
                          <a href="{{ route('user_show' , $value->buyer->id) }}">{{ $value->buyer->name }}</a> 
                          @if($value->buyer->sex == '男')
                            <img src="/images/iconfont/boy.png" title="男" class="mr-2" alt="" style="width: 23px;height:23px;margin-left:3px">
                          @else
                            <img src="/images/iconfont/girl.png" title="女" class="mr-2" alt="" style="width: 23px;height:23px;margin-left:3px">
                          @endif
                        </div>
                      </div>
                    </div>
                    <hr class="" style="width: 530px;margin:0 auto;">

                    <div  class="card-body row" style="display: flex;align-items:center; padding:9px">
                      
                      <div class="buyer_title ml-3" style=" width:56px">
                        <span style="margin:0 auto">支付信息</span> 
                      </div>
                      <div class="ml-2 mr-2" style="width: 2px;height:100px;background-color:#eee"></div>
                      
                      <div class="ml-1">
                        <div class="mb-1" >
                          <label for="amount" style="color: #636b6f;">金额</label>

                          {{-- 卖家未发送则显示默认价格；否则显示订单的价格 --}}
                          @if($value->seller_state == 2)
                            <input type="text" autocomplete="off" value="{{ $value->goods->price }}" class="form-control ml-2" id="amount" style="width:171px;display:inline" disabled>
                          @elseif($value->seller_state == 1)
                            <input type="text" autocomplete="off" value="{{ $value->payment_amount }}" class="form-control ml-2" id="amount" style="width:171px;display:inline" disabled>
                          @endif

                          <!-- <span class="ml-2" style="color:#636b6f;font-size:12px">填写范围为 0.1 ~ 9999.9，最多保留一位小数</span> -->
                          <!-- <div class="wrong_tip_price ml-5" style="font-size: 11px;"></div> -->
                        </div>
                        <div class="mt-2">
                          <label for="method" style="color: #636b6f;">方式</label>
                          <select class="form-control ml-2" id="method" style="width:171px;display:inline " autocomplete="off" disabled>
                            <option value="0" @if($value->payment_method == 0) selected @endif>微信</option>
                            <option value="1" @if($value->payment_method == 1) selected @endif>支付宝</option>
                            <option value="2" @if($value->payment_method == 2) selected @endif>现金</option>
                            <option value="3" @if($value->payment_method == 3) selected @endif>其他</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <hr class="" style="width: 530px;margin:0 auto;">

                    <div  class="card-body row" style="display: flex;align-items:center; padding:7px">
                      <div class="buyer_title ml-3" style=" width:56px">
                        <span style="margin:0 auto">订单状态</span>
                      </div>

                      <div class="ml-2 mr-2" style="width: 2px;height:100px;background-color:#eee"></div>
                      <div class="ml-2" style="width: 480px;">
                          @php
                            $isEffective = ($value->seller_state == 1 && ($value->buyer_state == 2 || $value->buyer_state == 4));
                            $rating = \App\Models\UserRating::where('order_id', $value->id)
                              ->where('rater_id', Auth::id())
                              ->first();
                            $hasRated = $rating !== null;
                            $ratedScore = $hasRated ? $rating->score : null;
                          @endphp
                          @if($isEffective)
                            <div>
                              <span style="color: #38c172;font-size:16px;letter-spacing:1px">
                                订单已生效
                              </span>
                            </div>
                            <div class="mt-2">
                              <span style="color:#636b6f;font-weight:600;font-size:14px;letter-spacing:0.7px">
                                【注】{{ buyer_order_processed($value->seller_state,$value->buyer_state)[2] ?? '双方已确认订单信息！' }}
                              </span>
                            </div>
                            <div class="mt-3" style="display: flex; align-items: center; gap: 10px;">
                              @if(!$hasRated)
                              <button type="button" class="btn btn-sm btn_rating" style="color:#E23729;border:1px solid #E23729;background:transparent;" 
                                data-order-id="{{ $value->id }}" 
                                data-rated-id="{{ $value->user_id }}"
                                style="width: 120px; height:35px;">
                                <i class="fas fa-star"></i> 评价卖家
                              </button>
                              @else
                              <span style="color: #6c757d; font-size:14px;">
                                <i class="fas fa-check-circle" style="color: #38c172;"></i> 已评价
                              </span>
                              <span style="color: #2fba9e; font-weight: 600; font-size: 16px;">
                                <i class="fas fa-star" style="color: #ffc107;"></i> {{ number_format($ratedScore, 1) }}分
                              </span>
                              @endif
                            </div>
                          @elseif(seller_order_processed($value->seller_state,$value->buyer_state)[0])
                            <div>
                              <span style="color: #38c172;font-size:16px;letter-spacing:1px">
                                订单已生效
                              </span>
                            </div>
                            <div class="mt-2">
                              <span style="color:#636b6f;font-weight:600;font-size:14px;letter-spacing:0.7px">
                                【注】{{ buyer_order_processed($value->seller_state,$value->buyer_state)[2] }}
                              </span>
                            </div>
                          @else
                            <div>
                              <span style="color: #E23729;font-size:16px;letter-spacing:1px">
                                订单未生效
                              </span>
                              <span style="color: #636b6f; font-size:13px;letter-spacing:0.7px">
                                (&lowast;{{ buyer_order_processed($value->seller_state,$value->buyer_state)[1] ?? '' }})
                              </span>
                            </div>
                            <div class="mt-2">
                              <span style="color:#636b6f;font-weight:600;font-size:14px;letter-spacing:0.7px">
                                【注】{{ buyer_order_processed($value->seller_state,$value->buyer_state)[2] ?? '' }}
                              </span>
                            </div>
                          @endif

                      </div>
                    </div>
                    <hr class="" style="width: 530px;margin:0 auto;">

                    <div  class="card-body row" style="display: flex;align-items:center; padding:7px">
                      <div class="buyer_title ml-3" style=" width:56px">
                        <span style="margin:0 auto">订单备注</span>
                      </div>

                      <div class="ml-2 mr-2" style="width: 2px;height:100px;background-color:#eee"></div>
                      <div class="mb-1">
                        <textarea autocomplete="off" class="form-control" id="remark" rows="2" placeholder="备注..." name="remark" style="width:400px;height:48px;max-height: 95px;min-height: 48px;" disabled>{{$value->remark}}</textarea>
                      
                      </div>
                    </div>
                    <hr class="" style="width: 530px;margin:0 auto;">
                    @if(buyer_order_processed($value->seller_state,$value->buyer_state)[3]) 
                      <div  class="card-body row" style="display: flex;align-items:center; padding:5px">
                        <div class="card-body mt-2">
                          <button type="button" data-id="{{ $value->id }}" class="btn btn-sm btn-outline-danger ml-2 btn_cancel" style="width: 95px; height:33px;">取消订单</button>
                          <button type="button" data-id="{{ $value->id }}" class="btn btn-sm btn-outline-success ml-5 btn_send" style="width: 95px; height:33px;">核对同意</button>
                        </div>
                        
                      </div>
                      <div  class="ml-3 row mb-2" style="">
                        <span style="color: #636b6f;">&lowast;若订单信息有误，请选择</span>
                        <button type="button" title="订单信息有误" data-id="{{ $value->id }}" class="btn btn-sm btn-outline-secondary ml-1 btn_refuse" style="width: 80px; height:33px;top:-5px;position:relative">
                          核对拒绝
                        </button>
                      </div>
                    @endif
                  </div>
                </div>
              </div>

              @endforeach
              <div class="card-body">
                {!! $processed_orders->appends(Request::except('page'))->render() !!}
              </div>

              @else
              <div class="card-body">
                <div class="" style="color:#ccc; text-align: center;line-height: 60px; margin: 10px;">
                  暂无数据 ~_~
                </div>
              </div>
              @endif
            @endif
          </ul> 
        
        </div>
        
      </div>

    </div>


  </div>
</div>

@stop

<!-- 评分弹窗 -->
<div class="modal fade" id="ratingModal" tabindex="-1" role="dialog" aria-labelledby="ratingModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ratingModalLabel">评价交易方</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="ratingForm">
          <input type="hidden" name="order_id" id="order_id" value="">
          <input type="hidden" name="rated_id" id="rated_id" value="">
          
          <div class="form-group">
            <label for="score">评分 <span class="text-danger">*</span></label>
            <div class="rating-container">
              <div class="rating-stars">
                <input type="radio" name="score" id="star5" value="5">
                <label for="star5" class="star-label">★</label>
                <input type="radio" name="score" id="star4" value="4">
                <label for="star4" class="star-label">★</label>
                <input type="radio" name="score" id="star3" value="3">
                <label for="star3" class="star-label">★</label>
                <input type="radio" name="score" id="star2" value="2">
                <label for="star2" class="star-label">★</label>
                <input type="radio" name="score" id="star1" value="1">
                <label for="star1" class="star-label">★</label>
              </div>
              <span id="scoreText" class="score-text">0分 - 未评分</span>
            </div>
            <small class="form-text text-muted">评分范围：0-5分（点击星星选择1-5分，点击空白处取消选择为0分）</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="submitRating" style="background:linear-gradient(120deg,#E23729,#EC5E29);border:none;">提交评价</button>
      </div>
    </div>
  </div>
</div>

<style>
.rating-container {
  display: flex;
  align-items: center;
  gap: 15px;
  position: relative;
}
.rating-stars {
  display: flex;
  flex-direction: row-reverse;
  justify-content: flex-end;
  gap: 5px;
  flex-shrink: 0;
  position: relative;
  padding-right: 10px;
}
.rating-stars input[type="radio"] {
  display: none;
}
.star-label {
  font-size: 30px;
  color: #ddd;
  cursor: pointer;
  transition: color 0.2s;
  user-select: none;
  position: relative;
  z-index: 2;
}
.rating-stars input[type="radio"]:checked ~ .star-label,
.rating-stars input[type="radio"]:checked ~ .star-label ~ .star-label,
.star-label:hover,
.star-label:hover ~ .star-label {
  color: #ffc107;
}
.score-text {
  color: #636b6f;
  font-size: 14px;
  min-width: 120px;
  flex-shrink: 0;
}
.rating-container::after {
  content: '';
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  cursor: pointer;
  z-index: 0;
  pointer-events: none;
}
</style>

@section('scriptsAfterJs')
<script src="https://cdn.staticfile.org/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

<link rel="stylesheet" href="/vendor/laravel-admin/sweetalert2/dist/sweetalert2.css">
<script src="/vendor/laravel-admin/sweetalert2/dist/sweetalert2.min.js" charset="UTF-8"></script>
<script>
  $(document).ready(function() {
   
    // 核对拒绝
    $('.btn_refuse').click(function(){
      Swal.fire({
        title: '你确认拒绝吗?',
        input: 'text',
      
        type:'warning',
        inputAttributes: {
          autocapitalize: 'off',
          maxlength: 32,
        },
        showCancelButton: true,
        cancelButtonText: '取消',
        confirmButtonText: '确认',
        showLoaderOnConfirm: true,
        allowEnterKey:false,
        allowOutsideClick:false
      }).then((res)=>{
       
        if(res.dismiss == 'cancel') return
        
        var order_id=$('.btn_refuse').data('id')
        //console.log(order_id)
        
        // 拒绝请求
        refuse_reason=$.trim($('.refuse_input').val())
        
        if(refuse_reason.length >32 || refuse_reason.length<3 ){     // 判断长度
          $(".refuse_input").focus();
        }else{
           //console.log(refuse_reason)
          axios.post('/buyer_refuse_order/'+ order_id,{refuse_reason}).then(function(res){
            console.log(res.data)
            swal({
              title: '拒绝成功！',
              text: "",
              type: 'success',
              //buttons: ,
            }).then((res)=>{
              location.reload()
            })
          })
        }

      })
      
      $('.swal2-input').addClass('refuse_input').addClass('form-control').removeClass('swal2-input')
      $('.refuse_input').focus();
      $('.refuse_input').attr('maxlength',32).attr('placeholder','填写订单有误的信息（3-32字）').css({
        'height':'50px',
      })

      // 验证 字段长度
      $('.refuse_input').after('<div class=""></div>')
      $('.refuse_input').blur(function(){      // 判断长度
        refuse_reason=$.trim($('.refuse_input').val())
        if(refuse_reason.length >32 || refuse_reason.length<3  ){
          if(!$('.refuse_input').hasClass('is-invalid')){
            $('.refuse_input').addClass('is-invalid').addClass('form-control')
            $('.refuse_input').next().addClass('invalid-feedback')
            $('.refuse_input').next().html('长度介于3-32个字符')
            $('.swal2-confirm').attr('disabled',true);
          }
        }else if(refuse_reason.length <=32 && refuse_reason.length>=3){
          // console.log('ys')
          $('.refuse_input').removeClass('is-invalid')
          $('.refuse_input').next().removeClass('invalid-feedback')
          $('.refuse_input').next().html('')
          $('.swal2-confirm').attr('disabled',false);
        }
      })

    })


    // 核对同意
    $('.btn_send').click(function(){

      //console.log(1)
      Swal.fire({
        title: '你确认同意吗?',
        text: "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '确认',
        cancelButtonText:'取消',
        // allowEnterKey:false,
        // allowOutsideClick:false,
        focusCancel:true
      }).then((result) => {
        if (!result.value) return
        
        var order_id=$('.btn_send').data('id')
        //console.log(order_id)
        axios.post('/buyer_confirm_order/'+order_id).then(function(res){
          //console.log(res.data)
          Swal.fire({
            title:'确认成功!',
            html:'完成交易，订单已生效！<br> 恭喜你又获得一件宝贝！',
            type:'success'
          }).then((res)=>{
            location.reload()
          })
          $('.swal2-content').addClass('info_text').css({
          'padding':'10px'
          })

        },function(error){
          if( error.response && error.response.status === 401 ){
            Swal.fire({
              title: '核对同意失败！',
              text: error.response.data.message,
              type: 'error',
            }).then((res)=>{
              window.location.reload()
            })
          }
        })

       
      })  
    })
    

    // 取消订单 
    $('.btn_cancel').click(function(){

      Swal.fire({
        title: '你确认取消吗?',
        text: "",
        html:'将会取消本次商品交易！',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '确认',
        cancelButtonText:'考虑一下',

        //allowEnterKey:false,
        //allowOutsideClick:false,
        focusCancel:true
      }).then((res)=>{
        if (!res.value) return

        order_id=$('.btn_cancel').data('id')
        axios.post('/buyer_cancel_order/'+order_id).then((res)=>{
          console.log(res.data)
          Swal.fire({
            title:'取消成功!',
            //html:'',
            type:'success'
          }).then((res)=>{
            location.reload()
          })
        },function(err){
          if( error.response && error.response.status === 401 ){
            Swal.fire({
              title: '取消订单失败！',
              text: error.response.data.message,
              type: 'error',
            }).then((res)=>{
              window.location.reload()
            })
          }
        })

      })
      $('.swal2-content').addClass('warning_text').css({
        'padding':'10px'
      })

    })


    // page缓存
    var old_tail_url=[]   // 记录之前状态 -页数 
    var lastPage=Math.ceil($('#pending_orders_count').val())  // 获取 待回复最后一页
    var lastPage_yesReply=Math.ceil($('#processed_orders_count').val())  // 获取 已回复最后一页
    
    if (lastPage==0) lastPage=1
    if (lastPage_yesReply==0) lastPage_yesReply=1

    var now_url_page=window.location.href.split('page=')[1]    // 获取当前页
    if(now_url_page == undefined) now_url_page=1

    //console.log('已回复页数：'+lastPage_yesReply)
    //console.log('当前页数：'+now_url_page)

    //console.log(document.referrer.length);
    if(document.referrer.indexOf('buyer_order') == -1 && document.referrer.length != 0){       // 当跳转不存在页数，referrer为空,此时也为刷新
      old_tail_url=['type=pending','type=processed']
      //console.log('跳转')
      $.removeCookie('buy_pending_old_url')
      $.removeCookie('buy_processed_old_url')

    }else if(document.referrer.indexOf('buyer_order') ){       
      //console.log('刷新')
      if(!$.cookie('buy_pending_old_url')){
        pending='type=pending'
       
      }else{
        if(window.location.href.indexOf('type=pending') != -1){   // 当前页面是待回复页面，则需要判断以下-page问题
          // 判断 待回复页数是否为0，且url为待回复
          if(now_url_page > lastPage){  //      最后一页问题-如果取消预定一个商品刚好没有下一页，而用户点了下一页，则改页数据为空，需返回第1页
            $.removeCookie('buy_pending_old_url')
            pending='type=pending'
           
          }else if(now_url_page <= lastPage){
            
            pending=$.cookie('buy_pending_old_url')
            //console.log(no_reply)
          }
        }else{        // 不是则不考虑
          pending=$.cookie('buy_pending_old_url')
        }
      }

      if(!$.cookie('buy_processed_old_url')){
        processed='type=processed'
      }else{
        if(window.location.href.indexOf('type=processed') != -1){   // 当前页面是已回复页面，则需要判断以下-page问题
          // 判断 已回复页数是否为0，且url为待回复
          if(now_url_page > lastPage_yesReply){  //      最后一页问题-如果取消预定一个商品刚好没有下一页，而用户点了下一页，则改页数据为空，需返回第1页
            $.removeCookie('buy_processed_old_url')
            processed='type=processed'
            
          }else if(now_url_page <= lastPage_yesReply){
            
            processed=$.cookie('buy_processed_old_url')
          }
        }else{        // 不是则不考虑
          processed=$.cookie('buy_processed_old_url')
        }
      }
      
      //console.log(no_reply)
      old_tail_url=[pending,processed]
      // console.log(old_tail_url)
    }

     //console.log(old_tail_url)

    // 待处理 -点击
    $('.pending').click(function(){
      now_url=window.location.href

      index=now_url.indexOf("type")
      head=now_url.substring(0,index)

      //console.log(head+old_tail_url[0])
      window.location.href=head+old_tail_url[0]
    })

    // 已处理
    $('.processed').click(function(){
      now_url=window.location.href

      index=now_url.indexOf("type")
      head=now_url.substring(0,index)
      //console.log(head+old_tail_url[1])
      window.location.href=head+old_tail_url[1]
     
    })

    // 页数跳转-点击
    $('a.page-link').click(function(){
      next_url=$(this).attr('href')
      index=next_url.indexOf("type")
      tail=next_url.substring(index)
      //var reply_expire= new Date();
      //reply_expire.setTime(expiresDate.getTime() + (60*1000));   // 2小时

      if(window.location.href.indexOf("type=pending") != -1){   // 当前为-待回复页
        $.cookie('buy_pending_old_url', tail);
      }else if((window.location.href.indexOf("type=processed") != -1)){
        $.cookie('buy_processed_old_url', tail)
      }
    })
    
    // 评分功能
    // 点击评价按钮 - 使用事件委托，因为按钮可能是动态加载的
    $(document).on('click', '.btn_rating', function() {
      var orderId = $(this).data('order-id');
      var ratedId = $(this).data('rated-id');
      
      $('#order_id').val(orderId);
      $('#rated_id').val(ratedId);
      $('#ratingForm')[0].reset();
      $('#scoreText').text('0分 - 未评分');
      $('input[name="score"]').prop('checked', false);
      $('#ratingModal').modal('show');
    });

    // 星级选择
    $(document).on('change', 'input[name="score"]', function() {
      var score = $(this).val();
      var scoreText = '';
      if (score == 5) scoreText = '5分 - 非常满意';
      else if (score == 4) scoreText = '4分 - 满意';
      else if (score == 3) scoreText = '3分 - 一般';
      else if (score == 2) scoreText = '2分 - 不满意';
      else if (score == 1) scoreText = '1分 - 很不满意';
      $('#scoreText').text(scoreText);
    });

    // 点击星星容器空白处取消选择，变为0分
    $(document).on('click', '.rating-container', function(e) {
      // 如果点击的是星星本身、radio按钮或评分文字，不处理
      if ($(e.target).is('.star-label') || $(e.target).is('input[type="radio"]') || 
          $(e.target).closest('.star-label').length || $(e.target).is('.score-text')) {
        return;
      }
      // 点击空白处，取消所有选择
      $('input[name="score"]').prop('checked', false);
      $('#scoreText').text('0分 - 未评分');
    });

    // 提交评分
    $('#submitRating').click(function() {
      var selectedScore = $('input[name="score"]:checked').val();
      // 如果没有选择星星，默认为0分
      var score = selectedScore ? parseFloat(selectedScore) : 0;
      
      var formData = {
        score: score,
      };

      if (score < 0 || score > 5) {
        swal({
          text: '评分范围是0-5分',
          icon: 'warning'
        });
        return;
      }

      var orderId = $('#order_id').val();
      if (!orderId) {
        swal({
          text: '订单ID缺失',
          icon: 'error'
        });
        return;
      }

      $(this).prop('disabled', true).text('提交中...');

      $.ajax({
        url: '/user-ratings/' + orderId,
        method: 'POST',
        data: formData,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          $('#ratingModal').modal('hide');
          swal({
            text: '评价成功！',
            icon: 'success',
            button: '确定'
          }).then(function() {
            location.reload();
          });
        },
        error: function(xhr) {
          var message = '评价失败，请重试';
          if (xhr.responseJSON && xhr.responseJSON.error) {
            message = xhr.responseJSON.error;
          } else if (xhr.responseText) {
            try {
              var errorData = JSON.parse(xhr.responseText);
              if (errorData.error) {
                message = errorData.error;
              }
            } catch(e) {
              // 忽略解析错误
            }
          }
          swal({
            text: message,
            icon: 'error'
          });
          $('#submitRating').prop('disabled', false).text('提交评价');
        }
      });
    });

  })
  

</script>

@stop