@extends('layouts.app')
@section('content')
    <div class="m-portlet">
        <form method="get" action="{{route('project.personal',['mid'=>request('mid')])}}"
              class="m-form m-form--fit m-form--group-seperator-dashed">
            <div class="m-portlet__body ">
                <div class="form-group m-form__group row">
                    <div class="col-lg-3">
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
                    <div class="col-lg-3">
                        <select name="type" class="form-control m-bootstrap-select" id="type">
                            <option value="0">
                                我参与的项目
                            </option>
                            <option value="1">
                                我负责的项目
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <input type="text" name="search" class="form-control m-input" placeholder="关键字如编号、名称、客户联系人">
                    </div>
                    <div class="col-lg-3">
                        <input type="hidden" name="mid" value="{{request('mid')}}">
                        <button type="submit" class="btn btn-brand  m-btn--pill">
                            查询
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @foreach($list as $project)
        <div class="m-portlet">
            <div class="m-portlet__body">

                <div class="m-widget">
                    <div class="m-widget-body">
                        <div class="m-section m-section-none">
                            <h3 class="m-section__heading">
                                 <span class="m-badge {{project_status($project->status,'class')}} m-badge--wide">
                                    {{project_status($project->status)}}
                                </span>
                                <a class="m-line-height-25" href="{{ route('project.tasks',['project'=>$project->id,'mid'=>request('mid')]) }}">{{$project->title}}</a>
                            </h3>
                            <span class="m-section__sub">
                            {{str_limit($project->remark,250,'...')}}
                            </span>
                            <span class="m-section__sub">
                            编号：{{$project->no}}
                                客户联系人：{{$project->customers}} 电话：{{$project->customers_tel}}
                                地址：{{$project->customers_address}}
                            </span>
                        </div>
                    </div>
                    <div class="m-widget__action m--margin-top-20">
                        @if($project->checkUserTask() && !$project->checkDayLog())
                            <div class="alert alert-warning alert-dismissible fade show   m-alert m-alert--square m-alert--air"
                                 role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                <strong>温馨提醒：</strong>
                                该项目今日您未上传日志
                            </div>
                            <a href="{{ route('dynamics.create',['project_id'=>$project->id,'mid'=>request('mid')]) }}"
                               class="btn m-btn--pill  btn-sm  btn-accent dynamic-add">
                                <i class="fa fa-edit"></i> 填写日志
                            </a>
                        @endif
                        <a href="{{ route('project.dynamics',['project_id'=>$project->id,'mid'=>request('mid')]) }}" class="btn m-btn--pill  btn-sm  btn-secondary">
                            <i class="fa fa-eye"></i> 查看所有日志</a>
                        @if(check_project_leader($project))
                            <a href="{{ route('tasks.create',['project_id'=>$project->id]) }}"
                               class="btn m-btn--pill btn-sm btn-accent task-add">
                                <i class="fa fa-plus"></i> 发布任务
                            </a>
                        @endif
                        <a href="{{ route('project.tasks',['project_id'=>$project->id,'mid'=>request('mid')]) }}" class="btn m-btn--pill  btn-sm  btn-secondary">
                            <i class="fa fa-list"></i> 查看所有任务
                            {{--<span class="m-badge m-badge--metal">{{$project->tasks()->count()}}</span>--}}
                        </a>
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