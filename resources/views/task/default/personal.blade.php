@extends('layouts.app')
@section('content')
    <div class="m-portlet">
        <form method="get" action="{{route('task.personal',['mid'=>request('mid')])}}"
              class="m-form m-form--fit m-form--group-seperator-dashed">
            <div class="m-portlet__body ">
                <div class="form-group m-form__group row">
                    <div class="col-lg-3">
                        <select name="status" class="form-control m-bootstrap-select" id="status">
                            <option value="">
                                所有状态
                            </option>
                            <option value="0">
                                进行中
                            </option>
                            <option value="1">
                                已完成
                            </option>
                        </select>
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
    @foreach($list as $task)
        <div class="m-portlet">
            <div class="m-portlet__body">

                <div class="m-widget">
                    <div class="m-widget-body">
                        <div class="m-section m-section-none">
                            <h3 class="m-section__heading">
                                <span class="m-badge {{tasks_status($task->status,'class')}} m-badge--wide">
                                    {{tasks_status($task->status)}}
                                </span>
                                @if($task->status)
                                    <s><a class="look-task m-line-height-25"  href="{{ route('tasks.show',['task'=>$task->id,'mid'=>request('mid')]) }}">{{$task->content}}</a></s>
                                @else
                                    <a class="look-task m-line-height-25" href="{{ route('tasks.show',['task'=>$task->id,'mid'=>request('mid')]) }}">{{$task->content}}</a>
                                @endif
                            </h3>
                            <span class="m-section__sub">
                                所属项目：{{$task->project->title}} <br>
                                开始时间：{{$task->start_at}} ,
                                接收人：{{$task->leaderUser ? $task->leaderUser->name : null}}
                            </span>
                        </div>
                    </div>
                    <div class="m-widget__action m--margin-top-20">
                        <a href=""
                           class="btn m-btn--pill  btn-sm btn-secondary ">
                            <i class="fa fa-edit"></i> 计划管理
                        </a>
                        @if(!$task->status)
                        <a href="{{ route('tasks.finish',['task'=>$task->id,'mid'=>request('mid')])}}"
                           class="btn m-btn--pill  btn-sm  btn-accent finish-task">
                            <i class="fa fa-check"></i> 完成任务
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{ $list->appends([
        'mid' => request('mid'),
        'status' => request('status') !== null ? 0 : '',
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
            $('#status').selectpicker();
            $('.finish-task,.look-task').click(function(event) {
                event.preventDefault();
                var url = $(this).attr('href');
                ActionModal(url);
            });
        });
    </script>
@endsection