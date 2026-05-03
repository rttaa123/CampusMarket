<style>
  .nav-soft {
    background: linear-gradient(120deg,rgb(255, 255, 255) 0%,rgb(243, 131, 100) 100%,rgb(236, 23, 4) 100%);
    box-shadow: 0 10px 28px rgba(255, 138, 128, 0.22);
    border: none;
    height: 80px;
    min-height: 80px;
    display: flex;
    align-items: center;
    z-index: 100;
    margin-bottom: 0;
    padding-bottom: 0;
  }
  /* 移除导航栏容器的默认margin */
  .nav-soft .container {
    margin-bottom: 0;
    padding-bottom: 0;
  }
  /* 确保导航栏和头画之间没有任何缝隙 */
  nav.navbar {
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
    border-bottom: none;
  }
  /* 移除body的默认margin-top */
  body {
    margin-top: 0 !important;
  }
  .nav-soft .nav-link,
  .nav-soft .navbar-brand {
    font-weight: 600;
    color: #5a2d2b !important;
    text-shadow: 0 1px 0 rgba(255, 255, 255, 0.22);
    z-index: 101;
  }
  .nav-soft .nav-link:hover {
    color:rgb(226, 58, 46) !important;
  }
  .nav-emoji {
    margin-right: 4px;
    font-size: 14px;
  }
  .nav-pill {
    border-radius: 12px;
    padding: 8px 12px;
    transition: all .2s ease;
    background: transparent;
  }
  .nav-pill.active,
  .nav-pill:hover {
    background: rgba(255, 255, 255, 0.7);
    color: #5a2d2b !important;
  }
  .nav-btn {
    border-radius: 12px;
    padding: 10px 12px;
    font-weight: 700;
    box-shadow: 0 8px 18px rgba(255, 138, 128, 0.25);
    background: rgba(255, 255, 255, 0.85) !important;
    color:rgb(214, 93, 87) !important;
    border: none;
  }
  /* 统一页面背景，减弱顶部/内容白色断层 */
  body {
    background: linear-gradient(180deg, #fff7f3 0%, #ffe7df 45%, #fff1ec 100%);
  }
</style>

<nav class="navbar navbar-expand-lg navbar-light nav-soft navbar-static-top">
  <div class="container">

    @php
      $categoryIcons = [
        '全部' => '🧭',
        '学习' => '📚',
        '生活' => '🏠',
        '娱乐' => '🎮',
        '食物' => '🍔',
        '跑腿' => '🚴',
        '租借' => '📦',
        '心愿单' => '💫',
        '其他' => '✨',
      ];
    @endphp

    <!-- 校徽 -->
    <img class="mr-2" src="/images/header.png" alt="" style="width: 45px; height:45px; border-radius: 50%;">
    <a class="navbar-brand" href="{{ route('home') }}">
    CampusMarket
    </a>
    <ul class="navbar-nav mr-auto link_category">
      <li class="nav-item {{ active_class(if_route('home')) }} {{ search_no_category_active() }}"><a class="nav-link nav-pill" href="{{ route('home') }}"><span class="nav-emoji">{{ $categoryIcons['全部'] ?? '🧭' }}</span>全部</a></li>
      @php
        $otherCategory = null;
      @endphp
      @foreach(($navCategories ?? []) as $category)
        @if($category->name === '其他')
          @php $otherCategory = $category; @endphp
          @continue
        @endif
        <li class="nav-item {{ category_active($category->id) }}">
          @php $icon = $categoryIcons[$category->name] ?? '📦'; @endphp
          <a class="nav-link nav-pill" href="{{ route('category', $category->id) }}"><span class="nav-emoji">{{ $icon }}</span>{{ $category->name }}</a>
        </li>
      @endforeach
      @if($otherCategory)
        <li class="nav-item {{ category_active($otherCategory->id) }}">
          <a class="nav-link nav-pill" href="{{ route('category', $otherCategory->id) }}"><span class="nav-emoji">{{ $categoryIcons['其他'] ?? '✨' }}</span>{{ $otherCategory->name }}</a>
        </li>
      @endif
    </ul>

    <div class="collapse navbar-collapse" id="navbarSupportedContent" >

      <ul class="navbar-nav mr-auto">
      </ul>

      <!-- 右导�?-->
      <ul class="navbar-nav navbar-right col-">

        @guest
        <li class="nav-item"><a class="nav-link nav-pill" href="{{ route('login') }}">登录</a></li>
        <li class="nav-item"><a class="nav-link nav-pill" href="{{ route('signup') }}">注册</a></li>

        @else
        <li class="nav-item " >
          <a class="nav-link btn btn-light nav-btn mt-2 mr-3 font-weight-bold" href="{{ route('create_goods') }}" style="width: 55px; height:40px;line-height:20px">
            <i class="fas fa-paper-plane" style="font-size:18px; line-height:20px"></i>
          </a>
        </li>

        <!-- 消息 -->
        <li class="nav-item notification-badge mt-2 " style="display: flex;align-items:center; width:55px;height:40px">
          <a id="notification" class="nav-link mr-3 badge badge-pill badge-{{ Auth::user()->notification_count > 0 ? 'hint' : 'secondary' }} text-white" href="{{ route('notifications') }}" style="height:22px;margin:0 auto" >
            <span class="" style="line-height:15px">{{ Auth::user()->notification_count }}</span>
          </a>
        </li>

       <li class="nav-item mt-2 mr-3" id="private-message-link-container" style="display: flex; align-items: center; width:55px; height:40px; position: relative;">
          <a class="nav-link nav-pill" href="{{ route('messages.index') }}" style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; padding: 0;">
            <i class="far fa-comment-dots" style="font-size:18px; color: #FFFFFF; line-height: 1;"></i> {{-- Icon color changed to white and added line-height for better centering --}}
            {{-- New message badge, hidden by default --}}
            <span id="unread-messages-badge" class="badge badge-danger" style="display: none;"></span>
          </a>
        </li>



        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle nav-pill" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="{{ Auth::user()->avatar }}" class="img-responsive img-circle" width="40px" height="40px" style="border-radius: 50%;">
            <span class="ml-2"> {{ Auth::user()->name }}</span>
          </a>
          <div class="dropdown-menu mt-2" aria-labelledby="navbarDropdown">

            <a class="dropdown-item" style="height: 45px; line-height:40px" href="{{ route('user_show', Auth::user()) }}">
              <i class="fas fa-user mr-2"></i>
              个人中心
            </a>

            <a class="dropdown-item" href="{{ route('user_edit', Auth::user()) }}" style="height: 45px; line-height:40px">
              <i class="fas fa-cogs mr-2"></i>
              编辑资料
            </a>

            <div class="dropdown-divider"></div>
            <a class="dropdown-item" id="logout" href="#">
              <form action="{{ route('login_out') }}" method="POST">
                {{ csrf_field() }}
                <button class="btn btn-block btn-danger" type="submit" name="button">退出</button>
              </form>
            </a>
          </div>
        </li>
        @endguest
      </ul>


    </div>

  </div>
</nav>
