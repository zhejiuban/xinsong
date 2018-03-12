@extends('layouts.app')

@section('content')
    <div class="m-portlet m-portlet--mobile m--margin-bottom-0">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-interface-1"></i>
                </span>
                    <h3 class="m-portlet__head-text m--font-primary">
                        新增陪产故障
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{{ get_redirect_url() }}"
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
        <!--begin::Form-->
        <form action="{{route('product_faults.store')}}" id="malfunction-form" method="post"
              class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
            <div class="m-portlet__body">
                <div class="row m-form__group ">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="">
                                所属项目:
                            </label>
                            <select class="form-control m-input select2" id="project_select" name="project_id">
                                @if(request('project_id'))
                                    <option value="{{request('project_id')}}"
                                            selected>{{get_project_info(request('project_id'))}}</option>
                                @endif
                            </select>
                            <span class="m-form__help"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group ">
                            <label>
                                小车编号:
                            </label>
                            <input type="text" name="car_no" class="form-control m-input"
                                   placeholder="请输入小车编号">
                            <span class="m-form__help"></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="">
                                故障现象:
                            </label>
                            <select class="form-control m-input select2" name="fault_cause_id">
                                {!! fault_cause_select() !!}
                            </select>
                            <span class="m-form__help"></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="">
                                发生时间:
                            </label>
                            <input type="text" name="occurrenced_at" class="form-control m-input m-dates"
                                   placeholder="请输入发生时间" value="{{current_date()}}">
                            <span class="m-form__help"></span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m-form__actions--solid">
                    <div class="row">
                        <div class="col-lg-6">
                            {{ csrf_field() }}
                            <button type="submit" id="malfunction-submit-button" class="btn btn-primary">
                                提交
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                重置
                            </button>
                        </div>
                        {{--<div class="col-lg-6 m--align-right">
                          <a href="{{ get_redirect_url() }}"  class="btn btn-secondary "><i class="fa fa-reply"></i> 返回</a>
                        </div>--}}
                    </div>
                </div>
            </div>
        </form>
        <!--end::Form-->
    </div>
@endsection
@section('js')
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
            mAppExtend.datePickerInstance('.m-dates',{
                endDate : new Date(),
                clearBtn : false
            });

            var projectSelector = $("#project_select");
            projectSelector.select2({
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

            var form = $('#malfunction-form');
            var submitButton = $("#malfunction-submit-button");
            form.validate({
                // define validation rules
                rules: {
                    car_no: {
                        required: true
                    }, fault_cause_id: {
                        required: true
                    }
                    , project_id: {
                        required: true
                    }, occurrenced_at: {
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
