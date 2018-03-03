<div class="modal-header">
	<h5 class="modal-title" id="_ModalLabel">
		编辑计划
	</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">
			&times;
		</span>
	</button>
</div>
<div class="modal-body">
	<form class="m-form" action="{{ route('plans.update',['project'=>$project,'plan'=>$plan->id]) }}" method="post" id="plans-form">
		<div class="row m-form__group ">
			<div class="col-md-12 hide">
				<div class="form-group">
					<label>
						所属项目:
					</label>
					<div class="">
						<select class="form-control m-input select2"  id="project_select" name="project_id">
							@if($project)
								<option value="{{$project->id}}"
										selected>{{$project->title}}</option>
							@endif
						</select>
					</div>
					<span class="m-form__help"></span>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="sort" class="form-control-label">
						序号:
					</label>
					<div class="">
						<input type="number" name="sort" value="{{$plan->sort}}" class="form-control m-input" >
					</div>
					<span class="m-form__help"></span>
				</div>
			</div>
			<div class="col-md-12">
				<div class="form-group">
					<label for="content" class="form-control-label">
						计划内容:
					</label>
					<textarea class="form-control" name="content" id="content" rows="6">{{$plan->content}}</textarea>
					<span class="m-form__help"></span>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="started_at" class="form-control-label">
						计划开始日期:
					</label>
		            <input type="text" class="form-control m-input m-date" value="{{$plan->started_at}}" placeholder="计划开始日期" name="started_at" />
					<span class="m-form__help"></span>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="finished_at" class="form-control-label">
						计划完成日期:
					</label>
		            <input type="text" value="{{$plan->finished_at}}" class="form-control m-input m-date" placeholder="计划完成日期" name="finished_at" id="finished_at" />
					<span class="m-form__help"></span>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="leader" class="form-control-label">
						执行人:
					</label>
					<div class="">
						<select class="form-control m-input select2" id="leader" name="user_id">
							{!!project_user_select($project->id,$plan->user_id)!!}
						</select>
					</div>
					<span class="m-form__help">可从项目参与人中选择执行人</span>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="last_finished_at" class="form-control-label">
						实际完成日期:
					</label>
		            <input type="text" class="form-control m-input m-date" placeholder="实际完成日期" name="last_finished_at" value="{{$plan->last_finished_at}}" id="last_finished_at"/>
					<span class="m-form__help"></span>
				</div>
			</div>
			<div id="reason-area" class="col-md-12 @if($plan->is_finished || is_null($plan->is_finished)) hide @endif">
				<div class="form-group">
					<label for="content" class="form-control-label">
						未按计划完成原因说明:
					</label>
					<textarea class="form-control" name="reason" id="reason" rows="6">{{$plan->reason}}</textarea>
					<span class="m-form__help"></span>
				</div>
			</div>
		</div>
		<input type="hidden" name="is_finished" value="{{$plan->is_finished}}">
		<input type="hidden" id="status" name="status" value="0">
		{{ csrf_field() }}
		{{ method_field('PUT')}}
	</form>
</div>
<div class="modal-footer">
	<span class="m--font-danger">
        提交之后不允许再修改，请及时提交
    </span>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">
        关闭
    </button>
    <button type="button" class="btn btn-warning" id="save-button">
        保存
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
        placeholder:'请选择执行人'
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

	$("#finished_at,#last_finished_at").change(function(){
		var finished_at = $("#finished_at").val();
		var last_finished_at = $("#last_finished_at").val();

		if(finished_at && !checkEndTime(finished_at,last_finished_at)){
			$('#reason-area').removeClass('hide');
			$('#reason').rules("add",{required:true});
		}else{
			$('#reason-area').addClass('hide');
			$('#reason').rules("remove");  
		}
	});

    var form = $( "#plans-form" );
    var submitButton = $("#submit-button");
    var saveButton = $("#save-button");
    form.validate({
        // define validation rules
        rules: {
        	sort:{
                required: true,
                number:true
            },
            project_id: {
                required: true
            },
            started_at: {
                required: true
            },
            content: {
                required: true
            },
            user_id: {
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
                        mAppExtend.notification(response.message,'success','toastr');
	                    $('#_modal,#_editModal').modal('hide');
	                    datatable.reload();
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
    submitButton.click(function(event) {
    	$('#status').val(1);
      	form.submit();
    });
    saveButton.click(function(event) {
    	$('#status').val(0);
      	form.submit();
    });
  });

</script>