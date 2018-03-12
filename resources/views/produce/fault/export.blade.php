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
                        陪产故障导出
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
        <form action="{{route('product_faults.export')}}" id="fault-form" method="post"
              class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
            <div class="m-portlet__body">
                <div class="row m-form__group ">
                    <div class="col-md-12">
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
                        <div class="form-group">
                            <label class="">
                                起始时间:
                            </label>
                            <input type="text" name="start_at" class="form-control m-input m-date"
                                   placeholder="请输入开始时间" value="">
                            <span class="m-form__help"></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="">
                                截止时间:
                            </label>
                            <input type="text" name="end_at" class="form-control m-input m-date"
                                   placeholder="请输入结束时间" value="{{current_date()}}">
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
                            <button type="submit" id="fault-submit-button" class="btn btn-primary">
                                导出
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                重置
                            </button>
                        </div>
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


        });
    </script>
@endsection
