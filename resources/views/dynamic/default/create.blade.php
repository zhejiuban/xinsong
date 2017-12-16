<div class="modal-header">
    <h5 class="modal-title" id="_ModalLabel">
        上传日志
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
    </button>
</div>
<div class="modal-body">
    <form class="m-form" action="{{ route('dynamics.store') }}" method="post" id="dynamic-form">
        {{--<div class="form-group">
            <label for="onsite_user" class="form-control-label">
                现场人员:<span class="required">*</span>
            </label>
            <input class="form-control m-input" name="onsite_user" id="onsite_user" />
            <span class="m-form__help">第三方配合人员请标注</span>
        </div>--}}
        @if(check_project_leader($project,1) || check_project_leader($project,2))
            @if($phase = $project->phases()->where('status','<',2)->orderBy('id','asc')->first())
                <div class="form-group">
                    <label class="form-control-label">
                        项目状态-{{$phase->name}}:<span class="required">*</span>
                    </label>
                    <div>
                        @if($phase->status)
                            <input data-switch="true" type="checkbox" data-on-text="已完成"
                                   data-handle-width="60" data-off-text="进行中" data-on-color="success"
                                   data-off-color="brand" name="phase_status" value="{{$phase->status}}">
                        @else
                            <input data-switch="true" type="checkbox" data-on-text="开始"
                                   data-handle-width="60" data-off-text="未开始" data-on-color="brand"
                                   name="phase_status" value="{{$phase->status}}">
                        @endif
                    </div>
                </div>
                <input type="hidden" name="phase_id" value="{{$phase->id}}">
            @endif
            <div class="m-separator m-separator--dashed "></div>
        @endif

        @if($task)
            <div class="form-group">
                <label class="form-control-label">
                    任务内容：
                </label>
                <div class="m--margin-bottom-15">
                    {{$task->content}}
                </div>
                <div>
                    <input data-switch="true" type="checkbox" data-on-text="已完成"
                           data-handle-width="60" data-off-text="进行中" data-on-color="success"
                           data-off-color="brand" name="task_status"  id="task_status" value="1">
                </div>
                <span class="m-form__help"></span>

            </div>
            <div class="form-group task-finish-detail" style="display: none">
                <label for="name" class="form-control-label">
                    去、离现场日期:<span class="required">*</span>
                </label>
                <div class="input-daterange input-group ">
                    <input type="text" class="form-control m-input m-datetime" placeholder="去现场日期" name="task_builded_at" value=""/>
                    <span class="input-group-addon">
                    <i class="la la-ellipsis-h"></i>
                    </span>
                    <input type="text" class="form-control m-datetime" placeholder="离开现场日期" name="task_leaved_at" value=""/>
                </div>
                <span class="m-form__help"></span>
            </div>
            <div class="form-group task-finish-detail "  style="display: none">
                <label for="content" class="form-control-label">
                    任务完成情况:<span class="required">*</span>
                </label>
                <textarea class="form-control" name="task_result" id="task_result" rows="6"></textarea>
                <span class="m-form__help"></span>
            </div>
            <input type="hidden" name="task_id" value="{{$task->id}}">
            <div class="m-separator m-separator--dashed "></div>
        @endif

        <div class="form-group">
            <label for="content" class="form-control-label">
                日志内容:<span class="required">*</span>
            </label>
            <textarea class="form-control" name="content" placeholder="请输入日志内容" id="content" rows="6"></textarea>
            <span class="m-form__help"></span>
        </div>
        <input type="hidden" name="project_id" value="{{$project->id}}">
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
    jQuery(document).ready(function () {
        mAppExtend.dateTimePickerInstance();
        mAppExtend.select2Instance();
        $('[data-switch=true]').bootstrapSwitch();
        $('#task_status').on('switchChange.bootstrapSwitch', function(event, state) {
            if(state){
                $('.task-finish-detail').show();
            }else{
                $('.task-finish-detail').hide();
            }
        });
        var form = $("#dynamic-form");
        var submitButton = $("#submit-button");
        form.validate({
            // define validation rules
            rules: {
                // onsite_user: {
                //     required: true
                // },
                content: {
                    required: true
                }
            },
            messages: {},
            //display error alert on form submit
            invalidHandler: function (event, validator) {
            },
            submitHandler: function (forms) {
                // alert(1);
                form.ajaxSubmit({
                    beforeSend: function () {
                        submitButton.attr('disabled', 'disabled')
                            .addClass('m-loader m-loader--light m-loader--right');
                    },
                    complete: function () {
                        submitButton.removeAttr('disabled')
                            .removeClass('m-loader m-loader--light m-loader--right');
                    },
                    success: function (response, status, xhr, $form) {
                        if (response.status == 'success') {
                            mAppExtend.notification(response.message, 'success', 'toastr', function () {
                                mAppExtend.backUrl(response.url);
                            });
                        } else {
                            mAppExtend.notification(response.message, 'error');
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        _$error = xhr.responseJSON.errors;
                        var _err_mes = '未知错误，请重试';
                        if (_$error != undefined) {
                            _err_mes = '';
                            $.each(_$error, function (i, v) {
                                _err_mes += v[0] + '<br>';
                            });
                        }
                        mAppExtend.notification(_err_mes, 'error');
                    }
                });
            }
        });
        $("#submit-button").click(function (event) {
            form.submit();
        });
    });
</script>
