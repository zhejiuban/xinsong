<div class="modal-header">
	<h5 class="modal-title" id="_ModalLabel">
		添加计划
	</h5>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">
			&times;
		</span>
	</button>
</div>
<div class="modal-body">
	<form class="m-form" action="{{ route('plans.import',['project'=>$project]) }}" method="post" id="plans-form">
		<div class="row m-form__group ">
			<div class="col-md-12 ">
				<div class="form-group">
					<label for="project_id" class="form-control-label">
						模板类型:
					</label>
					<div class="">
						<select class="form-control m-input select2"  id="temp_cate" name="temp_cate">
							 {!! project_plan_temp() !!}
						</select>
					</div>
					<span class="m-form__help"></span>
				</div>
			</div>
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

	function checkEndTime(startTime,endTime){  
	    var start = new Date(startTime.replace("-", "/").replace("-", "/"));  
	    var end = new Date(endTime.replace("-", "/").replace("-", "/"));  
	    if(end > start){  
	        return false;  
	    }  
	    return true;  
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
        messages:{},
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
    $("#submit-button").click(function(event) {
      form.submit();
    });
  });

</script>