<div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">
    新增版块
  </h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
  </button>
</div>
<div class="modal-body">
  <form class="m-form" action="{{ route('categories.store') }}" method="post" id="data-form">
      <div class="form-group">
        <label for="name" class="form-control-label">
          名称:
        </label>
        <input type="text" name="name" class="form-control" id="name">
      </div>
      <div class="form-group">
        <label for="remark" class="form-control-label">
          描述:
        </label>
        <textarea class="form-control" name="remark" id="remark" rows="6"></textarea>
      </div>
      <div class="form-group">
          <label>
              是否启用:
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
                          },delay:500,
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
    $("#submit-button").click(function(event) {
      form.submit();
    });
  });
</script>
