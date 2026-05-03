@extends('users.edit._edit')

@section('edit_info')

<div class="col-lg-9 col-md-8">
  <div class="card profile-card mb-4">
    <div class="card-header d-flex align-items-center" style="background: linear-gradient(120deg, #FCF1F0, #FDF1EC); border: none;">
      <i class="far fa-edit mr-2" style="font-size: 20px;color:#c0392b;"></i>
      <h5 class="mb-0" style="color:#c0392b;">修改资料</h5>
    </div>
    <div class="card-body">
      <form id="info_form" autocomplete="off" action="{{ route('user_edit_check',$user) }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="form-group">
          <label for="u_name" class="font-weight-bold text-muted">用户名 <span class="ml-1" style="color:red;font-size: 16px;">*</span></label>
          <div class="d-flex flex-wrap align-items-center">
            <input class="form-control user_name" id="u_name" maxlength="25"  value="{{ $user->name }}" required style="max-width: 420px; border-radius: 12px;">
            <div class="ml-2 text-secondary" style="line-height:35px;">建议长度 3~25</div>
            <div class="wrong_tip_name w-100"></div>
          </div>
        </div>

        <div class="form-group">
          <label for="sex" class="font-weight-bold text-muted">性别 <span class="ml-1" style="color:red;font-size: 16px;">*</span></label>
          <select class="form-control" id="sex" style="max-width: 420px; border-radius: 12px;">
            <option @if($user->sex=='男') selected @endif >男</option>
            <option @if($user->sex=='女') selected @endif>女</option>
          </select>
        </div>

        <div class="form-group">
          <label for="u_signature" class="font-weight-bold text-muted">个性签名</label>
          <div class="d-flex flex-wrap align-items-center">
            <input class="form-control" id="u_signature" maxlength="80" value="{{ $user->signature }}" required style="max-width: 420px; border-radius: 12px;">
            <div class="ml-2 text-secondary" style="line-height:35px;">建议长度不超 80</div>
          </div>
        </div>

        <div class="form-group">
          <label for="u_email" class="font-weight-bold text-muted">QQ邮箱 <span class="ml-1" style="color:red;font-size: 16px;">*</span></label>
          <div class="d-flex flex-wrap align-items-center">
            <input type="email" maxlength="32" class="form-control user_email" id="u_email" value="{{ $user->email }}" required style="max-width: 420px; border-radius: 12px;">
            <div class="ml-2 text-secondary" style="line-height:35px;">请填写有效QQ邮箱，如 1902422119@qq.com</div>
            <div class="wrong_tip_email w-100"></div>
          </div>
        </div>

        <div class="form-group">
          <label for="u_phone" class="font-weight-bold text-muted">手机号码</label>
          <div class="d-flex flex-wrap align-items-center">
            <input type="phone" class="form-control user_phone" id="u_phone" maxlength="11" value="{{ $user->phone }}" style="max-width: 420px; border-radius: 12px;">
            <div class="ml-2 text-secondary" style="line-height:35px;">手机号长度必须为 11 位</div>
            <div class="wrong_tip_phone w-100"></div>
          </div>
        </div>

        <div class="form-group">
          <label for="u_university" class="font-weight-bold text-muted">学校</label>
          <input type="text" class="form-control" id="u_university" value="{{ $user->university }}" disabled style="max-width: 420px; border-radius: 12px;">
        </div>
        <div class="form-group">
          <label for="u_faculty" class="font-weight-bold text-muted">院系</label>
          <input type="text" class="form-control" id="u_faculty" value="{{ $user->faculty }}" style="max-width: 420px; border-radius: 12px;">
        </div>
        <div class="form-group">
          <label for="u_stuID" class="font-weight-bold text-muted">学号</label>
          <input type="text" class="form-control" id="u_stuID" value="{{ $user->number }}" style="max-width: 420px; border-radius: 12px;">
        </div>
        <div class="form-group">
          <label for="u_rname" class="font-weight-bold text-muted">真实姓名</label>
          <input type="text" class="form-control" id="u_rname" value="{{ $user->r_name }}" style="max-width: 420px; border-radius: 12px;">
        </div>

        <div class="form-group mb-2">
          <button type="button" class="btn btn_edit_info" style="width:140px; color: white; font-weight:bold; border:none; border-radius:12px; background:linear-gradient(120deg,#E23729,#EC5E29);border:none;border-radius:12px;box-shadow:0 10px 24px rgba(226,55,41,0.22);">
            确认修改
          </button>
        </div>
      </form>
    </div>

  </div>
</div>
@stop

@section('scriptsAfterJs')
@parent
<script>
  $(document).ready(function() {
    // 用户名验证
    var current_name=$(".user_name").val();
    $(".user_name").blur(function(){
      if($(this).val()==''){
        $(this).addClass('is-invalid').removeClass('is-valid');
        $(".wrong_tip_name").addClass('invalid-feedback').removeClass('valid-feedback');
        $(".wrong_tip_name").html('<span>请填写用户名！</span>');
      }
      if($(this).val().length<3 && $(this).val().length>0){
        $(this).addClass('is-invalid').removeClass('is-valid');
        $(".wrong_tip_name").addClass('invalid-feedback').removeClass('valid-feedback');
        $(".wrong_tip_name").html('<span>用户名长度不能小于3！</span>');
      }
      if($(this).val().length>=3 && $(this).val().length<=25){
        if($(".user_name").val() == current_name ){
          $(".user_name").removeClass('is-valid is-invalid');
        }else{
          axios.get('/ajax_name/' + $(this).val())
            .then(function(res) {
              if(JSON.stringify(res.data)=='false'){
                $(".user_name").addClass('is-invalid').removeClass('is-valid');
                $(".wrong_tip_name").addClass('invalid-feedback').removeClass('valid-feedback');
                $(".wrong_tip_name").html('<span>该用户名已被注册，请重新填写！</span>');

              }else if(JSON.stringify(res.data)=='true'){
                $(".user_name").addClass('is-valid').removeClass('is-invalid');
                $(".wrong_tip_name").addClass('valid-feedback').removeClass('invalid-feedback');
                $(".wrong_tip_name").html('');
              }
            })
        }
      }
    });

    // 邮箱验证
    var current_email= $(".user_email").val();
    $(".user_email").blur(function(){
      var email = $(".user_email").val();
      var reg=/^[a-zA-Z\d]{8,}@qq.com$/;
      if(reg.test(email)){      // 邮箱格式正确
        if(email==current_email){
          $(".user_email").removeClass('is-valid is-invalid');
        }else{
          axios.get('/ajax_email/'+ email)
          .then(function(res){
            if(JSON.stringify(res.data)=='false'){
              $(".user_email").addClass('is-invalid').removeClass('is-valid');
              $(".wrong_tip_email").addClass('invalid-feedback').removeClass('valid-feedback');
              $(".wrong_tip_email").html('<span>该邮箱已被注册，请重新填写！!</span>');
            }else if(JSON.stringify(res.data)=='true'){
              $(".user_email").addClass('is-valid').removeClass('is-invalid');
              $(".wrong_tip_email").addClass('valid-feedback').removeClass('invalid-feedback');
              $(".wrong_tip_email").html('');
            }
          })
        }
      }else{
          $(this).addClass('is-invalid').removeClass('is-valid');
          $(".wrong_tip_email").addClass('invalid-feedback').removeClass('valid-feedback');
        if(email==''){
          $(".wrong_tip_email").html('<span>请填写您的邮箱！</span>');
        }else{
          $(".wrong_tip_email").html('<span>您的邮箱格式不正确，请重新填写！</span>');
        }
      }
    })

    // 手机号验证
    var current_phone= $(".user_phone").val();
    $('.user_phone').blur(function(){
      if(current_phone == $(".user_phone").val()){
        $('.user_phone').removeClass('is-valid').removeClass('is-invalid');
        $(".wrong_tip_phone").removeClass('valid-feedback').removeClass('invalid-feedback');
        $(".wrong_tip_phone").html('');
      }else{
        if( $('.user_phone').val().length>0 && $('.user_phone').val().length<11){
          
          $('.user_phone').addClass('is-invalid').removeClass('is-valid');
          $(".wrong_tip_phone").addClass('invalid-feedback').removeClass('valid-feedback');
          $(".wrong_tip_phone").html('<span>手机号码长度必须为11位！！</span>');
        }else if($('.user_phone').val().length==11){
          
          $('.user_phone').addClass('is-valid').removeClass('is-invalid');
          $(".wrong_tip_phone").addClass('valid-feedback').removeClass('invalid-feedback');
          $(".wrong_tip_phone").html('');
          
        }else if($('.user_phone').val().length==0){
          
          $('.user_phone').removeClass('is-valid').removeClass('is-invalid');
          $(".wrong_tip_phone").removeClass('valid-feedback').removeClass('invalid-feedback');
          $(".wrong_tip_phone").html('');
        }
      }

    })

    // 确认修改-
    $('.btn_edit_info').click(function() {

      // 获取用户信息
      var user_data={
        name: $('.user_name').val(),
        sex: $('#sex option:selected').val(),
        email: $('.user_email').val(),
        signature:$('#u_signature').val(),
        phone:$('#u_phone').val(),
        university:$('#u_university').val(),
        faculty:$('#u_faculty').val(),
        number:$('#u_stuID').val(),
        r_name:$('#u_rname').val(),
        email_cg:'',    // -表示邮箱是否修改
      }
      if($('.is-invalid').length!=0){   // 表示还有错误选项，无法提交修改
        swal({
          text: '有错误选项，无法提交！',
          icon: 'error'
        })
      }else{                            // 可以提交信息表单
        if(current_email != $(".user_email").val()){        // 用户修改了邮箱
          user_data.email_cg='true';      // 修改了邮箱
          swal({
            title: '你确定吗?',
            text: "修改邮箱后需要重新登录进行邮箱验证！",
            icon: 'warning',
            buttons: ['取消', '确定'],
            dangerMode: true,
          }).then((res) => {
            if (!res) {
              return;
            }
            axios.post( '{{ route('user_edit_check', ['user'=> $user->id]) }}', user_data)
            .then(function(res){ // 请求成功执行此函数
              swal({
                title:'修改成功',
                icon:'success',
                closeOnClickOutside: false
              }).then(function(url){
                  if(url){
                    location.href = '{{ route('login') }}';
                  }
              });
              $('.swal-button').text('重新登录');   // swal按钮样式-文字

            },function(error){   // 请求失败
              $(".is-valid").removeClass('is-valid');   // 所有成功样式清空
              var html = '<div>';
              if(error.response.status === 429){    // 429 频率限制
                html += '提交频率过高，休息1分钟再试试吧~'+'<br>';
              }
              if(error.response.status === 422){
                _.each(error.response.data.errors, function (errors) {
                  _.each(errors, function (error) {
                    html += error+'<br>';
                  })
                });
              }
              html += '</div>';
              swal({
                content: $(html)[0], 
                icon: 'error',
                closeOnClickOutside: false
              }).then((res)=>{
                window.location.reload()
                
              })
            })
          });
          $('.swal-text').addClass('warning_text'); // 控制swal-text的样式
          $('.swal-footer').css("text-align","center")  // 确认取消按钮-居中

        }else{          // 用户没有修改邮箱
          user_data.email_cg='false';
          axios.post( '{{ route('user_edit_check', ['user'=> $user->id]) }}', user_data)
          .then(function(res){
            swal('修改成功', '', 'success').then(function(){
                location.reload();
            });
          },function(error){
            $(".is-valid").removeClass('is-valid');   // 所有成功样式清空
              var html = '<div>';
              if(error.response.status === 429){    // 429 频率限制
                html += '您提交频率过高，休息1分钟再试试吧~'+'<br>';
              }
              if(error.response.status === 422){
                _.each(error.response.data.errors, function (errors) {
                  _.each(errors, function (error) {
                    html += error+'<br>';
                  })
                });
              }
              html += '</div>';
              swal({
                content: $(html)[0], 
                icon: 'error',
                closeOnClickOutside: false
              }).then((res)=>{
                window.location.reload()
                
              })
          })
        }
      }

    })

  })
</script>
@endsection
