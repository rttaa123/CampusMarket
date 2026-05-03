<!-- 消息提示 -->

@foreach (['danger', 'warning', 'success', 'info'] as $msg)
  @if(session()->has($msg))
  <div class="flash-message" style="margin:0 auto">
    <p class="alert alert-{{ $msg }} flash-alert flash-alert-{{ $msg }}">
      {{ session()->get($msg) }}
    </p>
  </div>
  @endif
@endforeach

<style>
  .flash-alert {
    border: 1px solid #d97706;
    background: #fff4e6;
    color: #7c3f00;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(217, 119, 6, 0.12);
  }
  .flash-alert-success,
  .flash-alert-info,
  .flash-alert-warning,
  .flash-alert-danger {
    background: #fff4e6 !important;
    border-color: #d97706 !important;
    color: #7c3f00 !important;
  }
</style>
