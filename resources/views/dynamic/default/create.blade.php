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
        @if($phase = $project->phases()->where('status','<',2)->orderBy('id','asc')->first())
            <div class="form-group">
                <div class="m--margin-bottom-15">
                    项目名称：{{$project->title ? $project->title : null}}
                </div>
                <label class="form-control-label">
                    项目状态: {{$phase->name}}
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
            <div class="m-separator m-separator--dashed "></div>
        @endif

        @if($plan = $project->plans()->processing($is_fill?$fill_date:null)->first())
            <div class="form-group">
                <label class="form-control-label">
                    计划内容：
                </label>
                <div class="m--margin-bottom-15">
                    {{$plan->content}} <br>
                    计划开始时间：{{$plan->started_at}} , 计划完成时间：{{$plan->finished_at}} 
                    @if($plan->delay), <span class="m--font-bolder m--font-danger">已延期：{{$plan->delay}} 天 </span> @endif
                </div>
                @if(check_project_leader($project,1) || check_project_leader($project,2))
                    @if($is_end = checkEqTime($plan->finished_at,'now',$plan->delay))
                    <div>
                        <input data-switch="true" type="checkbox" data-on-text="已完成"
                               data-handle-width="60" data-off-text="未完成" data-on-color="success"
                               data-off-color="brand" name="is_finished"  id="is_finished" value="1"/>
                    </div>
                    @endif
                @endif  
            </div>
            @if(check_project_leader($project,1) || check_project_leader($project,2))
                @if($is_end)
                    <div class="form-group delay">
                        <label for="delay" class="form-control-label">
                            预计延期(天):<span class="required">*</span>
                        </label>
                        <input class="form-control" type="number" name="delay" value="">
                        <span class="m-form__help">请在日志内容中描述未按计划完成原因</span>
                    </div>
                    <input type="hidden" name="plan_id" value="{{$plan->id}}">
                @endif
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
                           data-off-color="brand" name="task_status"  id="task_status" value="1"
                        @if($task->status) checked="checked" @endif />
                </div>
                <span class="m-form__help"></span>

            </div>
            <div class="form-group task-finish-detail" @if(!$task->status) style="display: none" @endif>
                <label for="name" class="form-control-label">
                    去、离现场日期:<span class="required">*</span>
                </label>
                <div class="input-daterange input-group ">
                    <input type="text" class="form-control m-input m-date"
                           placeholder="去现场日期" name="task_builded_at" readonly value="{{$task->builded_at ? $task->builded_at : current_date()}}"/>
                    <span class="input-group-addon">
                    <i class="la la-ellipsis-h"></i>
                    </span>
                    <input type="text" class="form-control m-date" placeholder="离开现场日期"
                           name="task_leaved_at" readonly value="{{$task->leaved_at ? $task->leaved_at : current_date()}}"/>
                </div>
                <span class="m-form__help"></span>
            </div>
            <div class="form-group task-finish-detail "  @if(!$task->status) style="display: none" @endif>
                <label for="content" class="form-control-label">
                    任务完成情况:<span class="required">*</span>
                </label>
                <textarea class="form-control" name="task_result" id="task_result" rows="6">{{$task->result}}</textarea>
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
        <div class="form-group">
            <label for="question" class="form-control-label">
                遗留问题:
            </label>
            <textarea class="form-control" name="question" placeholder="请输入遗留问题" id="question" rows="6"></textarea>
            <span class="m-form__help"></span>
        </div>
        <input type="hidden" name="project_id" value="{{$project->id}}">
        @if($is_fill)
        <input type="hidden" name="fill_date" readonly value="{{$fill_date}}">
        @endif
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
        mAppExtend.datePickerInstance();
        mAppExtend.select2Instance();
        $('[data-switch=true]').bootstrapSwitch();
        $('#task_status').on('switchChange.bootstrapSwitch', function(event, state) {
            if(state){
                $('.task-finish-detail').show();
            }else{
                $('.task-finish-detail').hide();
            }
        });
        $('#is_finished').on('switchChange.bootstrapSwitch', function(event, state) {
            if(!state){
                $('.delay').show();
            }else{
                $('.delay').hide();
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
