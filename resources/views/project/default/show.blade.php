@extends('layouts.app')

@section('content')
    @include('project.default.detail')
    <!-- 项目任务，动态 -->
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-6">
            <!--begin:: Widgets/Tasks -->
            <div class="m-portlet m-portlet--full-height">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                最新任务
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                                <a href="javascript:;" class="m-portlet__nav-link m-portlet__nav-link--icon m-portlet__nav-link--icon-xl m-dropdown__toggle">
                                    <i class="la la-ellipsis-h m--font-brand"></i>
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 21.5px;"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav">
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('tasks.create',['project_id'=>$project->id,'mid'=>request('mid')]) }}" id="task-add" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-add"></i>
                                                            <span class="m-nav__link-text">
                                                                发布任务
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('project.tasks',['project_id'=>$project->id,'mid'=>request('mid')]) }}" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-list"></i>
                                                            <span class="m-nav__link-text">
                                                                所有任务
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-scrollable" data-scrollable="true" data-max-height="400" style="height: 400px; overflow: hidden;">
                        <div class="m-widget2">
                            @foreach($project->tasks()->orderBy('status','asc')->orderBy('id','desc')->limit(10)->get() as $task)
                            <div id="task-{{$task->id}}" class="m-widget2__item m-widget2__item--{{tasks_status($task->status,'color')}}">
                                <div class="m-widget2__checkbox">
                                </div>
                                <div class="m-widget2__desc">
                                    <span class="m-widget2__text">
                                        <a href="{{ route('tasks.show',['task'=>$task->id,'mid' => request('mid')]) }}" class="task-look">{{$task->content}}</a> 
                                        <span class="m--font-size-12">({{$task->start_at}} ~ {{$task->end_at}})</span>
                                    </span>
                                    <br>
                                    <span class="m-widget2__user-name">
                                        负责人：{{$task->leaderUser->name}} 
                                        <a href="{{ route('tasks.edit',['task'=>$task->id])}}" class="fast-edit">编辑</a> 
                                        <a href="{{ route('tasks.destroy',['task'=>$task->id])}}" class="fast-del m--font-danger ">删除</a>
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Tasks -->
        </div>
        <div class="col-xl-6">
            <!--begin:: Widgets/Support Tickets -->
            <div class="m-portlet m-portlet--full-height">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                最新动态
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                                <a href="javascript:;" class="m-portlet__nav-link m-portlet__nav-link--icon m-portlet__nav-link--icon-xl m-dropdown__toggle">
                                    <i class="la la-ellipsis-h m--font-brand"></i>
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 21.5px;"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav">
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('dynamics.create',['project_id'=>$project->id]) }}" id="dynamic-add" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-add"></i>
                                                            <span class="m-nav__link-text">
                                                                发布动态
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('project.dynamics',['project_id'=>$project->id,'mid'=>request('mid')]) }}"  class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-list"></i>
                                                            <span class="m-nav__link-text">
                                                                所有动态
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-scrollable" data-scrollable="true" data-max-height="400" style="height: 400px; overflow: hidden;">
                        <div class="m-widget3">
                            @foreach($project->dynamics()->orderBy('id','desc')->limit(10)->get() as $dynamic)
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
                                            <a href="{{ route('dynamics.edit',['dynamic'=>$dynamic->id])}}" class="dynamic-edit">编辑</a> 
                                            <a href="{{ route('dynamics.destroy',['dynamic'=>$dynamic->id])}}" class="dynamic-del m--font-danger ">删除</a>
                                        </span>
                                    </div>
                                </div>
                                <div class="m-widget3__body">
                                    <p class="m-widget3__text">
                                        {{$dynamic->content}}
                                        <span class="m--font-primary m--font-size-12">
                                            (现场人员：{{$dynamic->onsite_user}})
                                        </span>
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Support Tickets -->
        </div>
    </div>
    <!--End::Main Portlet-->
    <!-- 协作，项目日志 -->
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-8">
            <div class="m-portlet m-portlet--full-height">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                协作交流
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                                    <a href="javascript:;" class="m-portlet__nav-link m-portlet__nav-link--icon m-portlet__nav-link--icon-xl m-dropdown__toggle">
                                        <i class="la la-plus m--hide"></i>
                                        <i class="la la-ellipsis-h m--font-brand"></i>
                                    </a>
                                    <div class="m-dropdown__wrapper">
                                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                        <div class="m-dropdown__inner">
                                            <div class="m-dropdown__body">
                                                <div class="m-dropdown__content">
                                                    <ul class="m-nav">
                                                        <li class="m-nav__item">
                                                            <a href="{{ route('questions.create',['project_id'=>$project->id,'mid'=>request('mid')]) }}" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-add"></i>
                                                                <span class="m-nav__link-text">
                                                                    发布协作
                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="{{ route('project.questions',['project'=>$project->id,'mid'=>request('mid')]) }}" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-list"></i>
                                                                <span class="m-nav__link-text">
                                                                    所有协作
                                                                </span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-scrollable" data-scrollable="true" data-max-height="400" style="height: 400px; overflow: hidden;">
                        <div class="m-widget5">
                            @foreach($project->questions()->orderBy('status','asc')->orderBy('id','desc')->limit(10)->get() as $question)
                            <div class="m-widget5__item" id="question-{{$question->id}}">
                                @if($question->file)
                                <a class="m-widget5__pic" href="{{asset($question->file[0]->path)}}" data-lightbox="roadtrip">
                                    <img class="m-widget7__img" src="{{asset($question->file[0]->path)}}" alt="">
                                </a>
                                @endif
                                <div class="m-widget5__content">
                                    <h4 class="m-widget5__title">
                                        {{$question->title}} 
                                    </h4>
                                    <span class="m-widget5__desc">
                                        {{str_limit($question->content,150,'...')}}
                                    </span>
                                    <div class="m-widget5__info">
                                        <span class="m-widget5__author">
                                            上报人: 
                                        </span>
                                        <span class="m-widget5__info-author-name">
                                            {{$question->user->name}}
                                        </span>
                                        <span class="m-widget5__info-label">
                                            时间:
                                        </span>
                                        <span class="m-widget5__info-date m--font-info">
                                            {{$question->created_at->diffForHumans()}}
                                        </span>
                                        <span class="m-widget5__info-action">
                                            <a href="{{ route('questions.edit',['question'=>$question->id,'mid'=>request('mid')])}}">编辑</a>
                                            <a href="{{ route('questions.destroy',['question'=>$question->id,'id'=>$question->id]) }}" class="m--font-danger question-del">删除</a>
                                            <a href="{{ route('questions.show',['question'=>$question->id]) }}" class="question-look">预览</a>
                                        </span>
                                    </div>
                                </div>
                                <div class="m-widget5__stats1">
                                    <span class="m-widget5__number">
                                        <span class="m-badge {{question_status($question->status,'class')}} m-badge--wide">{{question_status($question->status)}}</span>
                                    </span>
                                </div>
                                <div class="m-widget5__stats2">
                                    <span class="m-widget5__number">
                                        {{intval($question->click)}}
                                    </span>
                                    <br>
                                    <span class="m-widget5__votes">
                                        热度
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <!--begin:: Widgets/Audit Log-->
            <div class="m-portlet m-portlet--full-height">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                项目日志
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="m_widget4_tab1_content">
                            <div class="m-scrollable" data-scrollable="true" data-max-height="400" style="height: 400px; overflow: hidden;">
                                <div class="m-list-timeline m-list-timeline--skin-light min-height-400" id="logs-list" data-toggle="autoLoadHtml" data-target="#logs-list" href="{{ route('projects.logs',['id'=>$project->id]) }}" data-loading="#logs-list">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="m_widget4_tab2_content"></div>
                        <div class="tab-pane" id="m_widget4_tab3_content"></div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Audit Log-->
        </div>
    </div>
    <!--End::Main Portlet-->
    <!-- 相关文档，积分动态 -->
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-4">
            <!--begin:: Widgets/Download Files-->
            <div class="m-portlet m-portlet--full-height">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                相关文档
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item">
                                <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                                    <a href="javascript:;" class="m-portlet__nav-link m-portlet__nav-link--icon m-portlet__nav-link--icon-xl m-dropdown__toggle">
                                        <i class="la la-plus m--hide"></i>
                                        <i class="la la-ellipsis-h m--font-brand"></i>
                                    </a>
                                    <div class="m-dropdown__wrapper">
                                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                        <div class="m-dropdown__inner">
                                            <div class="m-dropdown__body">
                                                <div class="m-dropdown__content">
                                                    <ul class="m-nav">
                                                        <li class="m-nav__item">
                                                            <a id="file-add" href="{{ route('project.files.create',['project'=>$project->id,'mid'=>request('mid')]) }}" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-add"></i>
                                                                <span class="m-nav__link-text">
                                                                    上传文档
                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="{{ route('project.files',['project'=>$project->id,'mid'=>request('mid')]) }}" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-list"></i>
                                                                <span class="m-nav__link-text">
                                                                    所有文档
                                                                </span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-scrollable" data-scrollable="true" data-max-height="350" style="height: 350px; overflow: hidden;">
                    <!--begin::m-widget9-->
                    <div class="m-widget4">
                    @foreach($project->files()->orderBy('id','desc')->limit(10)->get() as $file)
                        <div class="m-widget4__item"  id="file-{{$file->id}}">
                            <div class="m-widget4__info m--padding-left-0">
                                <span class="m-widget4__text">
                                    {{$file->old_name}}
                                </span>
                            </div>
                            <div class="m-widget4__ext">
                                <a href="{{ route('file.download',['file'=>$file->uniqid]) }}" target="_blank" class="m-widget4__icon">
                                    <i class="la la-download"></i>
                                </a>
                            </div>
                            <div class="m-widget4__ext">
                                <a href="{{ route('project.files.destroy',['project'=>$project->id,'file'=>$file->id]) }}" title="删除" class="m-widget4__icon file-del">
                                    <i class="la la-trash"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                    </div>
                    <!--end::Widget 9-->
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Download Files-->
        </div>
        <div class="col-xl-4">
            <!--begin:: Widgets/New Users-->
            <div class="m-portlet m-portlet--full-height">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                参与人
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-scrollable" data-scrollable="true" data-max-height="350" style="height: 350px; overflow: hidden;">
                    <!--begin::Widget 14-->
                    <div class="m-widget4">
                        @foreach($project->users as $user)
                        <!--begin::Widget 14 Item-->
                        <div class="m-widget4__item">
                            <div class="m-widget4__img m-widget4__img--pic">
                                <img src="{{ avatar($user->avatar) }}" alt="">
                            </div>
                            <div class="m-widget4__info">
                                <span class="m-widget4__title">
                                    {{$user->name}}
                                </span>
                                <br>
                                <span class="m-widget4__sub">
                                    {{$user->department ? $user->department->name : null}}
                                </span>
                            </div>
                            <div class="m-widget4__ext">
                                {{--  <a href="javascript"  class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">
                                    +关注
                                </a>  --}}
                            </div>
                        </div>
                        <!--end::Widget 14 Item-->
                        @endforeach   
                    </div>
                    <!--end::Widget 14-->
                    </div>
                </div>
            </div>
            <!--end:: Widgets/New Users-->
        </div>
        <div class="col-xl-4">
            <!--begin:: Widgets/Authors Profit-->
            <div class="m-portlet m-portlet--full-height">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                积分动态
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-scrollable" data-scrollable="true" data-max-height="350" style="height: 350px; overflow: hidden;">
                        <div class="m-widget4">
                            <div class="m-widget4__item">
                                <div class="m-widget4__img m-widget4__img--logo">
                                    <img src="../../assets/app/media/img/users/default.jpg" alt="">
                                </div>
                                <div class="m-widget4__info">
                                    <span class="m-widget4__title">
                                        liu
                                    </span>
                                    <br>
                                    <span class="m-widget4__sub">
                                        发布动态
                                    </span>
                                </div>
                                <span class="m-widget4__ext">
                                    <span class="m-widget4__number m--font-brand">
                                        +2
                                    </span>
                                </span>
                            </div>
                            <div class="m-widget4__item">
                                <div class="m-widget4__img m-widget4__img--logo">
                                    <img src="../../assets/app/media/img/users/default.jpg" alt="">
                                </div>
                                <div class="m-widget4__info">
                                    <span class="m-widget4__title">
                                        管理员
                                    </span>
                                    <br>
                                    <span class="m-widget4__sub">
                                        上传文档
                                    </span>
                                </div>
                                <span class="m-widget4__ext">
                                    <span class="m-widget4__number m--font-brand">
                                        +2
                                    </span>
                                </span>
                            </div>
                            <div class="m-widget4__item">
                                <div class="m-widget4__img m-widget4__img--logo">
                                    <img src="../../assets/app/media/img/users/default.jpg" alt="">
                                </div>
                                <div class="m-widget4__info">
                                    <span class="m-widget4__title">
                                        liu
                                    </span>
                                    <br>
                                    <span class="m-widget4__sub">
                                        发布任务
                                    </span>
                                </div>
                                <span class="m-widget4__ext">
                                    <span class="m-widget4__number m--font-brand">
                                        +2
                                    </span>
                                </span>
                            </div>
                            <div class="m-widget4__item">
                                <div class="m-widget4__img m-widget4__img--logo">
                                    <img src="../../assets/app/media/img/users/default.jpg" alt="">
                                </div>
                                <div class="m-widget4__info">
                                    <span class="m-widget4__title">
                                        liu
                                    </span>
                                    <br>
                                    <span class="m-widget4__sub">
                                        删除动态
                                    </span>
                                </div>
                                <span class="m-widget4__ext">
                                    <span class="m-widget4__number m--font-danger">
                                        -2
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Authors Profit-->
        </div>
    </div>
    <!--End::Main Portlet-->
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
        <div class="modal-dialog modal-lg" role="document">
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
    var lookTask = function(url,type){
        $('#_modal').modal(type?type:'show');
        mAppExtend.ajaxGetHtml(
            '#_modal .modal-content',
            url,
            {},true);
    }
    //获取任务列表
    var getTasks = function(){
    }
    $(document).ready(function(){
        $('.task-look,#task-add,#dynamic-add,.fast-edit,.dynamic-edit,.question-look,#file-add').click(function(){
            event.preventDefault();
            var url = $(this).attr('href');
            lookTask(url);
        });
        $('.fast-del').click(function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            mAppExtend.deleteData({
                'url':url,
                callback:function(response, status, xhr){
                    if(response.data.id){
                        $("#task-"+response.data.id).fadeOut('slow');
                    }
                }
            });
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
        $('.question-del').click(function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            mAppExtend.deleteData({
                'url':url,
                callback:function(response, status, xhr){
                    if(response.data[0]){
                        $("#question-"+response.data[0]).fadeOut('slow');
                    }
                }
            });
        });
        $('.file-del').click(function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            mAppExtend.deleteData({
                'url':url,
                callback:function(response, status, xhr){
                    if(response.data.id){
                        $("#file-"+response.data.id).fadeOut('slow');
                    }
                }
            });
        });
    });
    </script>
@endsection
