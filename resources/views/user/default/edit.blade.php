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
      <div class="col-lg-12">
        <div class="form-group ">
          <div>
            <div class="m-card-user">
                    <div class="m-card-user__pic">
                        <img src="{{avatar($user->avatar)}}" class="m--img-rounded m--marginless" alt="">
                    </div>
                </div>
                <div class="">
                    <div id="forms-avatar-upload" class="btn-upload">
                        <div id="forms-avatar-upload-picker" class="pickers tooltips forms-avatar-upload-tooltips"
                             data-toggle="tooltip" data-placement="bottom" data-skin="dark"
                             data-original-title="最佳尺寸：96*96">
                            <i class="fa fa-upload"></i> 修改头像</div>
                        <div id="forms-avatar-upload-file-list"></div>
                    </div>
                </div>
                <input type="hidden" name="avatar" value="{{$user->avatar}}">
          </div>
        </div>
      </div>
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
      @if($user->id != get_current_login_user_info())
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
      @endif
    </div>
    @endif
      <div class="row">
        @if (!is_administrator_user($user->id) && $user->id != get_current_login_user_info())
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
        @endif
        <div class="col-lg-6">
          <div class="form-group">
            <label class="">
              是否参与考核<span class="required">*</span>:
            </label>
            <div class="m-radio-inline">
              <label class="m-radio">
                <input type="radio" name="is_assessment" value="1" {{ $user->is_assessment ? 'checked' : '' }}>
                是
                <span></span>
              </label>
              <label class="m-radio">
                <input type="radio" name="is_assessment" value="0" {{ $user->is_assessment ? '' : 'checked' }}>
                否
                <span></span>
              </label>
            </div>
            <span class="m-form__help"></span>
          </div>
        </div>
      </div>
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
    mAppExtend.handleInitTooltips(".forms-avatar-upload-tooltips");
    mAppExtend.singleImageUpload({
        uploader: 'avatarUploadForm',
        picker: 'forms-avatar-upload',
        swf: '{{ asset("assets/js/plugins/webuploader/Uploader.swf") }}',
        server: '{{ route("image.upload") }}',
        formData: {
            '_token': '{{ csrf_token() }}',
            'avatar_upload':1
        },
        errorMsgHiddenTime: 1000,
        isAutoInsertInput: false,//上传成功是否自动创建input存储区域
        isHiddenResult: true,
        uploadComplete: function (file) {
        },
        uploadError: function (file) {
        },
        uploadSuccess: function (file, response) {
            //上传完成触发时间
            $img = $('.m-card-user__pic').find('img');
            if (!$img.length) {
                $img = $('.m-card-user__pic').html('<img src="' + response.data.url + '" class="m--img-rounded m--marginless">');
            } else {
                $img.attr({'src': response.data.url});
                $('.m-topbar__userpic').find('img').attr({'src': response.data.url});
            }
            $('input[name="avatar"]').val(response.data.path);
            window.setTimeout(function () {
                $('#' + file.id).remove();
            }, 1000);
        }
    });
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
                  if (response.status == 'success') {
                      mAppExtend.notification(response.message
                          ,'success','toastr',function() {
                              $('#m_role_modal_edit').modal('hide');
                              datatable.reload();
                          });
                  } else {
                      mAppExtend.notification(response.message
                          ,'error');
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
    $("#edit-submit-button").click(function(event) {
      form.submit();
    });
  });
</script>
