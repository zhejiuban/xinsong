<div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">
    编辑用户
  </h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
  </button>
</div>
<div class="modal-body">
  <form action="{{ route('users.update',['user'=>$user->id]) }}" class="m-form m-form--fit " method="post" id="edit-data-form">
    <div class="row">
      <div class="col-lg-6">
        <div class="form-group ">
          <label>
            用户名<span class="required">*</span>:
          </label>
          <input type="text" class="form-control m-input" name="username" value="{{$user->username}}" placeholder="用户名">
          <span class="m-form__help">
                最长40个字符，可由下划线、字母、数字组成
          </span>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group ">
          <label>
            登录密码:
          </label>
          <input type="password" class="form-control m-input" name="password" value="" placeholder="登录密码">
          <span class="m-form__help">留空时不修改，最少6个字符</span>
        </div>
      </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
          <div class="form-group ">
          <label>
            姓名<span class="required">*</span>:
          </label>
          <input type="text" name="name" value="{{$user->name}}" class="form-control m-input" placeholder="姓名">
          <span class="m-form__help"></span>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group ">
          <label class="">
            性别<span class="required">*</span>:
          </label>
          <div class="m-radio-inline">
            <label class="m-radio">
              <input type="radio" name="sex" value="男" {{ $user->sex == '男' ? 'checked' : '' }}>
              男
              <span></span>
            </label>
            <label class="m-radio">
              <input type="radio" name="sex" value="女"  {{ $user->sex == '男' ? '' : 'checked' }}>
              女
              <span></span>
            </label>
          </div>
          <span class="m-form__help"></span>
          </div>
        </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <div class="form-group">
        <label>
          邮箱<span class="required">*</span>:
        </label>
        <input type="text" class="form-control m-input" name="email" value="{{$user->email}}" placeholder="邮箱">
        <span class="m-form__help"></span>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group">
        <label class="">
          手机号<span class="required">*</span>:
        </label>
        <input type="text" class="form-control m-input" name="tel" value="{{$user->tel}}" placeholder="手机号">
        <span class="m-form__help"></span>
        </div>
      </div>
    </div>
    @if (!is_administrator_user($user->id))
    <div class="row">
      <div class="col-lg-6">
        <div class="form-group">
        <label>
          所属部门<span class="required">*</span>:
        </label>
        <select name="department_id" id="department_id" class="form-control m-input select2 m-select2">
          {!! department_select($user->department_id) !!}
        </select>
        <span class="m-form__help"></span>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group">
        <label class="">
          状态<span class="required">*</span>:
        </label>
        <div class="m-radio-inline">
          <label class="m-radio">
            <input type="radio" name="status" value="1" {{ $user->status ? 'checked' : '' }}>
            启用
            <span></span>
          </label>
          <label class="m-radio">
            <input type="radio" name="status" value="0" {{ $user->status ? '' : 'checked' }}>
            禁用
            <span></span>
          </label>
        </div>
        <span class="m-form__help"></span>
        </div>
      </div>
    </div>
    @endif
    @if (!is_administrator_user($user->id) && $user->id != get_current_login_user_info())
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label>
              授权角色:
            </label>
            <div>
            <select multiple name="role_id[]" id="role_id" class="form-control m-input select2 m-select2">
              {!! role_select($user->roles->toArray(),1,'name') !!}
            </select>
            </div>
            <span class="m-form__help">请选择授权角色</span>
          </div>
        </div>
      </div>
    @endif
    {{ csrf_field() }}
    {{method_field('PUT')}}
  </form>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-dismiss="modal"  >
    关闭
  </button>
  <button type="button" class="btn btn-primary" id="edit-submit-button">
    提交
  </button>
</div>
<script type="text/javascript">
  jQuery(document).ready(function () {
    mAppExtend.init();
    var form = $( "#edit-data-form" );
    var submitButton = $("#edit-submit-button");
    form.validate({
        // define validation rules
        rules: {
            username: {
                required: true,
                minlength:4,
                maxlength:40
            },
            name: {
                required: true
            },
            sex: {
                required: true
            },
            email: {
                required: true,
                email:true
            },tel: {
                required: true,
                isMobile: true
            },status: {
                required: true
            },department_id: {
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
                      $.notify({'message':response.message},{
                          type: 'success',
                          placement: {
                              from: "top",
                              align: "center"
                          },delay:100,
                          onClose:function() {
                              $('#m_role_modal_edit').modal('hide');
                          }
                      });
                  }else{
                      $.notify({'message':response.message},{
                          type: 'danger',
                          placement: {
                              from: "top",
                              align: "center"
                          },delay:1000,
                          mouse_over:'pause'
                      });
                  }
              },
              error:function (xhr, textStatus, errorThrown) {
                  var _err_mes = '未知错误，请重试';
                  if(xhr.responseJSON != undefined){
                    _$error = xhr.responseJSON.errors;
                    if(_$error != undefined){
                        _err_mes = '';
                        $.each(_$error, function (i, v) {
                            _err_mes += v[0] + '<br>';
                        });
                    }
                  }
                  $.notify({'message':_err_mes},{
                      type: 'danger',
                      placement: {
                          from: "top",
                          align: "center"
                      },delay:1000, mouse_over:'pause'
                  });
              }
          });
        }
    });
    $("#edit-submit-button").click(function(event) {
      form.submit();
    });
  });
</script>
