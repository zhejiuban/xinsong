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
                            <li class="nav-item m-tabs__item">
                                <a href="{{ route('project.tasks',['project'=>$project->id,'mid'=>request('mid')]) }}" class="nav-link m-tabs__link  ">
                                    任务
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-center" data-dropdown-toggle="hover" aria-expanded="true">
                                <a href="{{ route('project.dynamics',['project'=>$project->id,'mid'=>request('mid')]) }}" class="active nav-link m-tabs__link  m-dropdown__toggle dropdown-toggle">
                                    日志
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--center"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav">
                                                    @if($project->checkUserTask() && !$project->checkDayLog())
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('dynamics.create',['project_id'=>$project->id,'mid'=>request('mid')]) }}" class="m-nav__link dynamic-add">
                                                            <i class="m-nav__link-icon flaticon-add"></i>
                                                            <span class="m-nav__link-text">
                                                                上传日志
                                                            </span>
                                                        </a>
                                                    </li>
                                                    @endif
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('project.dynamics',['project_id'=>$project->id,'mid'=>request('mid')]) }}" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-list"></i>
                                                            <span class="m-nav__link-text">
                                                                所有日志
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('project.dynamics',['project_id'=>$project->id,'only'=>1,'mid'=>request('mid')]) }}" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-user"></i>
                                                            <span class="m-nav__link-text">
                                                                只看自己的
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__separator m-nav__separator--fit"></li>
                                                    <li class="m-nav__item">
                                                        <a href="#" class="btn btn-outline-primary m-btn m-btn--pill m-btn--wide btn-sm">
                                                            日
                                                        </a>
                                                        <a href="#" class="btn btn-outline-primary m-btn m-btn--pill m-btn--wide btn-sm">
                                                            周
                                                        </a>
                                                        <a href="#" class="btn btn-outline-primary m-btn m-btn--pill m-btn--wide btn-sm">
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
                                <a href="{{ route('project.questions',['project'=>$project->id,'mid'=>request('mid')]) }}" class="nav-link m-tabs__link" >
                                    协作
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a href="{{ route('project.files',['project'=>$project->id,'mid'=>request('mid')]) }}" class="nav-link m-tabs__link ">
                                    文档
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body min-height-300">
                    @if($project->checkUserTask() && !$project->checkDayLog())
                        <div class="alert alert-warning alert-dismissible fade show   m-alert m-alert--square m-alert--air"
                             role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                            <strong>温馨提醒：</strong>
                            该项目今日您未上传日志
                        </div>
                    @endif
                    <div class="m-widget3">
                        @foreach($dynamics as $dynamic)
                            <div class="m-widget3__item" id="dynamic-{{$dynamic->id}}">
                                <div class="m-widget3__header">
                                    <div class="m-widget3__user-img">
                                        <img class="m-widget3__img" src="{{avatar($dynamic->user->avatar)}}" alt="">
                                    </div>
                                    <div class="m-widget3__info">
                                        <span class="m-widget3__username">
                                            {{$dynamic->user->name}}
                                        </span>
                                        <br>
                                        <span class="m-widget3__time">
                                            {{$dynamic->created_at->diffForHumans()}}
                                            @if(check_project_owner($dynamic->project,'edit') || $dynamic->user->id == get_current_login_user_info())
                                            <a href="{{ route('dynamics.edit',['dynamic'=>$dynamic->id,'mid'=>request('mid')]) }}" class="dynamic-edit">编辑</a>
                                            @endif
                                            @if(check_project_owner($dynamic->project,'edit'))
                                            <a href="{{ route('dynamics.destroy',['dynamic'=>$dynamic->id,'mid'=>request('mid')]) }}" class="dynamic-del m--font-danger">删除</a>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="m-widget3__body">
                                    <p class="m-widget3__text">
                                        {{$dynamic->content}}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{ $dynamics->appends(['mid' => request('mid')])->fragment('lists')->links('vendor.pagination.bootstrap-4') }}
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
    <div class="modal fade" id="_editModal" tabindex="-1" role="dialog" aria-labelledby="_EditModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!--end::Modal-->
    <!--begin::Modal-->
    <div class="modal fade" id="_planModal" tabindex="-1" role="dialog" aria-labelledby="_planModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lgx" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!--end::Modal-->
@endsection

@section('js')
    @component('project.default.finished_precent',['project'=>$project])
    @endcomponent()
    <script type="text/javascript">
        var ActionModal = function(url,type){
            $('#_modal').modal(type?type:'show');
            mAppExtend.ajaxGetHtml(
                '#_modal .modal-content',
                url,
                {},true);
        }
        $(document).ready(function(){
            $('.dynamic-add,.dynamic-edit').click(function(event) {
                event.preventDefault();
                var url = $(this).attr('href');
                ActionModal(url);
            });
            $('.dynamic-del').click(function(event) {
                event.preventDefault();
                var url = $(this).attr('href');
                mAppExtend.deleteData({
                    'url':url,
                    callback:function(response, status, xhr){
                        if(response.data.id){
                            $("#dynamic-"+response.data.id).fadeOut('slow');
                        }
                    }
                });
            });
        });

    </script>
@endsection
