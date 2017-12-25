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
                            <option value="">
                                所有状态
                            </option>
                            <option value="0">
                                未开始
                            </option>
                            <option value="1">
                                进行中
                            </option>
                            <option value="2">
                                已完成
                            </option>
                        </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group ">
                        <select name="type" class="form-control m-bootstrap-select" id="type">
                            <option value="0">
                                我参与的项目
                            </option>
                            <option value="1">
                                我负责的项目
                            </option>
                        </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group ">
                        <input type="text" name="search" class="form-control m-input" placeholder="关键字如编号、名称、客户联系人">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group ">
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
    @foreach($list as $project)
        <div class="m-portlet">
            <div class="m-portlet__body m-portlet__body--no-padding">
                <div class="row m-row--no-padding m-row--col-separator-xl">
                    <div class="col-xl-12">
                        <div class="m-widget m--padding-20">
                            <div class="m-widget-body">
                                <div class="m-section m-section-none">
                                    <h3 class="m-section__heading">
                                        <a class="m-line-height-25" href="{{ route('project.tasks',['project'=>$project->id,'mid'=>request('mid')]) }}">{{$project->title}}</a>
                                        @if($project->status == 1 && $phase = get_project_current_phase($project))
                                            <span class="m-badge {{project_status($phase->status,'class')}} m-badge--wide">
                                                {{$phase->name}} : {{project_status($phase->status)}}
                                            </span>
                                        @else
                                            <span class="m-badge {{project_status($project->status,'class')}} m-badge--wide">
                                                {{project_status($project->status)}}
                                            </span>
                                        @endif
                                    </h3>
                                    <span class="m-section__sub">
                                    {{str_limit($project->remark,250,'...')}}
                                    </span>
                                </div>
                            </div>
                            <div class="m-widget__action ">
                                @if($task = $project->checkUserTaskDayDynamic())
                                    <div class="alert alert-warning alert-dismissible fade show   m-alert m-alert--square m-alert--air"
                                         role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                        <strong>温馨提醒：</strong>
                                        该项目今日您未上传日志
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet-action row">
                <div class="btn-group m-btn-group col" role="group" aria-label="">
                    <a href="{{ route('dynamics.create',['project_id'=>$project->id,'task_id'=>$task,'mid'=>request('mid')]) }}"
                       class="m-btn-left-bottom-border-none m-btn
                            m-btn--square btn btn-secondary col m-btn--icon
                            m-btn--icon-center dynamic-add @if(!$project->checkUserTaskDayDynamic()) disabled @endif">
                        <span>
                            <i class="la la-edit"></i>
                            <span>上传日志</span>
                        </span>
                    </a>
                    @if(check_project_owner($project,'del'))
                    <a class="m-btn-bottom-border-none m-btn
                            m-btn--square m-btn--icon btn btn-secondary
                            col m-btn--icon-center task-add @if(!check_project_owner($project,'del')) disabled @endif"
                       href="{{ route('tasks.create',['project_id'=>$project->id]) }}">
                        <span>
                            <i class="la la-plus"></i>
                            <span>发布任务</span>
                        </span>
                    </a>
                    @endif
                    <div class="btn-group" >
                        <button type="button" class="m-btn--square m-btn--icon btn btn-secondary
                            col m-btn--icon-center dropdown-toggle m-btn-right-bottom-border-none" data-toggle="dropdown" data-dropdown-toggle="hover" aria-expanded="true" aria-haspopup="true" >
                            更多
                        </button>
                        <div class="dropdown-menu" x-placement="top-start">
                            <a href="{{ route('project.tasks',['project_id'=>$project->id,'mid'=>request('mid')]) }}" class="dropdown-item">
                                <i class="flaticon-share"></i>
                                所有任务
                            </a>
                            <a href="{{ route('project.tasks',['project_id'=>$project->id,'only'=>1,'mid'=>request('mid')]) }}" class="dropdown-item">
                                <i class="flaticon-user"></i>
                                只看我的任务 <span class="m-badge m-badge--brand">{{$project->tasks()->where('leader',get_current_login_user_info())->count()}}</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('project.dynamics',['project_id'=>$project->id,'mid'=>request('mid')]) }}" class="dropdown-item">
                                <i class="flaticon-list"></i>
                                所有日志
                            </a>
                            <a href="{{ route('project.dynamics',['project_id'=>$project->id,'only'=>1,'mid'=>request('mid')]) }}" class="dropdown-item">
                                <i class="flaticon-user"></i>
                                只看我的日志 <span class="m-badge m-badge--brand">{{$project->dynamics()->where('user_id',get_current_login_user_info())->count()}}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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
        }
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