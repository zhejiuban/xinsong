@extends('layouts.app')

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-interface-7"></i>
                </span>
                    <h3 class="m-portlet__head-text m--font-primary">
                        任务日志详情
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
        <form method="get" action="{{route('task.dynamics',['task'=>request('task'),'mid'=>request('mid')])}}"
              class="m-form m-form--fit m-form--group-seperator-dashed m-form-group-padding-bottom-10">
            <div class="m-portlet__body ">
                <div class=" m-form__group row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <b class=" m--font-brand">
                                任务内容：{{$task->content}} <br>
                            </b>
                            执行人：{{$task->leaderUser?$task->leaderUser->name : null}} ，开始时间：{{$task->start_at}}<br>
                            所属项目：{{$task->project ? $task->project->title : null}}
                        </div>
                    </div>
                </div>
                <div class=" m-form__group row">
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
                                    {{str_limit($task->content,50,'...')}}
                                </a>
                                @if(check_project_owner($task->project,'company'))
                                    <a href="{{route("dynamics.destroy",['dynamic'=>$task->id,'mid'=>request('mid')])}}"
                                       class="btn btn-outline-danger m-btn m-btn--icon btn-sm m-btn--icon-only
                                  m-btn--pill pull-right action-del" data-dynamic-id="{{$task->id}}"><i class="la la-trash"></i></a>
                                @endif
                            </h3>
                            <span class="m-section__sub m-section__sub-margin-bottom-none">
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
            'date' => request('date'),
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
        jQuery(document).ready(function () {
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
        })
    </script>
@endsection