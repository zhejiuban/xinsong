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
				所属项目:
			</label>
			<div class="">
				<select class="form-control m-input select2"  id="project_select" name="project_id">
					@if(request('project_id'))
						<option value="{{request('project_id')}}"
								selected>{{get_project_info(request('project_id'))}}</option>
					@endif
				</select>
			</div>
			<span class="m-form__help"></span>
		</div>

		<div class="form-group">
			<label for="name" class="form-control-label">
				开始日期:
			</label>
            <input type="text" class="form-control m-input m-date" placeholder="开始日期" name="start_at" />
			<span class="m-form__help"></span>
		</div>
		<div class="form-group">
			<label>
				分派给:
			</label>
			<div class="">
				<select class="form-control m-input select2" multiple id="leader" name="leader[]">
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
					<input type="radio" name="is_need_plan" value="1" > 是
					<span></span>
				</label>
				<label class="m-radio">
					<input type="radio" name="is_need_plan" value="0" checked> 否
					<span></span>
				</label>
			</div>
			<span class="m-form__help"></span>
		</div>
		{{ csrf_field() }}
	</form>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-secondary" data-dismiss="modal">
		关闭
	</button>
	<button type="button" class="btn btn-primary" id="submit-button">
		提交
	</button>
</div>
<script type="text/javascript">
    function formatProjectRepo(repo) {
        if (repo.loading) return repo.text;
        var markup = "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__meta'>" +
            "<div class='select2-result-repository__title'>" + repo.title + "</div>";
        markup += "<div class='select2-result-repository__description'>项目编号：" + repo.no + "</div>";
        markup += "</div></div>";
        return markup;
    }

    function formatProjectRepoSelection(repo) {
        return repo.title || repo.text;
    }
	jQuery(document).ready(function () {
    mAppExtend.datePickerInstance();
	$('#leader').select2({
		language:'zh-CN',
		width: '100%',
        placeholder:'请选择接收人'
	});
	var $projectSelector = $("#project_select").select2({
		language: 'zh-CN',
		placeholder: "输入项目编号、名称等关键字搜索，选择项目",
		allowClear: true,
		width: '100%',
		ajax: {
			url: "{{route('projects.selector.data')}}",
			dataType: 'json',
			delay: 250,
			data: function (params) {
				return {
					q: params.term, // search term
					page: params.page,
					status:1,
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
		templateResult: formatProjectRepo, // omitted for brevity, see the source of this page
		templateSelection: formatProjectRepoSelection // omitted for brevity, see the source of this page
	});
	$("#project_select").on("select2:select", function (e) {
		var project_id = e.params.data.id;
        $('#leader').empty();
		mAppExtend.ajaxPostSubmit({
			'type': 'get',
			'showLoading':false,
			url:"{{route('project.users.selector')}}",
			query:{'id':project_id},
            callback:function (data, textStatus, xhr) {
				var $user = data.data;
                if($user){
                    $.each($user, function (i, item) {
                        $('#leader').append("<option value='" + item.id + "'>" + item.name + "</option>");
                    });
				}
            }
		})
	});
    var form = $( "#task-form" );
    var submitButton = $("#submit-button");
    form.validate({
        // define validation rules
        rules: {
            project_id: {
                required: true
            },
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
                        var $board = "{{request('board')}}";
                        if($board == '1'){
                            $("#_modal").modal('hide');$('#_modal,#_editModal').modal('hide');
                            mAppExtend.ajaxGetHtml(
                                "#project-body","{!! get_redirect_url('board_ajax_url') !!}"
                                , {}, "#project-body");
						}else{
                            mAppExtend.notification(response.message,'success','toastr',function() {
                                mAppExtend.backUrl(response.url);
                            });
						}
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