<div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">
    新增考核细则
  </h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
  </button>
</div>
<div class="modal-body">
  <form class="m-form" action="{{ route('rules.store') }}" method="post" id="data-form">
      <div class="form-group">
        <label for="name" class="form-control-label">
          考核项目:
        </label>
        <input type="text" name="name" class="form-control" id="name">
      </div>
      <div class="form-group">
        <label for="content" class="form-control-label">
          考核内容:
        </label>
        <textarea name="content" class="form-control" id="content" id="" ></textarea>
      </div>
      <div class="form-group">
        <label for="score" class="form-control-label">
          分值:
        </label>
        <input type="number" name="score" class="form-control" id="score">
      </div>
      <div class="form-group">
        <label for="remark" class="form-control-label">
          备注:
        </label>
        <input type="text" name="remark" class="form-control" id="remark">
      </div>
      <div class="form-group">
          <label>
              是否可用:
          </label>
          <div class="m-radio-inline">
              <label class="m-radio">
                  <input type="radio" name="status" value="1" checked>
                  是
                  <span></span>
              </label>
              <label class="m-radio">
                  <input type="radio" name="status" value="0" >
                  否
                  <span></span>
              </label>
          </div>
          <span class="m-form__help"></span>
      </div>
      {{ csrf_field() }}
    </form>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal"  >
    关闭
  </button>
  <button type="button" class="btn btn-primary" id="submit-button">
    提交
  </button>
</div>
<script type="text/javascript">
  jQuery(document).ready(function () {
    var form = $( "#data-form" );
    var submitButton = $("#submit-button");
    form.validate({
        // define validation rules
        rules: {
            name: {
                required: true
            },content: {
                required: true
            },score: {
                required: true
            },status: {
                required: true
            }
        },
        //display error alert on form submit
        invalidHandler: function(event, validator) {
        },
        submitHandler: function (forms) {
          // alert(1);
          form.ajaxSubmit({
              beforeSend: function(){
                submitButton.attr('disabled', 'disabled')
                .addClass('m-loader m-loader--light m-loader--right');
              },
              complete:function(){
                submitButton.removeAttr('disabled')
                .removeClass('m-loader m-loader--light m-loader--right');
              },
              success: function(response, status, xhr, $form) {

               if(response.status == 'success'){
                  datatable.load(); //加载数据列表  
                  $('#m_role_modal').modal('hide');
                  mAppExtend.notification(response.message,'success','toastr');
                }else{
                  mAppExtend.notification(response.message,'error');
                }

              },
              error:function (xhr, textStatus, errorThrown) {
                _$error = xhr.responseJSON.errors;
                var _err_mes = '未知错误，请重试';
                if(_$error != undefined){
                    _err_mes = '';
                    $.each(_$error, function (i, v) {
                        _err_mes += v[0] + '<br>';
                    });
                }
                mAppExtend.notification(_err_mes,'error');
              }
          });
        }
    });
    $("#submit-button").click(function(event) {
      form.submit();
    });
  });
</script>
