<div class="modal-header">
    <h5 class="modal-title" id="_EditModalLabel">
        任务完成情况
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
    </button>
</div>
<div class="modal-body">
    <form class="m-form" action="{{ route('tasks.finish',['task'=>$task->id]) }}" method="post" id="task-form">

        <div class="form-group">
            <label for="name" class="form-control-label">
                去、离现场日期:
            </label>
            <div class="input-daterange input-group ">
                <input type="text" class="form-control m-input m-datetime" placeholder="去现场日期" name="builded_at" value="{{$task->builded_at}}"/>
                <span class="input-group-addon">
                <i class="la la-ellipsis-h"></i>
            </span>
                <input type="text" class="form-control m-datetime" placeholder="离开现场日期" name="leaved_at" value="{{$task->leaved_at}}"/>
            </div>
            <span class="m-form__help"></span>
        </div>

        <div class="form-group">
            <label for="content" class="form-control-label">
                任务完成情况:
            </label>
            <textarea class="form-control" name="result" id="result" rows="6">{{$task->result}}</textarea>
            <span class="m-form__help"></span>
        </div>
        {{ csrf_field() }}
        {{ method_field('POST') }}
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
        mAppExtend.dateTimePickerInstance();
        var form = $( "#task-form" );
        var submitButton = $("#submit-button");
        form.validate({
            // define validation rules
            rules: {
                builded_at: {
                    required: true
                },
                leaved_at: {
                    required: true
                },
                result: {
                    required: true
                }
            },
            messages:{
                builded_at:{
                    required:'请选择去现场时间'
                },leaved_at:{
                    required:'请选择离开现场时间'
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
                            mAppExtend.notification(response.message,'success','toastr',function() {
                                mAppExtend.backUrl(response.url);
                            });
                            //$('#_editModal').modal('hide');
                            //lookTask("{{ route('tasks.show',['task'=>$task->id]) }}",'handleUpdate');
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
