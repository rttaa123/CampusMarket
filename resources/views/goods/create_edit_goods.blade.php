@extends('layouts.app')
@section('title', '首页')

@section('content')

<style>
  .goods-page {
    /* background: radial-gradient(circle at 20% 20%, #e7f7ff, #eff6ff 45%, #e5f5ef 80%); */
    background: linear-gradient(145deg, #fffaf9 0%, #fff5f2 100%);
    padding: 24px 12px 40px;
    border-radius: 14px;
    display: flex;
    justify-content: center;
  }

  .goods-card {
    width: 100%;
    max-width: 800px;
    border: none;
    border-radius: 18px;
    /* box-shadow: 0 14px 38px rgba(21, 45, 71, 0.12); */
    box-shadow: 0 20px 40px -12px rgba(226, 55, 41, 0.08), 0 4px 12px rgba(0, 0, 0, 0.02);
    overflow: hidden;
    background: #fff;
  }

  .btn-main {
    /* background: #2fba9e; */
    background: linear-gradient(105deg, #E23729 0%, #EC5E29 100%);
    color: #ffffff;
    /* border: 1px solid #2fba9e; */
    border: none;
    border-radius: 12px;
    font-weight: 700;
    /* box-shadow: none; */
    box-shadow: 0 4px 12px rgba(226, 55, 41, 0.2);
    transition: all 0.18s ease;
  }

  .btn-main:hover {
    /* background: #259b83;
    border-color: #259b83; */
    color: #ffffff;
    /* box-shadow: 0 10px 22px rgba(37, 155, 131, 0.28); */
    box-shadow: 0 8px 20px rgba(226, 55, 41, 0.25);
    background: linear-gradient(105deg, #D42C1F 0%, #E35222 100%);
    transform: translateY(-1px);
  }

  .btn-ghost {
    border-radius: 12px;
    font-weight: 700;
    color: #C23D2F !important;
    /* border: 1px solid #d44444;
    background: #e74c3c; */
    border: 1.5px solid #E23729;
    background: transparent;
    box-shadow: none;
    transition: all 0.18s ease;
  }

  .btn-ghost:hover {
    /* background: #c0392b;
    border-color: #c0392b; */
   background: linear-gradient(105deg, #E85D4A 0%, #EE7A6A 100%) !important;
    border-color: #E23729;
    /* color: #ffffff; */
    color: #ffffff !important;
    box-shadow: 0 10px 22px rgba(192, 57, 43, 0.28);
    transform: translateY(-1px);
  }

  .btn_tag.active,
  .btn_category.active {
    /* background: #2fba9e !important; */
    color: #ffffff !important;
    /* border-color: #2fba9e !important; */
    background: linear-gradient(105deg, #E23729 0%, #EC5E29 100%) !important;
    border-color: transparent !important;
    box-shadow: 0 2px 8px rgba(226, 55, 41, 0.25);
  }

  .btn_tag,
  .btn_category {
    border-radius: 10px;
    /* background: #f7fafc !important;
    border: 1px solid #d6ecfb !important;
    color: #0f2e3e !important; */
    background: #FDF1EC !important;
    border: 1px solid #FFE0D6 !important;
    color: #C23D2F !important;
    transition: all 0.16s ease;
  }

  .btn_tag:hover,
  .btn_category:hover {
    background: #FCF1F0 !important;
    border-color: #E23729 !important;
    color: #E23729 !important;
    transform: translateY(-1px);
  }

  .goods-divider {
    border: 0;
    height: 1px;
    /* background: linear-gradient(90deg, rgba(55, 192, 169, 0.22), rgba(108, 199, 245, 0.35), rgba(55, 192, 169, 0.22)); */
    background: linear-gradient(90deg, rgba(226, 55, 41, 0.1), rgba(236, 94, 41, 0.3), rgba(226, 55, 41, 0.1));
    margin: 8px 0 4px;
  }

  .custom-file-input,
  .custom-file-label {
    /* border-color: #d6ecfb; */
    border-color: #FFE0D6 !important;
    background-color: #FDF1EC !important;
    color: #C23D2F !important;
    box-shadow: none !important;
  }

  .custom-file-input:focus,
  .custom-file-input:hover,
  .custom-file-label:hover {
    border-color: #E23729 !important;
    background-color: #FCF1F0 !important;
  }

  /* 文件浏览按钮样式 */
  .custom-file-label::after {
    background: linear-gradient(105deg, #E23729 0%, #EC5E29 100%) !important;
    color: #fff !important;
    border-radius: 0 8px 8px 0 !important;
  }

  /* 发布类型切换容器 - 设置相对定位 */
  .publish-type-switch {
    position: relative;
    background: #f5f5f5;
    border-radius: 12px;
    padding: 4px;
    display: inline-flex;
  }

  /* 滑动滑块 */
  .publish-type-switch .slider-bg {
    position: absolute;
    top: 4px;
    bottom: 4px;
    width: 50%;
    background: linear-gradient(105deg, #E23729 0%, #EC5E29 100%);
    border-radius: 10px;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 0;
  }

  /* 发布类型切换按钮改为绿系 */
  .publish-type-switch .btn {
    /* color: #2fba9e;
    border-color: #2fba9e !important; */
    color: #E23729;
    border-color: #E23729 !important;
    /* background: #ffffff !important; */
    background: transparent !important;
    font-weight: 700;
    transition: all 0.18s ease;
    position: relative;
  }

  .publish-type-switch .btn:hover {
    color: #ffffff;
    /* background: #2fba9e !important;
    border-color: #2fba9e !important; */
    background: linear-gradient(105deg, #E23729 0%, #EC5E29 100%) !important;
    border-color: transparent !important;
    box-shadow: 0 6px 16px rgba(47, 186, 158, 0.2);
  }

  .publish-type-switch .btn.active,
  .publish-type-switch .btn:focus,
  .publish-type-switch .btn:active,
  .publish-type-switch .btn.active:focus,
  .publish-type-switch .btn.active:hover,
  .publish-type-switch .btn-outline-primary:not(:disabled):not(.disabled).active,
  .publish-type-switch .btn-outline-primary:not(:disabled):not(.disabled):active,
  .publish-type-switch .show>.btn-outline-primary.dropdown-toggle {
    color: #ffffff;
    /* background: #2fba9e !important;
    border-color: #2fba9e !important; */
    background: linear-gradient(105deg, #E23729 0%, #EC5E29 100%) !important;
    border-color: transparent !important;
    /* box-shadow: 0 6px 16px rgba(47, 186, 158, 0.2); */
    box-shadow: 0 4px 12px rgba(226, 55, 41, 0.25);
  }

  #map_service_area {
    display: none;
    /* 默认隐藏所有地图相关的元素 */
    padding: 15px 0;
  }

  #map_container {
    width: 100%;
    height: 300px;
    /* 您可根据排版需要调整高度 */
    margin-top: 10px;
  }

  #result_display {
    /* border: 1px dashed #2fba9e;  */
    border: 1px solid #FFE0D6;
    padding: 12px;
    /* background: #f8fffb; */
    background: #FDF1EC;
    border-radius: 8px;
    margin-top: 15px;
  }

  .highlight-loc {
    display: none;
    font-weight: bold;
    color: #007bff;
    cursor: text;
    /* 提示用户可编辑 */
  }

  #location_toggle_area {
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f4f8fb;
  }
</style>

<div class="goods-page">
  <div class="card goods-card">
    <ul class="list-group list-group-flush">
      <li class="list-group-item">
        <div class="row mt-2 align-items-center">
          <i class="far fa-edit ml-3 mr-2" style="font-size: 20px;font-weight:bold;"></i>
          <h4 class="mb-0" style="line-height: 20px;font-weight:550">
            @if(isset($goods_info->id))
            编辑发布
            @else
            发布信息
            @endif
          </h4>

          <div class="ml-4">
            @php
            $initialPublishType = old('publish_type') ?? (isset($goods_info->type) && $goods_info->type == \App\Models\Good::TYPE_BUY ? 'buy' : 'good');
            @endphp
            <div class="btn-group btn-group-toggle publish-type-switch" data-toggle="buttons">
              <label class="btn btn-sm btn-outline-primary publish-type-btn @if($initialPublishType === 'good') active @endif" data-type="good">
                <input type="radio" autocomplete="off" @if($initialPublishType==='good' ) checked @endif> 发布商品
              </label>
              <label class="btn btn-sm btn-outline-primary publish-type-btn @if($initialPublishType === 'buy') active @endif" data-type="buy">
                <input type="radio" autocomplete="off" @if($initialPublishType==='buy' ) checked @endif> 发布求购
              </label>
            </div>
          </div> 
       
        </div>
        <hr class="goods-divider">

      </li>

    </ul>

    <div class="card-body">
      @php
      // 判断是否为编辑模式（$goods_info 对象存在且有 ID）
      $isEditMode = isset($goods_info) && $goods_info->id;

      // 设置表单提交的路由
      $formAction = $isEditMode
      ? route('goods.update', $goods_info->id) // 编辑路由: /goods/{id}
      : route('create_goods_check'); // 创建路由: /goods
      @endphp

      <form class="form_create_goods" action="{{ $formAction }}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">

        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        @if($isEditMode)
        {{-- 只有在编辑模式下，才包含 PUT 伪装域，用于触发 goods.update 路由 --}}
        <input type="hidden" name="_method" value="PUT">
        @endif

        <input type="hidden" name="publish_type" class="publish_type" value="{{ $initialPublishType ?? 'good' }}">
        {{-- ============================================================================== --}}

        {{-- ... 表单的其余部分从这里开始接续 ... --}}

        <div class="form-group " style="width:810px;">
          <label for="title" style="font-size:16px">标题</label>
          <div class="row ml-0">
            <input type="" autocomplete="on" maxlength="255" name="title" class="form-control" id="title" placeholder="显示在商品列表页..." style="width:750px" value="@if(isset($goods_info)){{ $goods_info->title }}@endif" required>
            <div style="line-height:35px;color:#636b6f" class="ml-2"></div>
          </div>
        </div>

        <div class="form-group" style="width:750px;">
          <label for="description" style="font-size:16px">描述</label>
          <textarea maxlength="512" class="form-control" id="description" rows="2" placeholder="显示在商品详情页..." name="description" style="height:52px;max-height: 126px;min-height: 52px;" required>@if(isset($goods_info)){{ $goods_info->description }}@endif</textarea>
        </div>

        <div class="form-group" style="width:750px;">
          <label for="tag" class="label_tag" style="font-size:15px">卖家标签</label>
          <div>
            <?php $index = 0 ?>

            @foreach($good_tag as $tags=>$tag)

            <button type="button" class="btn btn_tag @if(isset($goods_info) && $index <= count($tags_data->toArray())-1) @if($tag->name == $tags_data->toArray()[$index]['name'] ) active  <?php $index++ ?> @endif @elseif($tags == 0) active  @endif" style="outline:none;line-height:15px;height:27px;font-size: 12px;">
              {{ $tag->name }}
            </button>
            @endforeach

          </div>

          <input class="tags_data" type="text" name="tag_data" hidden>
        </div>

        <div class="form-group">
          <label for="price" class="label_price" style="font-size:16px">标价</label>
          <div class="row ml-0">
            <input type="" autocomplete="on" name="price" maxlength="6" minlength="1" class="form-control" id="price" placeholder="请填写数字价格，最多保留一位小数..." style="width:405px;" value="@if(isset($goods_info)){{ $goods_info->price }}@endif" required>
            <span style="line-height:35px ;color:#636b6f;" class="ml-2">填写范围为 0.1 ~ 9999.9</span>
            <div class="wrong_tip_price"></div>
          </div>
        </div>

        <div class="form-group group_old_price">
          <label for="old_price" style="font-size:15px">原价</label>
          <input type="text" autocomplete="on" name="old_price" maxlength="6" class="form-control " id="old_price" placeholder="" style="width:405px;" value="@if(isset($goods_info)){{ $goods_info->old_price }}@endif" required>
          <div class="wrong_tip_oprice "></div>
        </div>

        <div class="form-group">
          <label for="categories" style="font-size:16px">分区</label>
          <div>
            @php
            $activeCategories = $selectedCategories ?? [];
            $oldCategories = collect(explode('-', old('categories_data', '')))
            ->filter(function($value) {
            return $value !== '' && is_numeric($value);
            })
            ->map(function($value){
            return (int) $value;
            })
            ->toArray();
            if(!empty($oldCategories)){
            $activeCategories = $oldCategories;
            }
            @endphp
            @php
            $otherCategory = null;
            // 控制“默认选中”的标记，避免因为跳过“其他”导致 $loop->first 失效
            $hasDefaultCategory = false;
            @endphp
            @foreach($categories as $category)
            @if($category->name === '其他')
            @php $otherCategory = $category; @endphp
            @continue
            @endif
            @if($category->name === '心愿单')
            @continue
            @endif
            @php
            $isActive = (!empty($activeCategories) && in_array($category->id, $activeCategories))
            || (empty($activeCategories) && !$hasDefaultCategory);
            if(empty($activeCategories) && !$hasDefaultCategory){
            $hasDefaultCategory = true;
            }
            @endphp
            <button type="button"
              class="btn btn_category {{ $isActive ? 'active' : '' }}"
              data-id="{{ $category->id }}"
              style="outline:none;line-height:15px;height:27px;font-size: 12px;margin-right:5px;margin-bottom:5px;">
              {{ $category->name }}
            </button>
            @endforeach
            @if($otherCategory)
            @php
            $isActive = (!empty($activeCategories) && in_array($otherCategory->id, $activeCategories))
            || (empty($activeCategories) && !$hasDefaultCategory);
            @endphp
            <button type="button"
              class="btn btn_category {{ $isActive ? 'active' : '' }}"
              data-id="{{ $otherCategory->id }}"
              style="outline:none;line-height:15px;height:27px;border: 1px solid #e8eaec; background: #f7f7f7;font-size: 12px;color: #515a6e;margin-right:5px;margin-bottom:5px;">
              {{ $otherCategory->name }}
            </button>
            @endif
          </div>
          <small class="text-muted">可多选，商品将在每个选择的分区里展示</small>
          <input class="categories_data" type="text" name="categories_data" hidden>
        </div>

        <div class="form-group mt-4">
          <p style="font-size:17px;">商品图片：<small style="color: #969696;">【第一张默认为封面图片, 建议上传图片长宽为：450x460】</small>
            <button type="button" id="add" class="btn btn-main ml-2" style="line-height:20px;width:110px;height:36px;">
              添加图片
            </button>
          </p>
        </div>

        {{-- @if( $booking )
        <input type="" id="booking_operate_premise" hidden>
      @elseif( $order )
        <input type="" id="order_operate_premise"  hidden>
      @endif
      <input type="" id="user_id" value="{{ Auth::user()->id }}" hidden> --}}

        <!-- 图片文件 -->
        @if(isset($goods_info))
        <?php $arrImg = explode(',', $goods_info->image) ?>
        @endif
        <div class="img_file form-group">
          <div class=" input-group imgFile" style="width:91%;">
            <div class="input-group-append"></div>
            <div class=" custom-file">
              <input type="file" title="点击上传商品图片 ^_^ " class="custom-file-input file" img_data="@if(isset($goods_info)){{ $goods_info->image }}@endif" name="goods_img[]" id="validatedInputGroupCustomFile" autocomplete="off" required>
              <label class="custom-file-label " for="validatedInputGroupCustomFile">@if(isset($goods_info)) {{ 'P0.'.Str::afterLast($arrImg[0], '.') }} @else 选择图片文件... @endif</label>
            </div>
          </div>
          <div class=" file_tip_size"> </div>
          <div class=" file_tip_type"> </div>

          @if(isset($goods_info))
          @for($i=1;$i<sizeof($arrImg);$i++)
            <div class="mt-4 input-group imgFile" style="width:91%;">
            <div class="input-group-append">
              <button class="btn  btn-danger del_btn" type="button" style="top:-1px;border-top-left-radius: 3px;border-bottom-left-radius: 3px;">
                <i class="fas fa-times-circle " style="color:#fff;"></i>
              </button>
            </div>
            <div class="custom-file">
              <input type="file" class="custom-file-input file" title="点击上传商品图片 ^_^ " name="goods_img[]" id="validatedInputGroupCustomFile" required>
              <label class="custom-file-label imgName" for="validatedInputGroupCustomFile">{{ "P$i.".Str::afterLast($arrImg[0], '.') }}</label>
            </div>
        </div>
        <div class="file_tip_size"></div>
        <div class="file_tip_type"></div>
        @endfor
        @endif
    </div>

    @php
    // Blade 数据回显逻辑，确保在创建和编辑时都能正确加载数据
    $address = old('address', $goods->address ?? 'NO');
    $latitude = old('latitude', $goods->latitude ?? 0.0000000);
    $longitude = old('longitude', $goods->longitude ?? 0.0000000);
    $city = old('city', $goods->city ?? 'NO');
    @endphp

    <div class="form-group" id="location_toggle_area">
      <label>
        <input type="checkbox" id="location_toggle" onchange="toggleLocationService(this.checked)">
        **启用地图定位服务**
      </label>
      <p style="margin: 5px 0 0; font-size: 0.85em; color: #555;">启用后可搜索地址、拖动地图确定商品精确位置。</p>
    </div>

    <div id="map_service_area">
      <div class="form-group">
        <label for="suggest_input">地址搜索：</label>
        <input type="text" id="suggest_input" placeholder="输入地址关键词搜索" class="form-control">
      </div>
      <div id="map_container"></div>
    </div>


    <span id="display_address" class="highlight-loc" contenteditable="true">{{ $address }}</span>
    <span id="display_city" class="highlight-loc" contenteditable="true">{{ $city }}</span>
    <span id="display_lat" class="highlight-loc" contenteditable="true">{{ number_format($latitude, 7, '.', '') }}</span>
    <span id="display_lng" class="highlight-loc" contenteditable="true">{{ number_format($longitude, 7, '.', '') }}</span>

    {{-- 隐藏的表单字段：用于将最终数据提交给 Laravel 后端 --}}
    <input type="hidden" name="address" id="input_address"
      value="@if(isset($goods_info)){{ $goods_info->address }}@endif">

    <input type="hidden" name="city" id="input_city"
      value="@if(isset($goods_info)){{ $goods_info->city }}@endif">

    <input type="hidden" name="latitude" id="input_latitude"
      value="@if(isset($goods_info)){{ number_format($goods_info->latitude, 7, '.', '') }}@else 0.0000000 @endif">

    <input type="hidden" name="longitude" id="input_longitude"
      value="@if(isset($goods_info)){{ number_format($goods_info->longitude, 7, '.', '') }}@else 0.0000000 @endif">
    <div class="form-group form-check mb-2 row" style="right:20px">
      <button type="submit" hidden class="btn btn-primary mt-4 ml-3 btn_submit" style="line-height:20px;margin-right:10px;width:90px;height:32px">
      </button>
      <button type="button" class="btn btn-main mt-5 ml-3 btn_now" style="line-height:20px;margin-right:10px;width:120px;height:40px">
        立即发布
      </button>

      <button type="button" title="保存" class="btn btn-ghost mt-5 ml-3 btn_wait" style="line-height:20px;margin-right:10px;width:120px;height:40px">
        暂时保存
      </button>
      <input class="state" name="goods_state" type="text" hidden>
      <!--记录状态-->
      <input class="goods_old_img" type="text" name="goods_old_img" hidden> <!-- 记录old_img -->

      @if(isset($goods_info->id))
      <input class="id" type="text" value="{{ $goods_info->id }}" name="id" hidden>
      @endif

      @foreach (['wrong_type', 'null_data'] as $msg)
      @if(session()->has($msg))
      <span id='tip' msg-data="{{session()->get($msg) }}" hidden></span>
      @endif
      @endforeach

    </div>
    </form>


    @error('title')
    <input type="text" id="wrong_title" value="{{ $message }}" hidden>
    @enderror

    @error('description')
    <input type="text" id="wrong_description" value="{{ $message }}" hidden>
    @enderror

  </div>

</div>
@stop

@section('scriptsAfterJs')
<script>
  $(document).ready(function() {
    if ($('#wrong_title').length > 0) {
      swal({
        text: $('#wrong_title').val(),
        icon: 'error'
      })
    } else {
      if ($('#wrong_description').length > 0) {
        swal({
          text: $('#wrong_description').val(),
          icon: 'error'
        })
      }
    }

    // 判断是否有 错误提示
    msg_data = $('#tip').attr('msg-data')
    if (msg_data) {
      console.log(msg_data)
      swal({
        text: msg_data,
        icon: 'warning'
      })
    }


    // 处理发布类型（商品 / 求购）
    function applyPublishType(type) {
      $('.publish_type').val(type)

      if (type === 'buy') {
        // 求购：隐藏原价，改为预期价格，买家标签
        $('.group_old_price').hide()
        $('.label_price').text('预期价格')
        $('.label_tag').text('买家标签')
        $('#old_price').prop('required', false).val('')

        // 隐藏不适合求购的标签：诚意转让、如假包退
        $('.btn_tag').each(function() {
          var text = $(this).text().trim()
          if (text === '诚意转让' || text === '如假包退') {
            $(this).removeClass('active').hide()
          } else {
            $(this).show()
          }
        })
      } else {
        // 发布商品：显示原价，恢复标价，卖家标签
        $('.group_old_price').show()
        $('.label_price').text('标价')
        $('.label_tag').text('卖家标签')
        $('#old_price').prop('required', true)

        // 所有标签恢复可见
        $('.btn_tag').each(function() {
          $(this).show()
        })
      }

      // 切换模式后重算分区和标签
      setCategoriesData()
    }

    // 初始化发布类型
    var initialType = $('.publish_type').val() || 'good'
    applyPublishType(initialType)

    // 顶部切换按钮事件
    $('.publish-type-btn').click(function() {
      var type = $(this).data('type');
      $('.publish-type-btn').removeClass('active');
      $(this).addClass('active');
      applyPublishType(type);
    })

    // 循环遍历-为active 添加样式
    $(".btn_tag").each(function(index) {

      //console.log($(".btn_tag")[index]);
      if ($(".btn_tag").eq(index).hasClass('active')) {
        $(this).css({
          'background': '#2d8cf0',
          'color': '#fff'
        })
      }
    })

    // 标签按钮-点击事件
    var max_tag = true // 记录标签能否选择
    $('.btn_tag').click(function() {
      if (max_tag) {
        $(this).toggleClass('active')
        if ($(this).hasClass('active')) {
          $(this).css({
            'background': '#2d8cf0',
            'color': '#fff'
          })
        } else {
          $(this).css({
            'background': '#f7f7f7',
            'color': '#515a6e'
          })
        }
      } else {
        //console.log('')
        if ($(this).hasClass('active')) {
          $(this).toggleClass('active')
          $(this).css({
            'background': '#f7f7f7',
            'color': '#515a6e'
          })
        } else {
          swal("最多选择四个标签", {
            buttons: false,
            icon: 'warning',
            timer: 2500
          });
        }
      }
      // 最多选择四个标签
      if ($('.btn_tag.active').length == 4) {
        max_tag = false
      } else {
        max_tag = true
      }
    })

    function updateCategoryButtonStyle(btn) {
      if (btn.hasClass('active')) {
        btn.css({
          // 'background': '#34c388',
          // 'color': '#fff'
          'background': '#FDF1EC',
          'color': '#C23D2F', // 深红色，看得见
          'border': '1px solid #FFE0D6'
        })
      } else {
        btn.css({
          // 'background': '#f7f7f7',
          // 'color': '#515a6e'
          'background': 'linear-gradient(105deg, #E23729 0%, #EC5E29 100%)',
          'color': '#ffffff', // 白色
          'border': 'none'
        })
      }
    }

    $(".btn_category").each(function() {
      updateCategoryButtonStyle($(this))
    })

    $('.btn_category').click(function() {
      $(this).toggleClass('active')
      updateCategoryButtonStyle($(this))
      // 分区按钮每次变更时，立即同步 hidden 字段，避免提交时数据为空
      setCategoriesData()
    })

    function setCategoriesData() {
      var category_data = ''
      $.each($('.btn_category'), function(i, val) {
        if ($(this).hasClass('active')) {
          category_data = category_data === '' ?
            String($(this).data('id')) :
            category_data + '-' + String($(this).data('id'))
        }
      })

      // 如果当前没有任何激活的分区，但页面上是有分区按钮的，
      // 为了避免“请选择至少一个分区”的误报，自动选中第一个按钮
      if (category_data.length === 0 && $('.btn_category').length > 0) {
        var firstBtn = $('.btn_category').first()
        firstBtn.addClass('active')
        updateCategoryButtonStyle(firstBtn)
        category_data = firstBtn.data('id')
      }

      $('.categories_data').val(category_data)
      return category_data !== ''
    }

    setCategoriesData()

    function reg_price() {
      var price = $("#price").val();

      var d_reg = /^0{1}\.{1}[1-9]{1}$/; // 小数-首位可以0，小数位不为0
      var d_reg2 = /^[1-9]{1}\d{0,3}\.{1}\d{1}$/; //  小数-首位不能0，小数位可为0
      var int_reg = /^[1-9]{1}\d{0,5}$/; // 整数-首位不能0
      if ($('#price').val().length != 0) {
        if (d_reg.test(price) || d_reg2.test(price) || int_reg.test(price)) { // 原价格式正确
          $("#price").addClass('is-valid').removeClass('is-invalid');
          $(".wrong_tip_price").addClass('valid-feedback').removeClass('invalid-feedback');
          $(".wrong_tip_price").html('');
        } else {
          $("#price").addClass('is-invalid').removeClass('is-valid');
          $(".wrong_tip_price").addClass('invalid-feedback').removeClass('valid-feedback');
          $(".wrong_tip_price").html('<strong>格式错误，请重新填写！!</strong>');
        }
      } else {
        $("#price").removeClass('is-valid').removeClass('is-invalid');
        $(".wrong_tip_price").removeClass('valid-feedback').removeClass('invalid-feedback');
        $(".wrong_tip_price").html('');
      }
    }
    // 验证标价字段
    reg_price()
    $('#price').blur(function() {
      reg_price()
    })

    function reg_old_price() {
      var price = $("#old_price").val();
      var d_reg = /^0{1}\.{1}[1-9]{1}$/; // 小数-首位可以0，小数位不为0
      var d_reg2 = /^[1-9]{1}\d{0,3}\.{1}\d{1}$/; //  小数-首位不能0，小数位可为0
      var int_reg = /^[1-9]{1}\d{0,5}$/; // 整数-首位不能0
      if ($('#old_price').val().length != 0) {
        if (d_reg.test(price) || d_reg2.test(price) || int_reg.test(price)) { // 原价格式正确
          $("#old_price").addClass('is-valid').removeClass('is-invalid');
          $(".wrong_tip_oprice").addClass('valid-feedback').removeClass('invalid-feedback');
          $(".wrong_tip_oprice").html('');
        } else {
          $("#old_price").addClass('is-invalid').removeClass('is-valid');
          $(".wrong_tip_oprice").addClass('invalid-feedback').removeClass('valid-feedback');
          $(".wrong_tip_oprice").html('<strong>格式错误，请重新填写！!</strong>');
        }
      } else {
        $("#old_price").removeClass('is-valid').removeClass('is-invalid');
        $(".wrong_tip_oprice").removeClass('valid-feedback').removeClass('invalid-feedback');
        $(".wrong_tip_oprice").html('');
      }
    }
    // 验证原价字段
    reg_old_price()
    $('#old_price').blur(function() {
      reg_old_price()
    })


    // 用于记录 编辑商品 时的图片
    var img_type
    var arrImg = false
    //arrImg = $('.file').eq(0).attr('img_data')
    //console.log(arrImg)
    //arrImg = arrImg.split(',').slice(0, -1) // 转数组， 记录三种情况： url-原图片链接  0-删除  'update'-新图片
    //$('.file').eq(0).attr('img_data', '')
    if ($('.file').eq(0).attr('img_data') != '') {
      arrImg = $('.file').eq(0).attr('img_data')

      console.log(arrImg)
      //arrImg = arrImg.split(',').slice(0, -1) // 转数组， 记录三种情况： url-原图片链接  0-删除  'update'-新图片

      arrImg = arrImg.split(',')

      console.log(arrImg)

      $('.file').eq(0).attr('img_data', '')
      img_type = 'edit'
    } else {
      img_type = 'create'
    }
    for (i = 0; i < $('.file').length; i++) {
      $('.file').eq(i).next().css('border-color', '#37c0a9')
    }

    // console.log(img_type)

    // 添加商品图片-点击事件
    $('#add').click(function() {

      if ($("input[type='file']").length >= 4) {
        // console.log('最多选择四个图片！')
        swal("最多选择四个图片", {
          buttons: false,
          icon: 'warning',
          timer: 2500
        });
        return false;
      }

      $('.img_file').append(`
      <div class="mt-4 input-group imgFile" style="width:91%;">
        <div class="input-group-append">
          <button class="btn  btn-danger del_btn" type="button" style="top:-1px;border-top-left-radius: 3px;border-bottom-left-radius: 3px;">
            <i class="fas fa-times-circle " style="color:#fff;"></i>
          </button>
        </div>
        <div class="custom-file" >
          <input type="file" class="custom-file-input file" title="点击上传商品图片 ^_^ " name="goods_img[]" id="validatedInputGroupCustomFile"  required>
          <label class="custom-file-label imgName" for="validatedInputGroupCustomFile" >选择图片文件...</label>
        </div>
       </div>
      <div class="file_tip_size"></div>
      <div class="file_tip_type"></div>`)

      if (arrImg) {
        arrImg.push('0') // 添加0，表示空
      }

    })

    $(".img_file ").on("click", "button", function() { // 移除图片
      //console.log( $(this).parent().parent().index() );     // 3 6 9
      if (arrImg) {
        if ($(this).parent().parent().index() == 3) arrImg.splice(1, 1); // 删除下标1
        if ($(this).parent().parent().index() == 6) arrImg.splice(2, 1); // 删除下标2
        if ($(this).parent().parent().index() == 9) arrImg.splice(3, 1); // 删除下标3
      }


      $(this).parent().parent().next().remove()
      $(this).parent().parent().next().remove()
      $(this).parent().parent().remove();
    })


    // 验证上传图片(类型、大小、宽)-change事件
    $(".img_file").on("change", "input", function() {

      if (arrImg) {

        if ($(this).parent().parent().index() == 0) arrImg[0] = 'update'; // 修改下标0
        if ($(this).parent().parent().index() == 3) arrImg[1] = 'update'; // 修改下标1
        if ($(this).parent().parent().index() == 6) arrImg[2] = 'update'; // 修改下标2
        if ($(this).parent().parent().index() == 9) arrImg[3] = 'update'; // 修改下标3
      }

      console.log(arrImg)
      //console.log($(this)[0].files[0])
      reg_img($(this), false, '', arrImg)

      // 验证图片宽高
      // var reader = new FileReader();
      // reader.readAsDataURL(file);
      // reader.onload = function(e) {
      //   var data = e.target.result;
      //   //加载图片获取图片真实宽度和高度
      //   var image = new Image();
      //   image.src = data;
      //   // 图片先加载完，才可以得到图片宽度和高度
      //   image.onload = function() {
      //     var width = image.width;
      //     var height = image.height;
      //     // 验证图片宽高 最小宽和高278和318-最大宽和高480和518
      //     // 宽超过480，则设计一个tip，提示自动裁剪
      //     //console.log('宽：', width)
      //     // if (width > 480) {
      //     //   console.log('自动裁剪')
      //     // }
      //   }
      // }

    })


    // 函数-原图格式还原
    function reg_old_img(img, is_submit, i = '') {
      if (is_submit) {
        img = img.eq(i)
        img.next().css('border-color', '#37c0a9')
        img.parents('.imgFile').addClass('is-valid').removeClass('is-invalid')
        img.parent().parent().next().addClass('valid-feedback').removeClass('invalid-feedback')
        img.parent().parent().next().next().addClass('valid-feedback').removeClass('invalid-feedback')
        img.parent().parent().next().html('');
        img.parent().parent().next().next().html('');
      }
    }
    // 函数-验证图片
    function reg_img(img, is_submit, i = '', arrImg) {

      if (is_submit) {
        file = img[i].files[0]
        img = img.eq(i)

        img.next().css('border-color', '#37c0a9')
        img.parents('.imgFile').addClass('is-valid').removeClass('is-invalid')
        img.parent().parent().next().addClass('valid-feedback').removeClass('invalid-feedback')
        img.parent().parent().next().next().addClass('valid-feedback').removeClass('invalid-feedback')
        // img.parent().parent().next().html('');
        // img.parent().parent().next().next().html('');

      } else file = img[0].files[0]

      //console.log(img)
      //console.log(file['name'])

      img_ext = file.type // 获取图片类型
      //console.log(img_ext)
      var png = new RegExp('png');
      var jpg = new RegExp('jpg');
      var jpeg = new RegExp('jpeg');
      var gif = new RegExp('gif');
      img_size = Math.floor(file.size / 1024)

      //console.log(img_size)
      if (png.test(img_ext) || jpg.test(img_ext) || jpeg.test(img_ext) || gif.test(img_ext)) {

        if (img_size > 1024) {
          //console.log('大于200k');

          img.next().css('border-color', '#dc3545')
          img.next().html('选择图片文件...')
          img.parents('.imgFile').addClass('is-invalid').removeClass('is-valid')
          img.parent().parent().next().addClass('invalid-feedback').removeClass('valid-feedback')
          img.parent().parent().next().html('图片大小超过1M，请重新选择！!');
          img.parent().parent().next().next().addClass('valid-feedback').removeClass('invalid-feedback')
          img.parent().parent().next().next().html('');

        } else { // 图片格式、大小都符合
          img.next().css('border-color', '#37c0a9')


          img.next().html(file['name'])
          //img.next().html('xxx')
          img.parents('.imgFile').addClass('is-valid').removeClass('is-invalid')
          img.parent().parent().next().addClass('valid-feedback').removeClass('invalid-feedback')
          img.parent().parent().next().next().addClass('valid-feedback').removeClass('invalid-feedback')
          img.parent().parent().next().html('');
          img.parent().parent().next().next().html('');
          //}
        }
      } else { // 格式不正确
        //console.log('格式不正确');
        img.next().css('border-color', '#dc3545')
        img.next().html('选择图片文件...')
        img.parents('.imgFile').addClass('is-invalid').removeClass('is-valid')
        img.parent().parent().next().next().addClass('invalid-feedback').removeClass('valid-feedback')
        img.parent().parent().next().next().html('不支持该图片格式，请选择 【GIF JPG JPEG PNG】 格式的图片！!');
        img.parent().parent().next().addClass('valid-feedback').removeClass('invalid-feedback')
        img.parent().parent().next().html('');

        if (img_size > 1024) { // 都不正确
          //console.log('都不正确');
          img.next().css('border-color', '#dc3545')
          img.next().html('选择图片文件...')
          img.parents('.imgFile').addClass('is-invalid').removeClass('is-valid')
          img.parent().parent().next().addClass('invalid-feedback').removeClass('valid-feedback')
          img.parent().parent().next().next().addClass('invalid-feedback').removeClass('valid-feedback')
          img.parent().parent().next().html('不支持该图片格式，请重新选择 【GIF JPG JPEG PNG】 格式的图片！!');
          img.parent().parent().next().next().html('图片大小超过1M，请重新选择！!');
        }
      }

    }



    // 立即发布按钮-点击事件
    $('.btn_now').click(function() {

      var publishType = $('.publish_type').val() || 'good'
      var isBuy = publishType === 'buy'

      //console.log(arrImg)
      // 验证
      reg_old_price()
      reg_price()


      // 设置标签数据
      //console.log($('.btn_tag'))
      var tag_data = ''
      $.each($('.btn_tag'), function(i, val) {
        if ($(this).hasClass('active')) {
          if (tag_data == '') {
            tag_data = (i + 1)

          } else {
            tag_data = tag_data + '-' + (i + 1)
          }
        }
      })
      $('.tags_data').val(tag_data)
      //console.log($('.tags_data').val())

      // 仅在前端同步分区数据，是否选择分区交由后端校验
      setCategoriesData();

      // 设置状态-发布
      $('.state').val(1)

      var file

      for (var i = 0; i < $('.file').length; i++) { // 判断所有图片是否为空
        //console.log($(".file").eq(1).val().length)
        if ($(".file").eq(i).val().length == 0) {
          if (arrImg != false && arrImg[i] != '0') {
            reg_old_img($('input[type=file]'), true, i)
            continue
          } else {
            file = 0
            break
          }

        } else {
          //console.log(123)
          reg_img($('input[type=file]'), true, i, arrImg) // 验证
        }

      }
      console.log(arrImg)
      //console.log($('#description').val())
      if (file == 0 ||
        $('#description').val().length == 0 ||
        $('#title').val().length == 0 ||
        $('#price').val().length == 0 ||
        (!isBuy && $('#old_price').val().length == 0)) {
        //$('.btn_submit').trigger('click')

        // 弹框提示
        swal({
          text: '你还有未填的选项，无法提交！',
          icon: 'warning'
        })

      } else {
        if ($('.is-invalid').length != 0) {
          swal({
            text: '有错误选项，无法提交！',
            icon: 'error'
          })
        } else {
          var _this = $(this)

          if (isBuy) {
            swal({
              title: '确定立即发布?',
              icon: 'warning',
              buttons: ['取消', '确定'],
              dangerMode: true,
            }).then((res) => {
              if (!res) return;

              _this.attr('disabled', 'true')
              _this.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Loading...')
              if (arrImg) $('.goods_old_img').val(arrImg)
              else $('.goods_old_img').val(null)
              $('.form_create_goods').submit()
            })
            return
          }

          if ($('#old_price').val().length != 0 && $('#price').val().length != 0) {

            var price = parseFloat($('#price').val());
            var old_price = parseFloat($('#old_price').val())
            if (price > old_price) {
              // console.log('标价大于原价！')
              swal({
                title: '你确定吗?',
                text: "该商品的标价高于原价 ！",
                icon: 'warning',
                buttons: ['取消', '确认'],
                dangerMode: true,
              }).then((res) => {
                if (!res) return;

                if (arrImg) $('.goods_old_img').val(arrImg) // 也要 arrImg
                else $('.goods_old_img').val(null)
                $('.form_create_goods').submit()
              })
              $('.swal-text').addClass('warning_text'); // 控制swal-text的样式
              $('.swal-footer').css("text-align", "center") // 确认取消按钮-居中
            } else {

              //console.log()
              //console.log($('.goods_old_img').val())
              swal({
                title: '确定立即发布?',
                icon: 'warning',
                buttons: ['取消', '确定'],
                dangerMode: true,
              }).then((res) => {
                if (!res) return;

                _this.attr('disabled', 'true')
                _this.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Loading...')

                //console.log(arrImg);

                if (arrImg) $('.goods_old_img').val(arrImg)
                else $('.goods_old_img').val(null)

                $('.form_create_goods').submit()

              })
            }

          }

        }
      }
    })


    // 暂不发布按钮-点击事件
    $('.btn_wait').click(function() {

      var publishType = $('.publish_type').val() || 'good'
      var isBuy = publishType === 'buy'

      // 再次验证
      //console.log($('input[type=file]').length)
      for (var i = 0; i < $('input[type=file]').length; i++) {
        //reg_img($('input[type=file]'),true,i)
      }
      reg_old_price()
      reg_price()

      // 设置标签数据
      //console.log($('.btn_tag'))
      var tag_data = ''
      $.each($('.btn_tag'), function(i, val) {
        if ($(this).hasClass('active')) {
          if (tag_data == '') {
            tag_data = (i + 1)

          } else {
            tag_data = tag_data + '-' + (i + 1)
          }
        }
      })
      $('.tags_data').val(tag_data)
      //console.log($('.tags_data').val())

      // 仅在前端同步分区数据，是否选择分区交由后端校验
      setCategoriesData();

      // 设置状态-未发布
      $('.state').val(0)

      var file
      for (var i = 0; i < $('.file').length; i++) { // 判断所有图片是否为空
        //console.log($(".file").eq(i).val())
        if ($(".file").eq(i).val().length == 0) {

          if (arrImg != false && arrImg[i] != '0') {
            reg_old_img($('input[type=file]'), true, i) // 未改的图片 格式还原
            continue
          } else {
            file = 0
            break
          }
        } else {
          reg_img($('input[type=file]'), true, i, arrImg)
        }
      }
      if (file == 0 ||
        $('#title').val().length == 0 ||
        $('#description').val().length == 0 ||
        $('#price').val().length == 0 ||
        (!isBuy && $('#old_price').val().length == 0)) {
        //$('.btn_submit').trigger('click')
        swal({
          text: '你还有未填的选项，无法提交！',
          icon: 'warning'
        })

      } else {
        if ($('.is-invalid').length != 0) {
          swal({
            text: '有错误选项，无法提交！',
            icon: 'error'
          })
        } else {

          swal({
            title: '暂不发布',
            text: '?',
            buttons: ['取消', '确定'],
          }).then((res) => {
            if (!res) return;

            $(this).attr('disabled', 'true')
            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Loading...')

            if (arrImg) $('.goods_old_img').val(arrImg) // 传 旧图url
            else $('.goods_old_img').val(null)
            $('.form_create_goods').submit()

          })

          $('.swal-text').css({
            "background-color": "#FEFAE3",
            "padding": "17px",
            "border": "1px solid #F0E1A1",
            "display": "block",
            "margin": "22px",
            "text-align": "center",
            "color": "#61534e"
          })
          $('.swal-text').html('暂不发布商品可在 【个人中心】&#10132【我的商品】&#10132【预发布】 中查看')
        }
      }

    })



  })
</script>

</div>
</div>

<script type="text/javascript" src="https://api.map.baidu.com/api?v=3.0&ak=UemmJ3s8IXzsI6aFdg912AipO2drJyvn"></script>

<script>
  // ==========================================
  // ** 百度地图定位逻辑 (请添加在文件底部) **
  // ==========================================

  // 特殊值定义 (与后端保持一致)
  var SPECIAL_LAT_LNG = 0.0000000;
  var SPECIAL_STRING = 'NO';

  // 全局变量
  var map = null;
  var marker = null;
  var isMapInitialized = false;

  // --- 1. 切换地图服务的显示与初始化 (被 HTML onchange 调用) ---
  window.toggleLocationService = function(isEnabled) {
    var mapArea = document.getElementById('map_service_area');

    if (isEnabled) {
      mapArea.style.display = 'block';

      // 检查 API 是否加载
      if (typeof BMap === 'undefined') {
        alert("百度地图API未能加载，请检查网络或AK密钥！");
        return;
      }

      if (!isMapInitialized) {
        initMapAndListeners();
      }

      // 如果当前是特殊值（禁用状态），则重置为默认坐标（北京）
      var currentLat = parseFloat(document.getElementById('input_latitude').value);
      var currentLng = parseFloat(document.getElementById('input_longitude').value);

      // 确保地图存在且坐标不是特殊值
      if (map && !isNaN(currentLat) && currentLat != SPECIAL_LAT_LNG) {
        var currentPoint = new BMap.Point(currentLng, currentLat);
        map.centerAndZoom(currentPoint, 15);
        marker.setPosition(currentPoint);
      } else if (map && isNaN(currentLat) || currentLat == SPECIAL_LAT_LNG) {
        // 如果坐标是特殊值（没有保存定位），则使用默认坐标（例如北京）
        var defaultPoint = new BMap.Point(116.404, 39.915); // 默认北京
        map.centerAndZoom(defaultPoint, 15);
        marker.setPosition(defaultPoint);
        // 此时应该更新前端显示，但我们在 initMapAndListeners 中处理了，这里先跳过
      }
    } else {
      mapArea.style.display = 'none';
      // 确保 form 提交时这些字段不为 NULL
      document.getElementById('input_address').value = SPECIAL_STRING;
      document.getElementById('input_city').value = SPECIAL_STRING;
      // 经纬度设置为您的特殊数值
      document.getElementById('input_latitude').value = SPECIAL_LAT_LNG;
      document.getElementById('input_longitude').value = SPECIAL_LAT_LNG;
      // 禁用时，填入特殊值
      updateDisplay({
        address: SPECIAL_STRING,
        latitude: SPECIAL_LAT_LNG,
        longitude: SPECIAL_LAT_LNG,
        city: SPECIAL_STRING
      });
      document.getElementById("suggest_input").value = '';
    }
  }

  // --- 2. 辅助函数：更新显示和隐藏域 ---
  function updateDisplay(data) {
    // 更新显示文本
    document.getElementById('display_address').innerText = data.address;
    document.getElementById('display_city').innerText = data.city;
    document.getElementById('display_lat').innerText = data.latitude.toFixed(7);
    document.getElementById('display_lng').innerText = data.longitude.toFixed(7);

    // 更新提交给后端的隐藏 input
    document.getElementById('input_address').value = data.address;
    document.getElementById('input_city').value = data.city;
    document.getElementById('input_latitude').value = data.latitude.toFixed(7);
    document.getElementById('input_longitude').value = data.longitude.toFixed(7);
  }

  // --- 3. 地图初始化核心逻辑 ---
  function initMapAndListeners() {
    // 防止重复初始化
    if (isMapInitialized) return;

    map = new BMap.Map("map_container");

    // 获取当前坐标（如果是编辑模式，可能是已有坐标）
    var initialLat = parseFloat(document.getElementById('input_latitude').value);
    var initialLng = parseFloat(document.getElementById('input_longitude').value);

    // 如果无效或特殊值，默认北京
    if (isNaN(initialLat) || initialLat == SPECIAL_LAT_LNG) {
      initialLat = 39.915;
      initialLng = 116.404;
    }

    var point = new BMap.Point(initialLng, initialLat);
    map.centerAndZoom(point, 15);
    map.enableScrollWheelZoom(true);

    marker = new BMap.Marker(point);
    map.addOverlay(marker);

    // A. 地址搜索自动填充
    var ac = new BMap.Autocomplete({
      "input": "suggest_input",
      "location": map
    });

    ac.addEventListener("onconfirm", function(e) {
      var _value = e.item.value;
      var myValue = _value.province + _value.city + _value.district + _value.street + _value.business;
      document.getElementById("suggest_input").value = myValue;

      function setPlace() {
        map.clearOverlays(); // 清除旧标记
        function myFun() {
          var pp = local.getResults().getPoi(0).point; // 获取第一个智能搜索的结果
          map.centerAndZoom(pp, 18);
          marker = new BMap.Marker(pp);
          map.addOverlay(marker);

          // 更新数据
          updateDisplay({
            address: myValue,
            latitude: pp.lat,
            longitude: pp.lng,
            city: _value.city
          });
        }
        var local = new BMap.LocalSearch(map, { // 智能搜索
          onSearchComplete: myFun
        });
        local.search(myValue);
      }
      setPlace();
    });

    // B. 地图拖拽监听
    map.addEventListener("moveend", function() {
      var center = map.getCenter();
      marker.setPosition(center);

      var geoc = new BMap.Geocoder();
      geoc.getLocation(center, function(rs) {
        if (rs) {
          var addComp = rs.addressComponents;
          var address = rs.address;

          // 更新输入框以便用户知道当前位置
          document.getElementById("suggest_input").value = address;

          updateDisplay({
            address: address,
            latitude: center.lat,
            longitude: center.lng,
            city: addComp.city
          });
        }
      });
    });

    isMapInitialized = true;
  }

  // --- 4. 页面加载后的自动检查 ---
  // 如果是“编辑商品”且已有坐标，自动打开地图
  document.addEventListener("DOMContentLoaded", function() {
    var currentLat = parseFloat(document.getElementById('input_latitude').value);
    // 如果坐标存在且不是特殊值，说明之前存过定位
    if (!isNaN(currentLat) && currentLat != SPECIAL_LAT_LNG) {
      document.getElementById('location_toggle').checked = true;
      // 稍微延迟一点执行，确保 BMap 加载完毕
      setTimeout(function() {
        window.toggleLocationService(true);
      }, 500);
    }

    // 绑定手动修改 span 的监听 (可选)
    document.getElementById('display_address').addEventListener('input', function() {
      document.getElementById('input_address').value = this.innerText;
    });
  });
</script>

@stop