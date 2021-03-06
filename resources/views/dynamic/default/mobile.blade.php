@extends('layouts.app')
@section('content')
    <div class="m-portlet">
        <form method="get" action="{{route('project.dynamic.dynamics',['mid'=>request('mid')])}}"
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
                            <select name="user_id" class="form-control" id="user_id">
                            </select>
                        </div>
                    </div>

                </div>
                <div class="m-form__group row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="text" class="form-control m-input m-date"
                                   placeholder="上传日期" name="date" id="date"
                                   readonly value="{{request('date')}}"/>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="text" class="form-control m-input"
                                   placeholder="关键字" name="search" id="search"
                                   value="{{request('search')}}"/>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <input type="hidden" name="mid" value="{{request('mid')}}">
                            <button type="submit" class="btn btn-brand  m-btn--pill">
                                查询
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @foreach($list as $task)
        <div class="m-portlet" id="dynamic-{{$task->id}}">
            <div class="m-portlet__body">
                <div class="m-widget">
                    <div class="m-widget-body">
                        <div class="m-section m-section-none">
                            <h3 class="m-section__heading m-line-height-25">
                                <a class="action-show" href="{{route('dynamics.show',['dynamic'=>$task->id,'mid'=>request('mid')])}}">
                                {{$task->content}}
                                </a>
                                @if($task->fill)
                                <span class="m-badge  m-badge--warning m-badge--wide">补</span>
                                @endif
                                @if(check_project_owner($task->project,'company'))
                                <a href="{{route("dynamics.destroy",['dynamic'=>$task->id,'mid'=>request('mid')])}}"
                                   class="btn btn-outline-danger m-btn m-btn--icon btn-sm m-btn--icon-only
                                  m-btn--pill pull-right action-del" data-dynamic-id="{{$task->id}}"><i class="la la-trash"></i></a>
                                @endif
                            </h3>
                            <span class="m-section__sub m-section__sub-margin-bottom-none">
                                所属项目：{{$task->project ? $task->project->title : null}} <br>
                                上报人：{{$task->user?$task->user->name : null}} <br>
                                上传日期：{{$task->created_at}}
                                @if($task->project && $task->project->committedPlans->isNotEmpty())
                                <br>
                                实施计划：<a href="{{route('plans.index',['project'=>$task->project,'mid'=>request('mid')])}}" title="项目实施计划"><span class="m-badge m-badge--brand m-badge--wide">详情</span></a>
                                @endif
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
        'date' => request('date'),
        'search' => request('search'),
    ])->links('vendor.pagination.bootstrap-4') }}
    <!--begin::Modal-->
    <div class="modal fade" id="_modal" tabindex="-1" role="dialog" aria-labelledby="_ModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!--end::Modal-->
@endsection
@section('js')
    <script type="text/javascript">
        function formatRepos(repo) {
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
        function formatReposSelection(repo) {
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
        };
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
                            all:'company',
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
            $("#user_id").select2({
                language: 'zh-CN',
                placeholder: "输入姓名、用户名等关键字搜索，选择用户",
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
                templateResult: formatRepos, // omitted for brevity, see the source of this page
                templateSelection: formatReposSelection // omitted for brevity, see the source of this page
            });
            $('a.action-show').click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                ActionModal(url,'show')
            });
            $('a.action-del').click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                var id = $(this).data('dynamic-id');
                mAppExtend.deleteData({
                    'url': url,
                    'callback': function () {
                        $("#dynamic-"+id).remove();
                    }
                });
            });
        });
    </script>
@endsection