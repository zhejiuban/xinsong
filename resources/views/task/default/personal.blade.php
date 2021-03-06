@extends('layouts.app')
@section('content')
    <div class="m-portlet">
        <form method="get" action="{{route('task.personal',['mid'=>request('mid')])}}"
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
                        <select name="status" class="form-control m-bootstrap-select" id="status">
                            <option value="">
                                所有状态
                            </option>
                            <option value="0" @if(request('status','') == 0 && request('status','') !== '') selected @endif>
                                进行中
                            </option>
                            <option value="1" @if(request('status') == 1) selected @endif>
                                已完成
                            </option>
                        </select>
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

    <div class="row">
        @foreach($list as $task)
        <div class="col-xl-6">
            <div class="m-portlet">
                <div class="m-portlet__body m-portlet__body--no-padding">
                    <div class="row m-row--no-padding m-row--col-separator-xl">
                        <div class="col-xl-12">
                            <div class="m-widget m--padding-20">
                                <div class="m-widget-body">
                                    <div class="m-section m-section-none">
                                        <h3 class="m-section__heading m-line-height-25">
                                            @if($task->status)
                                                <s><a class="look-task "  href="{{ route('tasks.show',['task'=>$task->id,'mid'=>request('mid')]) }}">
                                                        {{str_limit($task->content,50,'...')}}
                                                    </a></s>
                                            @else
                                                <a class="look-task" href="{{ route('tasks.show',['task'=>$task->id,'mid'=>request('mid')]) }}">
                                                    {{str_limit($task->content,50,'...')}}
                                                </a>
                                            @endif
                                            @if($task->is_need_plan && $task->project->plans->isEmpty() && check_project_leader($task->project,2))
                                            <br>
                                            <a href="{{ route('plans.index',['project'=>$task->project_id,'mid'=>'bd128edbfd250c9c5eff5396329011cd']) }}" >
                                                <span class="m-badge m-badge--danger m-badge--wide">
                                                    请及时填写项目实施计划，点击上传
                                                </span>
                                            </a> 
                                            @endif
                                        </h3>
                                        <span class="m-section__sub m-section__sub-margin-bottom-none">
                                            所属项目：{{$task->project->title}} <br>
                                            开始时间：{{$task->start_at}} <br>
                                            完成时间：{{$task->finished_at?$task->finished_at:'进行中'}}
                                            @if($task->is_need_plan)
                                                <br>
                                                实施计划：<a class="m-badge m-badge--brand m-badge--wide" href="{{ route('plans.index',['project'=>$task->project_id,'mid'=>request('mid')]) }}">查看</a>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet-action row">
                    <div class="btn-group m-btn-group col" role="group" aria-label="">
                        @if(!$task->status)
                            <a href="{{ route('tasks.finish',['task'=>$task->id,'mid'=>request('mid')])}}" class="m-btn--square m-btn--icon btn btn-secondary col m-btn--icon-center m-btn-left-bottom-border-none m-btn-right-bottom-border-none finish-task"  >
                                <span>
                                    <i class="la la-check-circle-o"></i>
                                    <span>完成任务</span>
                                </span>
                            </a>
                        @else
                            <a href="javascript:;" class="m-btn--square m-btn--icon btn btn-success col m-btn--icon-center  m-btn-right-bottom-border-none"  >
                                <span>
                                    <i class="la la-check"></i>
                                    <span>已完成</span>
                                </span>
                            </a>
                        @endif


                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>


    {{ $list->appends([
        'mid' => request('mid'),
        'project_id' => request('project_id'),
        'status' => request('status') !== null ? 0 : '',
    ])->links('vendor.pagination.bootstrap-4') }}
    <!--begin::Modal-->
    <div class="modal fade" id="_modal" tabindex="-1" role="dialog" aria-labelledby="_ModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!--end::Modal-->
    <!--begin::Modal-->
    <div class="modal fade" id="_editModal" tabindex="-1" role="dialog" aria-labelledby="_EditModalLabel"
         aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
            $('#status').selectpicker();
            $('.finish-task,.look-task').click(function(event) {
                event.preventDefault();
                var url = $(this).attr('href');
                ActionModal(url);
            });
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
        });
    </script>
@endsection