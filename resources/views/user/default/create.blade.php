<div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">
    新增用户
  </h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
  </button>
</div>
<div class="modal-body">
  <form action="{{ route('users.store') }}" class="m-form m-form--fit " method="post" id="data-form">
    <div class="row">
      <div class="col-lg-6">
        <div class="form-group ">
          <label>
            用户名<span class="required">*</span>:
          </label>
          <input type="text" class="form-control m-input" name="username" value="" placeholder="用户名">
          <span class="m-form__help">
                最长40个字符，可由下划线、字母、数字组成
          </span>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group ">
          <label>
            登录密码<span class="required">*</span>:
          </label>
          <input type="password" class="form-control m-input" name="password" value="" placeholder="登录密码">
          <span class="m-form__help">最少6个字符</span>
        </div>
      </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
          <div class="form-group ">
          <label>
            姓名<span class="required">*</span>:
          </label>
          <input type="text" name="name" class="form-control m-input" placeholder="姓名">
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
              <input type="radio" name="sex" value="男" checked>
              男
              <span></span>
            </label>
            <label class="m-radio">
              <input type="radio" name="sex" value="女">
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
        <input type="text" class="form-control m-input" name="email" value="" placeholder="邮箱">
        <span class="m-form__help"></span>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="form-group">
        <label class="">
          手机号<span class="required">*</span>:
        </label>
        <input type="text" class="form-control m-input" name="tel" value="" placeholder="手机号">
        <span class="m-form__help"></span>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-6">
        <div class="form-group">
        <label>
          所属部门<span class="required">*</span>:
        </label>
        <select name="department_id" id="department_id" class="form-control m-input select2 m-select2">
          {!! department_select() !!}
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
            <input type="radio" name="status" value="1" checked>
            启用
            <span></span>
          </label>
          <label class="m-radio">
            <input type="radio" name="status" value="0">
            禁用
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
            授权角色:
          </label>
          <div>
          <select multiple name="role_id[]" id="role_id" class="form-control m-input select2 m-select2">
            {!! role_select('',1,'name') !!}
          </select>
          </div>
          <span class="m-form__help">请选择授权角色</span>
        </div>
      </div>
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
    mAppExtend.init();
    var form = $( "#data-form" );
    var submitButton = $("#submit-button");
    form.validate({
        // define validation rules
        rules: {
            username: {
                required: true,
                minlength:4,
                maxlength:40
            },
            password: {
                required: true,
                minlength:6
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
                              $('#m_role_modal').modal('hide');
                              // mAppExtend.backUrl(response.url);
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
                      },delay:1000, mouse_over:'pause'
                  });
              }
          });
        }
    });
    $("#submit-button").click(function(event) {
      form.submit();
    });
  });
</script>
