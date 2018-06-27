<div class="modal-header">
  <h5 class="modal-title" id="exampleModalLabel">
    新增考核
  </h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
  </button>
</div>
<div class="modal-body">
  <form class="m-form" action="{{ route('assessments.store') }}" method="post" id="data-form">
      <div class="form-group">
        <label for="name" class="form-control-label">
          考核人员:
        </label>
        <select name="user_id" class="form-control" id="user_id" data-error-container="#user_id-error"></select>
        <span id="user_id-error"></span>
      </div>
      <div class="form-group">
        <label for="score" class="form-control-label">
          考核细则:
        </label>
        <select class="form-control select2" name="assessment_rule_id" id="assessment_rule_id" data-error-container="#assessment_rule_id-error">
          {!! assessment_rule_select() !!}
        </select>
        <span id="assessment_rule_id-error"></span>
      </div>
      <div class="form-group">
        <label for="remark" class="form-control-label">
          备注:
        </label>
        <input type="text" name="remark" class="form-control" id="remark">
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
  
  function formatRepos(repo) {
      if (repo.loading) return repo.text;
      var markup = "<div class='select2-result-repository clearfix'>" +
          "<div class='select2-result-repository__meta'>" +
          "<div class='select2-result-repository__title'>" + repo.name + "</div>";
      if (repo.department) {
          markup += "<div class='select2-result-repository__description'>所在部门：" + repo.department.name + "</div>";
      }
      markup += "</div></div>";
      return markup;
  }

  function formatReposSelection(repo) {
      return repo.name || repo.text;
  }

  jQuery(document).ready(function () {
    mAppExtend.select2Instance("#assessment_rule_id");
    $("#user_id").select2({
      language: 'zh-CN',
      placeholder: "输入姓名、用户名等关键字搜索，选择用户",
      allowClear: true,
      width: '100%',
      ajax: {
          url: "{{route('users.selector.data')}}",
          dataType: 'json',
          delay: 250,
          data: function (params) {
              return {
                  q: params.term, // search term
                  page: params.page,
                  per_page: {{config('common.page.per_page')}}
              };
          },
          processResults: function (data, params) {
              params.page = params.page || 1;
              return {
                  results: data.data,
                  pagination: {
                      more: (params.page * data.per_page) < data.total
                  }
              };
          },
          cache: true
      },
      escapeMarkup: function (markup) {
          return markup;
      }, // let our custom formatter work
      minimumInputLength: 0,
      templateResult: formatRepos, // omitted for brevity, see the source of this page
      templateSelection: formatReposSelection // omitted for brevity, see the source of this page
  });
    var form = $( "#data-form" );
    var submitButton = $("#submit-button");
    form.validate({
        // define validation rules
        rules: {
            user_id: {
                required: true
            },assessment_rule_id: {
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
