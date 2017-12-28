@extends('layouts.app')
@section('content')
    <div class="m-portlet">
        <form method="get" action="{{route('project.personal',['mid'=>request('mid')])}}"
              class="m-form m-form--fit m-form--group-seperator-dashed m-form-group-padding-bottom-10">
            <div class="m-portlet__body ">
                <div class="row m-form__group">
                    <div class="col-lg-3">
                        <div class="form-group ">
                        <select name="status" class="form-control m-bootstrap-select" id="status">
                            {!! project_status_select(request('status','')) !!}
                        </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group ">
                        <select name="type" class="form-control m-bootstrap-select" id="type">
                            <option value="0" @if(!request('type')) selected @endif >
                                我参与的项目
                            </option>
                            <option value="1"  @if(request('type')) selected @endif>
                                我负责的项目
                            </option>
                        </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group ">
                        <input type="text" name="search" value="{{request('search','')}}" class="form-control m-input" placeholder="关键字如编号、名称、客户联系人">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group ">
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

    @if(get_current_login_user_info(true)->leaderTasks()
                ->needAddDynamic(current_date())->doesntHaveDynamic(current_date())->get()->isNotEmpty())
        <div class="m-alert m-alert--icon m-alert--air m-alert--square alert alert-dismissible fade show alert-warning" role="alert">
            <div class="m-alert__icon">
                <i class="la la-warning"></i>
            </div>
            <div class="m-alert__text">
                <strong>
                    警告：
                </strong>
                您今日有日志未上传，请尽快上传
            </div>
            <div class="m-alert__close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div class="row">
        @foreach($list as $project)
        <div class="col-xl-6">
            <div class="m-portlet">
                <div class="m-portlet__body m-portlet__body--no-padding">
                    <div class="row m-row--no-padding m-row--col-separator-xl">
                        <div class="col-xl-12">
                            <div class="m-widget m--padding-20">
                                <div class="m-widget-body">
                                    <div class="m-section m-section-none">
                                        <h3 class="m-section__heading">
                                            <a class="m-line-height-25" href="{{ route('project.board',['project'=>$project->id,'mid'=>request('mid')]) }}"
                                            title="{{$project->title}}">{{str_limit($project->title,24,'...')}}</a>
                                            @if($project->status == 1 && $phase = get_project_current_phase($project))
                                            <span class="m-badge {{project_status($phase->status,'class')}} m-badge--wide pull-right">
                                                {{$phase->name}} : {{project_status($phase->status)}}
                                            </span>
                                            @else
                                            <span class="m-badge {{project_status($project->status,'class')}} m-badge--wide pull-right">
                                            {{project_status($project->status)}}
                                            </span>
                                            @endif
                                        </h3>
                                        <span class="m-section__sub">
                                        {{str_limit($project->remark,240,'...')}}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet-action row">
                    @php $task = $project->checkUserTaskDayDynamic() @endphp
                    <div class="btn-group m-btn-group col" role="group" aria-label="">
                        <a href="{{ route('dynamics.create',['project_id'=>$project->id,'task_id'=>$task,'mid'=>request('mid')]) }}"
                           class="m-btn-left-bottom-border-none m-btn
                            m-btn--square btn btn-secondary col m-btn--icon
                            m-btn--icon-center dynamic-add @if(!$task) disabled @endif">
                            <span>
                                <i class="la la-plus"></i>
                                <span>
                                    上传日志
                                    @if($task) <span class="m-badge m-badge--danger m-badge--dot"></span>@endif
                                </span>
                            </span>
                        </a>
                        @if(check_project_leader($project))
                            <a class="m-btn-bottom-border-none m-btn
                            m-btn--square m-btn--icon btn btn-secondary
                            col m-btn--icon-center task-add"
                               href="{{ route('tasks.create',['project_id'=>$project->id]) }}">
                                <span>
                                <i class="la la-edit"></i>
                                    <span>
                                        发布任务
                                    </span>
                                </span>
                            </a>
                        @endif
                        <a href="{{ route('project.board',['project'=>$project->id,'mid'=>request('mid')]) }}"
                           class="m-btn--square m-btn--icon btn btn-secondary
                        col m-btn--icon-center  m-btn-right-bottom-border-none"  >
                        <span>
                            <i class="la la-eye"></i>
                            <span>项目详情</span>
                        </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    {{ $list->appends([
        'mid' => request('mid'),
        'status' => request('status') !== null ? 0 : '',
        'type' => request('type'),
        'search' => request('search'),
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
        var ActionModal = function (url, type) {
            $('#_modal').modal(type ? type : 'show');
            mAppExtend.ajaxGetHtml(
                '#_modal .modal-content',
                url,
                {}, true);
        };
        $(document).ready(function () {
            $('#type,#status').selectpicker();
            $('.dynamic-add,.task-add').click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                ActionModal(url);
            });
        });
    </script>
@endsection