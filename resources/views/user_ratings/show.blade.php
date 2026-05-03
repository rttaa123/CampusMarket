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
            <div class="rating-stars">
              <input type="radio" name="score" id="star5" value="5" required>
              <label for="star5" class="star-label">★</label>
              <input type="radio" name="score" id="star4" value="4" required>
              <label for="star4" class="star-label">★</label>
              <input type="radio" name="score" id="star3" value="3" required>
              <label for="star3" class="star-label">★</label>
              <input type="radio" name="score" id="star2" value="2" required>
              <label for="star2" class="star-label">★</label>
              <input type="radio" name="score" id="star1" value="1" required>
              <label for="star1" class="star-label">★</label>
              <input type="radio" name="score" id="star0" value="0" required>
              <label for="star0" class="star-label">★</label>
              <span id="scoreText" class="ml-2" style="color: #636b6f;">请选择评分</span>
            </div>
            <small class="form-text text-muted">评分范围：0-5分</small>
          </div>

          <div class="form-group">
            <label for="comment">评价内容（选填）</label>
            <textarea class="form-control" id="comment" name="comment" rows="3" maxlength="500" placeholder="请输入评价内容..."></textarea>
            <small class="form-text text-muted">最多500字</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button type="button" class="btn btn-primary" id="submitRating">提交评价</button>
      </div>
    </div>
  </div>
</div>

<style>
.rating-stars {
  display: flex;
  flex-direction: row-reverse;
  justify-content: flex-end;
  gap: 5px;
}
.rating-stars input[type="radio"] {
  display: none;
}
.star-label {
  font-size: 30px;
  color: #ddd;
  cursor: pointer;
  transition: color 0.2s;
}
.rating-stars input[type="radio"]:checked ~ .star-label,
.rating-stars input[type="radio"]:checked ~ .star-label ~ .star-label,
.star-label:hover,
.star-label:hover ~ .star-label {
  color: #ffc107;
}
</style>

<script>
$(document).ready(function() {
  // 点击评价按钮 - 使用事件委托，因为按钮可能是动态加载的
  $(document).on('click', '.btn_rating', function() {
    var orderId = $(this).data('order-id');
    var ratedId = $(this).data('rated-id');
    
    $('#order_id').val(orderId);
    $('#rated_id').val(ratedId);
    $('#ratingForm')[0].reset();
    $('#scoreText').text('请选择评分');
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
    else if (score == 0) scoreText = '0分 - 极差';
    $('#scoreText').text(scoreText);
  });

  // 提交评分
  $('#submitRating').click(function() {
    var formData = {
      score: $('input[name="score"]:checked').val(),
      comment: $('#comment').val(),
    };

    if (!formData.score && formData.score !== '0') {
      swal({
        text: '请选择评分',
        icon: 'warning'
      });
      return;
    }

    var scoreNum = parseFloat(formData.score);
    if (scoreNum < 0 || scoreNum > 5) {
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
});
</script>

