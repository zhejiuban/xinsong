@extends('layouts.app')

@section('content')
    <div class="m-portlet m-portlet--mobile m--margin-bottom-0">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-interface-7"></i>
                </span>
                    <h3 class="m-portlet__head-text m--font-primary">
                        新增项目
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{{ menu_url_format(route('projects.index'),['mid'=>request('mid')])  }}"
                           class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
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
    </div>
    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--tabs  m--margin-bottom-0">
        <div class="m-portlet__head">
            <div class="m-portlet__head-tools">
                <ul class="nav nav-tabs  m-tabs-line m-tabs-line--primary" role="tablist">
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_tabs_6_1" role="tab">
                            基本信息
                        </a>
                    </li>
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_6_2" role="tab">
                            设备类型
                        </a>
                    </li>
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_6_3" role="tab">
                            项目状态
                        </a>
                    </li>
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_tabs_6_4" role="tab">
                            相关文档
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!--begin::Form-->
        <form action="{{route('projects.store')}}" id="project-form" method="post"
              class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
            <div class="m-portlet__body">
                <div class="tab-content">
                    <div class="tab-pane active" id="m_tabs_6_1" role="tabpanel">
                        <div class="row m-form__group">
                            <div class="col-md-6">
                                <div class="form-group  ">
                                    <label>
                                        项目名称:
                                    </label>
                                    <input type="text" name="title" class="form-control m-input" placeholder="请输入项目名称">
                                    <span class="m-form__help"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="">
                                        项目编号:
                                    </label>
                                    <input type="text" name="no" class="form-control m-input" placeholder="请输入项目编号">
                                    <span class="m-form__help"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="">
                                        所属办事处:
                                    </label>
                                    <select class="form-control m-input select2" name="department_id">
                                        {!! department_select(get_user_company_id(),2) !!}
                                    </select>
                                    <span class="m-form__help"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        新松负责人:
                                    </label>
                                    <select class="form-control m-input" id="user_select" name="leader">
                                    </select>
                                    <span class="m-form__help"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        项目负责人:
                                    </label>
                                    <select class="form-control m-input" id="subcompany_leader"
                                            name="subcompany_leader">
                                    </select>
                                    <span class="m-form__help"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="">
                                        现场负责人:
                                    </label>
                                    <select class="form-control m-input" id="user_select2" name="agent">
                                    </select>
                                    <span class="m-form__help"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="">
                                        项目参与人:
                                    </label>
                                    <select class="form-control m-input" id="user_select3" multiple
                                            name="project_user[]">
                                    </select>
                                    <span class="m-form__help"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="">
                                        项目描述:
                                    </label>
                                    <textarea name="remark" class="form-control m-input" rows="8"
                                              placeholder="请输入项目描述"></textarea>
                                    <span class="m-form__help"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row m-form__group">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="">
                                        客户对接人:
                                    </label>
                                    <input type="text" name="customers" class="form-control m-input"
                                           placeholder="请输入客户对接人">
                                    <span class="m-form__help"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="">
                                        客户对接人电话:
                                    </label>
                                    <input type="text" name="customers_tel" class="form-control m-input"
                                           placeholder="请输入客户对接人电话">
                                    <span class="m-form__help"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="">
                                        客户地址:
                                    </label>
                                    <input type="text" name="customers_address" class="form-control m-input"
                                           placeholder="请输入客户地址">
                                    <span class="m-form__help"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane device-list" id="m_tabs_6_2" role="tabpanel">
                        <div class="form-group m-form__group row ">
                            <div class="col-md-12">
                                <button type="button" title="添加" id="add-device-option" class="btn btn-primary ">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group m-form__group row device-option last-device-option" id="device-option-1">
                            <div class="col-md-1">
                                <h1 class="device-option-sort">1</h1>
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="">设备类型:</label>
                                <select class="form-control m-input select2" name="device_project[1][device_id]">
                                    {!! device_select() !!}
                                </select>
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-4">
                                <label class="">数量:</label>
                                <input type="number" name="device_project[1][number]" class="form-control m-input"
                                       placeholder="请输入设备数量">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">&nbsp;</label>
                                <div class="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="m_tabs_6_3" role="tabpanel">
                        <div class="form-group m-form__group row phase-option" id="phase-option-1">
                            <div class="col-md-1">
                                <h1>1</h1>
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">建设阶段:</label>
                                <input type="text" class="form-control m-input" name="project_phases[1][name]" readonly
                                       value="现场装配">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">开始时间:</label>
                                <input type="text" name="project_phases[1][started_at]"
                                       class="form-control m-input m-date" placeholder="请选择时间">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">结束时间:</label>
                                <input type="text" name="project_phases[1][finished_at]"
                                       class="form-control m-input  m-date" placeholder="请选择时间">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-2 hide" style="display:none;">
                                <label class="">状态:</label>
                                <select class="form-control m-input select2" name="project_phases[1][status]">
                                    {!! project_phases_status_select() !!}
                                </select>
                                <span class="m-form__help"></span>
                            </div>
                        </div>

                        <div class="form-group m-form__group row phase-option" id="phase-option-2">
                            <div class="col-md-1">
                                <h1>2</h1>
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">建设阶段:</label>
                                <input type="text" class="form-control m-input" name="project_phases[2][name]" readonly
                                       value="现场调试">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">开始时间:</label>
                                <input type="text" name="project_phases[2][started_at]"
                                       class="form-control m-input  m-date" placeholder="请选择时间">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">结束时间:</label>
                                <input type="text" name="project_phases[2][finished_at]"
                                       class="form-control m-input m-date" placeholder="请选择时间">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-2 hide" style="display:none;">
                                <label class="">状态:</label>
                                <select class="form-control m-input select2" name="project_phases[2][status]">
                                    {!! project_phases_status_select() !!}
                                </select>
                                <span class="m-form__help"></span>
                            </div>
                        </div>
                        <div class="form-group m-form__group row phase-option" id="phase-option-3">
                            <div class="col-md-1">
                                <h1 class="">3</h1>
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">建设阶段:</label>
                                <input type="text" class="form-control m-input" name="project_phases[3][name]" readonly
                                       value="陪产试机">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">开始时间:</label>
                                <input type="text" name="project_phases[3][started_at]"
                                       class="form-control m-input m-date" placeholder="请选择时间">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">结束时间:</label>
                                <input type="text" name="project_phases[3][finished_at]"
                                       class="form-control m-input m-date" placeholder="请选择时间">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-2 hide" style="display:none;">
                                <label class="">状态:</label>
                                <select class="form-control m-input select2" name="project_phases[3][status]">
                                    {!! project_phases_status_select() !!}
                                </select>
                                <span class="m-form__help"></span>
                            </div>
                        </div>
                        <div class="form-group m-form__group row phase-option" id="phase-option-4">
                            <div class="col-md-1">
                                <h1 class="">4</h1>
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">建设阶段:</label>
                                <input type="text" class="form-control m-input" name="project_phases[4][name]" readonly
                                       value="终验整改">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">开始时间:</label>
                                <input type="text" name="project_phases[4][started_at]"
                                       class="form-control m-input m-date" placeholder="请选择时间">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">结束时间:</label>
                                <input type="text" name="project_phases[4][finished_at]"
                                       class="form-control m-input m-date" placeholder="请选择时间">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-2 hide" style="display:none;">
                                <label class="">状态:</label>
                                <select class="form-control m-input select2" name="project_phases[4][status]">
                                    {!! project_phases_status_select() !!}
                                </select>
                                <span class="m-form__help"></span>
                            </div>
                        </div>
                        <div class="form-group m-form__group row phase-option last-phase-option" id="phase-option-5">
                            <div class="col-md-1">
                                <h1 class="">5</h1>
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">建设阶段:</label>
                                <input type="text" class="form-control m-input" name="project_phases[5][name]" readonly
                                       value="质保售后">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">开始时间:</label>
                                <input type="text" name="project_phases[5][started_at]"
                                       class="form-control m-input m-date" placeholder="请选择时间">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-3">
                                <label class="">结束时间:</label>
                                <input type="text" name="project_phases[5][finished_at]"
                                       class="form-control m-input m-date" placeholder="请选择时间">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-md-2 hide" style="display:none;">
                                <label class="">状态:</label>
                                <select class="form-control m-input select2" name="project_phases[5][status]">
                                    {!! project_phases_status_select() !!}
                                </select>
                                <span class="m-form__help"></span>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="m_tabs_6_4" role="tabpanel">
                        <div class="form-group m-form__group row">
                            <div class="col-md-12">
                                <div id="file-upload-instance" class="clearfix multi-image-upload">
                                    <div id="file-upload-instance-picker"
                                         class="pull-left m-b-sm p-xxs b-r-sm tooltips uploader-picker"
                                         data-container="body" data-html=true data-toggle="m-tooltip"
                                         data-placement="top"
                                         data-original-title="单个文件大小{{format_bytes(config('filesystems.disks.file.validate.size')*1024)}}以内,允许上传类型：{{arr2str(config('filesystems.disks.file.validate.ext'))}}">
                                        <p class="m-b-sm"><i class="fa fa-plus-circle m--font-primary fa-2x fa-fw"></i>
                                        </p>
                                        选择文件
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions--solid">
                    <div class="row">
                        <div class="col-lg-6">
                            {{ csrf_field() }}
                            <button type="submit" id="submit-button" class="btn btn-primary">
                                提交
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                重置
                            </button>
                        </div>
                        {{--<div class="col-lg-6 m--align-right">--}}
                            {{--<a href="{{ menu_url_format(route('projects.index'),['mid'=>request('mid')])  }}"--}}
                               {{--class="btn btn-secondary "><i class="fa fa-reply"></i> 返回</a>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
        </form>
        <!--end::Form-->
    </div>
    <!--end::Portlet-->
@endsection
@section('js')
    <script type="text/javascript">
        //异步加载用户选择框
        function formatRepo(repo) {
            if (repo.loading) return repo.text;
            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>" + repo.name + "</div>";
            if (repo.department || repo.company) {
                markup += "<div class='select2-result-repository__description'>所在部门：" + (repo.company ? repo.company.name : '') + (repo.department && repo.department.level == 3 ? '/' + repo.department.name : '') + "</div>";
            }
            markup += "</div></div>";
            return markup;
        }

        function formatRepoSelection(repo) {
            return repo.name || repo.text;
        }

        function delDeviceOption(n) {
            $('#device-option-' + n).remove();
            $('.device-option-sort').each(function (i, v) {
                $(v).html(i + 1);
            });
        }

        jQuery(document).ready(function () {
            //异步加载用户选择框
            var $userSelector = $("#user_select2,#subcompany_leader").select2({
                language: 'zh-CN',
                placeholder: "输入姓名、用户名等关键字搜索",
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{route('users.selector.data')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
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
                templateResult: formatRepo, // omitted for brevity, see the source of this page
                templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
            });
            var $userSelector2 = $("#user_select").select2({
                language: 'zh-CN',
                placeholder: "输入姓名、用户名等关键字搜索",
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{route('users.selector.data',['type'=>'all'])}}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
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
                templateResult: formatRepo, // omitted for brevity, see the source of this page
                templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
            });
            $("#user_select3").select2({
                language: 'zh-CN',
                placeholder: "输入姓名、电话、邮箱、用户名等关键字搜索，选择参与用户",
                allowClear: false,
                width: '100%',
                ajax: {
                    url: "{{route('users.selector.data')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
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
                },
                minimumInputLength: 0,
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            });
            $('#add-device-option').click(function (event) {
                var length = $('.device-option').length;
                var next = length + 1;
                var option = '';
                option += '<div class="form-group m-form__group row device-option" id="device-option-' + next + '">';
                option += '<div class="col-md-1">';
                option += '<h1 class="device-option-sort"></h1>';
                option += '<span class="m-form__help"></span>';
                option += '</div>';
                option += '<div class="col-md-4">';
                option += '<label class="">设备类型:</label>';
                option += '<select class="form-control m-input select2" name="device_project[' + next + '][device_id]">{!! device_select() !!}</select>';
                option += '<span class="m-form__help"></span>';
                option += '</div>';
                option += '<div class="col-md-4">';
                option += '<label class="">数量:</label>';
                option += '<input type="number" name="device_project[' + next + '][number]" class="form-control m-input" placeholder="请输入设备数量">';
                option += '<span class="m-form__help"></span>';
                option += '</div>';
                option += '<div class="col-md-3">';
                option += '<label class="">&nbsp;</label>';
                option += '<div class="">';
                option += '<button type="button" title="删除" id="del-device-option-' + next + '" onclick="delDeviceOption(' + next + ');" device-id="' + next + '" class="btn btn-danger del-device-option">';
                option += '<i class="fa fa-trash"></i>';
                option += '</button>';
                option += '</div>';
                option += '</div>';
                option += '</div>';
                $('.device-list').append(option);
                $('.device-option-sort').each(function (i, v) {
                    $(v).html(i + 1);
                });
                $('#device-option-' + next + ' .select2').select2({width: '100%'});
            });
            // $('.device-list').on('click', '.del-device-option', function(event) {
            //   event.preventDefault();
            //   var del = $(this).attr('device-id');
            //   $('#device-option-'+del).remove();
            // });
            //

            //多文件上传实例
            mAppExtend.fileUpload({
                uploader: 'fileUploadInstance',
                picker: 'file-upload-instance',
                swf: '{{ asset("assets/js/plugins/webuploader/Uploader.swf") }}',
                server: '{{ route("file.upload") }}',
                formData: {
                    '_token': '{{ csrf_token() }}'
                },
                fileNumLimit: 10,
                isAutoInsertInput: true,//上传成功是否自动创建input存储区域
                storageInputName: 'file_project',//上传成功后input存储区域的name
                uploadComplete: function (file, uploader) {
                },
                uploadError: function (file, uploader) {
                },
                uploadSuccess: function (file, response, uploader) {
                },
                fileCannel: function (fileId, uploader) {
                },
                fileDelete: function (fileId, uploader) {
                }
            });

            var form = $('#project-forms');
            var submitButton = $("#submit-button");
            form.validate({
                // define validation rules
                rules: {
                    title: {
                        required: true
                    }, no: {
                        required: true
                    }, leader: {
                        required: true
                    }, subcompany_leader: {
                        required: true
                    }, agent: {
                        required: true
                    }, department_id: {
                        required: true
                    }, customers: {
                        required: true
                    }, customers_tel: {
                        required: true
                    }, customers_address: {
                        required: true
                    }, 'project_user[]': {
                        required: true
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
                                mAppExtend.notification(response.message
                                    , 'success', 'toastr', function () {
                                        mAppExtend.backUrl(response.url);
                                    });
                            } else {
                                mAppExtend.notification(response.message
                                    , 'error');
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
                            mAppExtend.notification(_err_mes
                                , 'error');
                        }
                    });
                }
            });

        });
    </script>
@endsection
