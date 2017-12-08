<div class="modal-header">
  <h5 class="modal-title" id="_ModalLabel">
    发布任务
  </h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
  </button>
</div>
<div class="modal-body">
  <form class="m-form" action="{{ route('tasks.store') }}" method="post" id="task-form">
      <div class="form-group">
          <label>
              建设阶段:
          </label>

          <div class="">
              <select class="form-control m-input select2" id="project_phase_id" name="project_phase_id" >
                  {!!project_phase_select($project_id)!!}
              </select>
          </div>
          <span class="m-form__help"></span>
      </div>
      <div class="form-group">
        <label for="name" class="form-control-label">
          起止日期:
        </label>
        <div class="input-daterange input-group m-date">
            <input type="text" class="form-control m-input" placeholder="开始日期" name="start_at" />
            <span class="input-group-addon">
                <i class="la la-ellipsis-h"></i>
            </span>
            <input type="text" class="form-control" placeholder="截止日期" name="end_at" />
        </div>
        <span class="m-form__help"></span>
      </div>
      <div class="form-group">
          <label>
            分派给:
          </label>
          <div class="">
              <select class="form-control m-input select2" id="leader" name="leader">
                {!!project_user_select($project_id)!!}
              </select>
          </div>
          <span class="m-form__help">可从项目参与人中选择处理人</span>
      </div>
      <div class="form-group">
        <label for="content" class="form-control-label">
          任务内容:
        </label>
        <textarea class="form-control" name="content" id="content" rows="6"></textarea>
        <span class="m-form__help"></span>
      </div>
      <div class="form-group">
          <label>
              是否需要上传计划:
          </label>
          <div class="m-radio-inline">
              <label class="m-radio">
                  <input type="radio" name="is_need_plan" value="1" checked>
                  是
                  <span></span>
              </label>
              <label class="m-radio">
                  <input type="radio" name="is_need_plan" value="0" >
                  否
                  <span></span>
              </label>
          </div>
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
            start_at: {
                required: true
            },
            end_at: {
                required: true
            },
            content: {
                required: true
            },
            is_need_plan: {
                required: true
            },
            leader: {
                required: true
            },
            project_phase_id: {
                required: true
            }
        },
        messages:{
            start_at:{
                required:'请选择开始时间'
            },end_at:{
                required:'请选择截止时间'
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
