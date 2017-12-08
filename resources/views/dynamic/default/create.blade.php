<div class="modal-header">
  <h5 class="modal-title" id="_ModalLabel">
    发布动态
  </h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
  </button>
</div>
<div class="modal-body">
  <form class="m-form" action="{{ route('dynamics.store') }}" method="post" id="task-form">
      <div class="form-group">
          <label for="content" class="form-control-label">
              现场人员:<span class="required">*</span>
          </label>
          <input class="form-control m-input" name="onsite_user" id="onsite_user" />
          <span class="m-form__help">第三方配合人员请标注</span>
      </div>
      <div class="form-group">
        <label for="content" class="form-control-label">
          内容:<span class="required">*</span>
        </label>
        <textarea class="form-control" name="content" id="content" rows="6"></textarea>
        <span class="m-form__help"></span>
      </div>
      <input type="hidden" name="project_id" value="{{$project_id}}">
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
    mAppExtend.datePickerInstance(); 
    mAppExtend.select2Instance();
    var form = $( "#task-form" );
    var submitButton = $("#submit-button");
    form.validate({
        // define validation rules
        rules: {
            onsite_user: {
                required: true
            },
            content: {
                required: true
            }
        },
        messages:{

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
                        mAppExtend.notification(response.message,'success','toastr',function() {
                            mAppExtend.backUrl(response.url);
                        });
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
