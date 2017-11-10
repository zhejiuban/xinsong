<div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">
    角色编辑
  </h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
  </button>
</div>
<div class="modal-body">
  <form class="m-form" action="{{ route('groups.update',['group'=>$role->id]) }}" method="post" id="data-form-edit">
      <div class="form-group">
        <label for="name" class="form-control-label">
          名称:
        </label>
        <input type="text" name="name" value="{{ $role->name  }}" {{$role->is_system ? 'disabled':''}} class="form-control" id="name">
      </div>
      <div class="form-group">
        <label for="remark" class="form-control-label">
          描述:
        </label>
        <textarea class="form-control" name="remark" id="remark" rows="6">{{ $role->remark  }}</textarea>
      </div>
      <div class="form-group">
          <label>
              分部是否可授权:
          </label>
          <div class="m-radio-inline">
              <label class="m-radio">
                  <input type="radio" name="is_call" value="1" {{ $role->is_call ? 'checked' : '' }}>
                  是
                  <span></span>
              </label>
              <label class="m-radio">
                  <input type="radio" name="is_call" value="0" {{ $role->is_call ? '' : 'checked' }}>
                  否
                  <span></span>
              </label>
          </div>
          <span class="m-form__help">分部管理员是否可将角色权限授权于分部用户</span>
      </div>
      {{ csrf_field() }}
      {{ method_field('PUT') }}
    </form>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal"  >
    关闭
  </button>
  <button type="button" class="btn btn-primary" id="submit-button-edit">
    提交
  </button>
</div>
<script type="text/javascript">
  jQuery(document).ready(function () {
    var formEdit = $( "#data-form-edit" );
    var submitButtonEdit = $("#submit-button-edit");
    formEdit.validate({
        // define validation rules
        rules: {
            name: {
                required: true
            }
        },
        //display error alert on form submit
        invalidHandler: function(event, validator) {
        },
        submitHandler: function (forms) {
          // alert(1);
          formEdit.ajaxSubmit({
              beforeSend: function(){
                submitButtonEdit.attr('disabled', 'disabled')
                .addClass('m-loader m-loader--light m-loader--right');
              },
              complete:function(){
                submitButtonEdit.removeAttr('disabled')
                .removeClass('m-loader m-loader--light m-loader--right');
              },
              success: function(response, status, xhr, $form) {
                  if(response.status == 'success'){
                      datatable.load(); //加载数据列表
                      $.notify({'message':response.message},{
                          type: 'success',
                          placement: {
                              from: "top",
                              align: "center"
                          },delay:500,
                          onClose:function() {
                              $('#m_role_modal_edit').modal('hide');
                              // mAppExtend.backUrl(response.url);
                          }
                      });
                  }else{
                      $.notify({'message':response.message},{
                          type: 'danger',
                          placement: {
                              from: "top",
                              align: "center"
                          },delay:1000
                      });
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
                  $.notify({'message':_err_mes},{
                      type: 'danger',
                      placement: {
                          from: "top",
                          align: "center"
                      },delay:1000
                  });
              }
          });
        }
    });
    $("#submit-button-edit").click(function(event) {
      formEdit.submit();
    });
  });
</script>
