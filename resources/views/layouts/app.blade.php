<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="/favicon.ico">
  <meta name="keywords" content="校园,二手交易,商品,平台">
  <meta name="description" content="二手交易平台">
  <script>
    (function() {
      try {
        if (sessionStorage.getItem('heroPlayed')) {
          document.documentElement.classList.add('hero-played');
        }
      } catch (e) {}
    })();
  </script>

  <title>@yield('title', 'onestore') - ShopCampus</title>

 
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <link href="/js/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css" rel="stylesheet">

</head>

<body>
  <div id="app" class="{{ route_class() }}-page" style="overflow:hidden">

    @include('layouts._header')

    <!-- 首页头画 -->
    @php $isHome = Request::routeIs('home'); @endphp
    @if($isHome || Request::routeIs('category') || Request::routeIs('goods_search') || Request::routeIs('goods_hot'))
    <div class="page-header-image" style="width: 100%; height: 500px; background: linear-gradient(180deg, rgba(255,255,255,0.45), rgba(255,255,255,0.35)), url('/images/top2.jpg') no-repeat center center; background-size: cover; display: flex; align-items: center; justify-content: center; margin-bottom: 0; position: relative; overflow: hidden; margin-top: -15px; padding-top: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background-color:rgb(254, 223, 221);">
            <!-- 底部晕染效果 - 与主页背景融合 -->
            <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 200px; background: linear-gradient(to top, rgba(252, 241, 240, 0.8), rgba(252, 241, 240, 0.8), rgba(255,255,255,0)); pointer-events: none;"></div>
            <!-- 头画内容 -->
            <div style="text-align: center; z-index: 10; max-width: 800px; width: 100%; padding: 0 20px;">
                <!-- Logo -->
                <div class="hero-animate" style="margin-bottom: 20px; @if($isHome) opacity: 0; animation: fadeInUp 0.8s ease-out 0.3s forwards; @else opacity: 1; @endif">
                        <img src="@if(Auth::check()){{ Auth::user()->avatar }}@else/images/header1.png @endif" alt="User Avatar" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                    </div>
                <!-- 问候语 -->
                <div class="hero-animate" style="margin-bottom: 30px; font-size: 28px; font-weight: 700; color: #ffffff; text-shadow: 0 2px 10px rgba(192, 99, 42, 0.39); @if($isHome) opacity: 0; transform: translateY(15px); animation: fadeInUp 0.8s ease-out 0.5s forwards; @else opacity: 1; transform: none; @endif">
                    Hello! @if(Auth::check()){{ Auth::user()->name }}@else同学@endif
                </div>
                <!-- 搜索框 -->
                <form id="header_search_form" method="GET" action="{{ route('goods_search') }}" class="hero-search" style="position: relative; max-width: 600px; margin: 0 auto; width: 30%; @if($isHome) animation: searchExpand 2s ease-out 0.5s forwards; @else width: 100%; @endif">
                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                    <input type="hidden" class="btn_new_hot" name="key" value="{{ request('key') }}">
                    <input type="hidden" class="btn_new_time" name="time" value="{{ request('time') }}">
                    <input type="hidden" class="order_input" name="order" value="{{ request('order', '1') }}">
                    <input type="hidden" class="state_input" name="state" value="{{ request('state', '2') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="快来发现宝藏..." class="hero-animate" style="width: 100%; padding: 15px 20px 15px 50px; border: none; border-radius: 50px; font-size: 16px; box-shadow: 0 8px 25px rgba(0,0,0,0.2); outline: none; @if($isHome) opacity: 0; transform: translateY(10px); animation: fadeInUp 0.5s ease-out 0.7s forwards; @else opacity: 1; transform: none; @endif">
                    <button type="submit" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); border: none; background: transparent; padding: 0; cursor: pointer;">
                      <i class="fas fa-search hero-animate" style="font-size: 18px; color: #666; @if($isHome) opacity: 0; animation: fadeIn 0.5s ease-out 0.7s forwards; @else opacity: 1; @endif"></i>
                    </button>
                    <style>
                        @keyframes searchExpand {
                            0% {
                                width: 30%;
                            }
                            100% {
                                width: 100%;
                            }
                        }
                        @keyframes fadeIn {
                            0% {
                                opacity: 0;
                            }
                            100% {
                                opacity: 1;
                            }
                        }
                        @keyframes fadeInUp {
                            0% {
                                opacity: 0;
                                transform: translateY(10px);
                            }
                            100% {
                                opacity: 1;
                                transform: translateY(0);
                            }
                        }
                    </style>
                </form>
            </div>
        </div>
    @endif

    <div class="container">

      @include('shared._messages')
      @include('shared._errors')
      @yield('content')

    </div>

    @include('layouts._footer')
  </div>

  <script src="{{ mix('js/app.js') }}"></script>

  @yield('scriptsAfterJs')

  <style>
    /* 仅首页首屏播放动画，之后跳过以避免闪烁 */
    .hero-played .hero-animate {
      opacity: 1 !important;
      transform: none !important;
      animation: none !important;
    }
    .hero-played .hero-search {
      width: 100% !important;
      animation: none !important;
    }
  </style>
  <script>
    (function() {
      const played = sessionStorage.getItem('heroPlayed');
      if (played) {
        document.body.classList.add('hero-played');
      }
      window.addEventListener('load', function() {
        sessionStorage.setItem('heroPlayed', '1');
      });
    })();
  </script>

  {{--  
  @if(Auth::user() && Auth::user()->email == '1902422119@qq.com')
    @include('sudosu::user-selector')
  @endif
  --}}

    @if (app()->isLocal())
      @include('sudosu::user-selector')
    @endif
  
</body>

</html>
