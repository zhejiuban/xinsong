@extends('layouts.app')

@section('content')
    <!-- 基本信息 -->
    @include('project.default.detail')
    <!-- 项目任务，动态 -->
    <!--Begin::Main Portlet-->
    <div class="row" id="lists">
        <div class="col-xl-12">
            <!--begin:: Widgets/Tasks -->
            <div class="m-portlet m-portlet--full-height  m-portlet--tabs">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x" role="tablist">
                            <li class="nav-item m-tabs__item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-left"
                                data-dropdown-toggle="hover" aria-expanded="true">
                                <a href="{{ route('project.tasks',['project'=>$project->id,'mid'=>request('mid')]) }}"
                                   class="nav-link m-tabs__link active m-dropdown__toggle dropdown-toggle">
                                    任务
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--left"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav">
                                                    @if(check_project_owner($project,'edit') || check_project_leader($project))
                                                        <li class="m-nav__item">
                                                            <a href="{{ route('tasks.create',['project_id'=>$project->id,'mid'=>request('mid')]) }}"
                                                               id="task-add" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-add"></i>
                                                                <span class="m-nav__link-text">
                                                                发布任务
                                                            </span>
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('project.tasks',['project_id'=>$project->id,'mid'=>request('mid')]) }}"
                                                           class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-list"></i>
                                                            <span class="m-nav__link-text">
                                                            所有任务
                                                        </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('project.tasks',['project_id'=>$project->id,'only'=>1,'mid'=>request('mid')]) }}"
                                                           class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-user"></i>
                                                            <span class="m-nav__link-text">
                                                                只看我的任务
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__separator m-nav__separator--fit"></li>
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('project.tasks',['project_id'=>$project->id,'date'=>'day','mid'=>request('mid')]) }}"
                                                           class="btn btn-outline-primary m-btn m-btn--pill m-btn--wide btn-sm">
                                                            日
                                                        </a>
                                                        <a href="{{ route('project.tasks',['project_id'=>$project->id,'date'=>'week','mid'=>request('mid')]) }}"
                                                           class="btn btn-outline-primary m-btn m-btn--pill m-btn--wide btn-sm">
                                                            周
                                                        </a>
                                                        <a href="{{ route('project.tasks',['project_id'=>$project->id,'date'=>'month','mid'=>request('mid')]) }}"
                                                           class="btn btn-outline-primary m-btn m-btn--pill m-btn--wide btn-sm">
                                                            月
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a href="{{ route('project.dynamics',['project'=>$project->id,'mid'=>request('mid')]) }}"
                                   class="nav-link m-tabs__link">
                                    日志
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a href="{{ route('project.questions',['project'=>$project->id,'mid'=>request('mid')]) }}"
                                   class="nav-link m-tabs__link">
                                    协作
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a href="{{ route('project.files',['project'=>$project->id,'mid'=>request('mid')]) }}"
                                   class="nav-link m-tabs__link ">
                                    文档
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body min-height-300">
                    <div class="m-widget2">
                        @foreach($tasks as $task)
                            <div id="task-{{ $task->id }}"
                                 class="m-widget2__item m-widget2__item--{{tasks_status($task->status,'color')}}">
                                <div class="m-widget2__checkbox">
                                    {{--<label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand">--}}
                                        {{--<input type="checkbox">--}}
                                        {{--<span></span>--}}
                                    {{--</label>--}}
                                </div>
                                <div class="m-widget2__desc">
                                <span class="m-widget2__text">

                                    @if($task->status)
                                        <s><a href="{{ route('tasks.show',['task'=>$task->id,'mid' => request('mid')]) }}"
                                              class="task-look">{{$task->content}}</a></s>
                                    @else
                                        <a href="{{ route('tasks.show',['task'=>$task->id,'mid' => request('mid')]) }}"
                                           class="task-look">{{$task->content}}</a>
                                    @endif
                                    <span class="m--font-size-12">(开始时间：{{$task->start_at}} @if($task->status) 完成时间：{{$task->finished_at}} @endif )</span>
                                </span>
                                    <br>
                                    <span class="m-widget2__user-name">
                                    接收人：{{$task->leaderUser->name}} 接收时间：{{$task->received_at}}
                                        @if(check_project_owner($project,'edit'))
                                        @if(!$task->status)
                                        <a href="{{ route('tasks.edit',['task'=>$task->id])}}" class="fast-edit">编辑</a>
                                        @endif
                                        <a href="{{ route('tasks.destroy',['task'=>$task->id])}}"
                                           class="fast-del m--font-danger">删除</a>
                                        @endif
                                </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{ $tasks->appends([
                        'mid' => request('mid'),
                        'only'=>request('only')
                        ])->fragment('lists')->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
            <!--end:: Widgets/Tasks -->
        </div>
    </div>
    <!--begin::Modal-->
    <div class="modal fade" id="_modal" tabindex="-1" role="dialog" aria-labelledby="_ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!--end::Modal-->
    <!--begin::Modal-->
    <div class="modal fade" id="_editModal" tabindex="-1" role="dialog" aria-labelledby="_EditModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!--end::Modal-->
    <!--begin::Modal-->
    <div class="modal fade" id="_planModal" tabindex="-1" role="dialog" aria-labelledby="_planModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lgx" role="document">
            <div class="modal-content">
                aa
            </div>
        </div>
    </div>
    <!--end::Modal-->
@endsection

@section('js')
    @component('project.default.finished_precent',['project'=>$project])
    @endcomponent()
    <script type="text/javascript">
        var lookTask = function (url, type) {
            $('#_modal').modal(type ? type : 'show');
            mAppExtend.ajaxGetHtml(
                '#_modal .modal-content',
                url,
                {}, true);
        };
        $(document).ready(function () {
            $('.task-look,#task-add,.fast-edit').click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                lookTask(url);
            });
            $('.fast-del').click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                mAppExtend.deleteData({
                    'url': url,
                    callback: function (response, status, xhr) {
                        if (response.data.id) {
                            $("#task-" + response.data.id).fadeOut('slow');
                        }
                    }
                });
            });
        });
    </script>
@endsection
