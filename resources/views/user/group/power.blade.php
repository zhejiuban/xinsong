@extends('layouts.app')

@section('content')
<div class="m-portlet m--margin-bottom-0">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-list-2"></i>
                </span>
                <h3 class="m-portlet__head-text m--font-primary">
                    角色授权
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="#" class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
                        <span>
                            <i class="fa fa-edit"></i>
                            <span>
                                保存
                            </span>
                        </span>
                    </a>
                </li>
                <li class="m-portlet__nav-item">
                    <a href="{{ route('groups.index')  }}" class="btn btn-metal btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
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
    <div class="m-portlet__body">
a
    </div>
</div>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.m-demo-icon').click(function () {
                var cl = $(this).children('.m-demo-icon__preview').children('i').attr('class');
                $('#icon-preview').removeClass().addClass(cl);
                $('#icon_class').val(cl);
                $('#btn_select_modal').modal('hide');
            });

            var form = $( "#data-form" );
            var submitButton = $( "#submit-button" );
            form.validate({
                // define validation rules
                rules: {
                    parent_id: {
                        required: true
                    },
                    title: {
                        required: true
                    },url: {
                        required: true
                    }/*,gurad_name: {
                        required: true
                    }*/,hide: {
                        required: true
                    },target: {
                        required: true
                    },status: {
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
