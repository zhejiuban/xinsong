@extends('layouts.app')

@section('content')
<div class="m-portlet m--margin-bottom-0">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                <h3 class="m-portlet__head-text m--font-primary">
                    新增部门
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="javascript:;" onclick="mAppExtend.backUrl()" class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
                        <span>
                            <i class="fa fa-reply"></i>
                            <span>
                                返回
                            </span>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <form class="m-form m-form--label-align-right" action="{{ route('departments.sub') }}" method="post" id="data-form">
    <div class="m-portlet__body">
        <div class="form-group m-form__group row">
            <label class="col-lg-3 col-form-label">
                上级部门:
            </label>
            <div class="col-lg-6">
                <select name="parent_id" id="parent_id" class="form-control select2 m-select2">
                    {!! department_select(request('parent_id')) !!}
                </select>
                <span class="m-form__help">请选择上级部门</span>
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label class="col-lg-3 col-form-label">
                部门名称:
            </label>
            <div class="col-lg-6">
                <input type="text" name="name" class="form-control input" id="title">
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label class="col-lg-3 col-form-label">
                状态:
            </label>
            <div class="col-lg-6">
                <div class="m-radio-inline">
                    <label class="m-radio">
                        <input type="radio" name="status" value="1" checked>
                        可用
                        <span></span>
                    </label>
                    <label class="m-radio">
                        <input type="radio" name="status" value="0">
                        不可用
                        <span></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label class="col-lg-3 col-form-label">
                排序:
            </label>
            <div class="col-lg-6">
                <input type="text" name="sort" value="0" class="form-control input" id="sort">
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label class="col-lg-3 col-form-label">
                描述:
            </label>
            <div class="col-lg-6">
                <textarea class="form-control" name="remark" id="tip" rows="6"></textarea>
            </div>
        </div>
        {{ csrf_field() }}
    </div>
    <div class="m-portlet__foot m-portlet__foot--fit">
        <div class="m-form__actions m-form__actions">
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-6">
                    <button type="submit" id="submit-button" class="btn btn-primary">
                        提交
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        重置
                    </button>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var form = $( "#data-form" );
            var submitButton = $( "#submit-button" );
            form.validate({
                // define validation rules
                rules: {
                    name: {
                        required: true
                    },status: {
                        required: true
                    },parent_id:{
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
                                $.notify({'message':response.message},{
                                    type: 'success',
                                    placement: {
                                        from: "top",
                                        align: "center"
                                    },delay:500,
                                    onClose:function() {
                                        //$('#m_role_modal').modal('hide');
                                        mAppExtend.backUrl(response.url);
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
        });
    </script>
@endsection
