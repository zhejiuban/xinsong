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
                    编辑菜单
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{ route('menus.index')  }}" class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
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
    <form class="m-form m-form--label-align-right" action="{{ route('menus.update',['menu'=>$menu->id]) }}" method="post" id="data-form">
    <div class="m-portlet__body">
        <div class="form-group m-form__group row">
            <label class="col-lg-3 col-form-label">
                上级菜单:
            </label>
            <div class="col-lg-6">
                <select name="parent_id" id="parent_id" class="form-control select2 m-select2">
                    {!! menu_select($menu->parent_id) !!}
                </select>
                <span class="m-form__help">请选择上级菜单</span>
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label class="col-lg-3 col-form-label">
                菜单名称:
            </label>
            <div class="col-lg-6">
                <input type="text" name="title" value="{{$menu->title}}" class="form-control input" id="title">
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label class="col-lg-3 col-form-label">
                访问地址:
            </label>
            <div class="col-lg-6">
                <input type="text" name="url" value="{{$menu->url}}" class="form-control input" id="url">
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label class="col-lg-3 col-form-label">
                图标:
            </label>
            <div class="col-lg-6">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i id="icon-preview" class="flaticon-visible"></i>
                    </span>
                    <input type="text" id="icon_class" value="{{$menu->icon_class}}"  name="icon_class" class="form-control" placeholder="">
                    <span class="input-group-btn">
                        <button data-toggle="modal" data-target="#btn_select_modal" class="btn btn-primary" type="button">
                            选择
                        </button>
                    </span>
                </div>
            </div>
        </div>
        {{--<div class="form-group m-form__group row">--}}
            {{--<label class="col-lg-3 col-form-label">--}}
                {{--分组:--}}
            {{--</label>--}}
            {{--<div class="col-lg-6">--}}
                {{--<select name="gurad_name" id="gurad_name" class="form-control select2 m-select2">--}}
                    {{--{!! gurad_name_select($menu->gurad_name) !!}--}}
                {{--</select>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="form-group m-form__group row">
            <label class="col-lg-3 col-form-label">
                是否隐藏:
            </label>
            <div class="col-lg-6">
                <div class="m-radio-inline">
                    <label class="m-radio">
                        <input type="radio" name="hide" value="0" {{ $menu->hide ? '' : 'checked' }}>
                        否
                        <span></span>
                    </label>
                    <label class="m-radio">
                        <input type="radio" name="hide" value="1" {{ $menu->hide ? 'checked' : '' }}>
                        是
                        <span></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="m-form__group form-group row">
            <label class="col-lg-3 col-form-label">
                打开方式:
            </label>
            <div class="col-lg-6">
                <div class="m-radio-inline">
                    <label class="m-radio">
                        <input type="radio" name="target" value="_self" {{ $menu->target == '_self' ? 'checked' : '' }}>
                        当前窗口
                        <span></span>
                    </label>
                    <label class="m-radio">
                        <input type="radio" name="target" value="_blank"  {{ $menu->target == '_self' ? '' : 'checked' }}>
                        新窗口
                        <span></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label class="col-lg-3 col-form-label">
                状态:
            </label>
            <div class="col-lg-6">
                <div class="m-radio-inline">
                    <label class="m-radio">
                        <input type="radio" name="status" value="1" {{ $menu->status ? 'checked' : '' }}>
                        可用
                        <span></span>
                    </label>
                    <label class="m-radio">
                        <input type="radio" name="status" value="0" {{ $menu->status ? '' : 'checked' }}>
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
                <input type="text" name="sort" value="{{$menu->sort}}" class="form-control input" id="sort">
            </div>
        </div>
        <div class="form-group m-form__group row">
            <label class="col-lg-3 col-form-label">
                描述:
            </label>
            <div class="col-lg-6">
                <textarea class="form-control" name="tip" id="tip" rows="6">{{$menu->tip}}</textarea>
            </div>
        </div>
        <input type="hidden" value="{{$menu->id}}" name="id">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
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
<div class="modal fade" id="btn_select_modal" tabindex="-1" role="dialog" aria-labelledby="BtnSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    选择图标
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('system.menu.icon')
            </div>
        </div>
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
