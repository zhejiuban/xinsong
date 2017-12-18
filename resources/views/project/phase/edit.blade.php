<div class="modal-header">
    <h5 class="modal-title" id="_PhaseModalLabel">
        编辑项目状态
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">
      &times;
    </span>
    </button>
</div>
<div class="modal-body">
    <form class="m-form" action="{{ route('project.phases.update',['phase'=>$phase->id]) }}" method="post"
          id="phase-form">
        <div class="form-group">
            <label for="name" class="form-control-label">
                开始日期:
            </label>
            <input type="text" class="form-control m-input m-date" placeholder="开始日期" name="started_at"
                   value="{{$phase->started_at}}"/>
            <span class="m-form__help"></span>
        </div>
        <div class="form-group">
            <label for="name" class="form-control-label">
                完成日期:
            </label>
            <input type="text" class="form-control m-input m-date" placeholder="开始日期" name="finished_at"
                   value="{{$phase->finished_at}}"/>
            <span class="m-form__help"></span>
        </div>
        {{--<div class="form-group">
            <label>
                状态:
            </label>
            <div class="">
                <select class="form-control m-input select2" name="status">
                    {!! project_phases_status_select($phase->status) !!}
                </select>
            </div>
            <span class="m-form__help"></span>
        </div>--}}
        {{ csrf_field() }}
        {{ method_field('PUT') }}
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">
        关闭
    </button>
    <button type="button" class="btn btn-primary" id="phase-submit-button">
        提交
    </button>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        mAppExtend.datePickerInstance();
        mAppExtend.select2Instance();
        var form = $("#phase-form");
        var submitButton = $("#phase-submit-button");
        form.validate({
            // define validation rules
            rules: {
                // started_at: {
                //     required: true
                // },
                // finished_at: {
                //     required: true
                // },
                // status: {
                //     required: true
                // }
            },
            messages: {
                started_at: {
                    required: '请选择开始时间'
                }, finished_at: {
                    required: '请选择完成时间'
                }
            },
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
        $("#phase-submit-button").click(function (event) {
            form.submit();
        });
    });
</script>
