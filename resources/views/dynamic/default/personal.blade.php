@extends('layouts.app')
@section('content')
    <div class="m-portlet">
        <form method="get" action="{{route('dynamic.personal',['mid'=>request('mid')])}}"
              class="m-form m-form--fit m-form--group-seperator-dashed m-form-group-padding-bottom-10">
            <div class="m-portlet__body ">
                <div class=" m-form__group row">
                    <div class="col-lg-4">
                        <div class="form-group">
                        <select name="project_id" class="form-control" id="project_id">
                        </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="text" class="form-control m-input m-date"
                                   placeholder="上传日期" name="date" id="date"
                                   readonly value=""/>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                        <input type="hidden" name="mid" value="{{request('mid')}}">
                        <button type="submit" class="btn btn-brand  m-btn m-btn--pill m-btn--icon">
                            <span>
                                <i class="la la-search"></i>
                                <span>搜索</span>
                            </span>
                        </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @foreach($list as $task)
        <div class="m-portlet">
            <div class="m-portlet__body  m-portlet__body--no-padding">
                <div class="m-widget  m--padding-20">
                    <div class="m-widget-body">
                        <div class="m-section m-section-none">
                            <h3 class="m-section__heading m-line-height-25">
                                <a class="action-show" href="{{route('dynamics.show',['dynamic'=>$task->id,'mid'=>request('mid')])}}">
                                    {{str_limit($task->content,50,'...')}}
                                    @if($task->fill)
                                    <span class="m-badge  m-badge--warning m-badge--wide">补</span>
                                    @endif
                                </a>
                            </h3>
                            <span class="m-section__sub m-section__sub-margin-bottom-none">
                                所属项目：{{$task->project ? $task->project->title : null}} <br>
                                上传日期：{{$task->created_at}}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{ $list->appends([
        'mid' => request('mid'),
        'project_id' => request('project_id'),
        'search' => request('search'),
        'date' => request('date'),
    ])->links('vendor.pagination.bootstrap-4') }}
    <!--begin::Modal-->
    <div class="modal fade" id="_modal" tabindex="-1" role="dialog" aria-labelledby="_ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!--end::Modal-->
@endsection
@section('js')
    <script type="text/javascript">
        function formatRepo(repo) {
            if (repo.loading) return repo.text;
            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>" + repo.name + "</div>";
            if (repo.department) {
                markup += "<div class='select2-result-repository__description'>所在部门：" + repo.department.name + "</div>";
            }
            markup += "</div></div>";
            return markup;
        }
        function formatRepoSelection(repo) {
            return repo.name || repo.text;
        }
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

        var ActionModal = function (url, type) {
            $('#_modal').modal(type ? type : 'show');
            mAppExtend.ajaxGetHtml(
                '#_modal .modal-content',
                url,
                {}, true);
        }
        $(document).ready(function () {
            var $projectSelector = $("#project_id").select2({
                language:'zh-CN',
                placeholder: "输入项目编号、名称等关键字搜索，选择项目",
                allowClear: true,
                width:'100%',
                ajax: {
                    url: "{{route('projects.selector.data')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
                            per_page: {{config('common.page.per_page')}}
                        };
                    },
                    processResults: function(data, params) {
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
                escapeMarkup: function(markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 0,
                templateResult: formatProjectRepo, // omitted for brevity, see the source of this page
                templateSelection: formatProjectRepoSelection // omitted for brevity, see the source of this page
            });
            $('a.action-show').click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                ActionModal(url,'show')
            });
        });
    </script>
@endsection