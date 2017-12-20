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
                                <a href="{{ route('project.tasks',['project'=>$project->id,'mid'=>request('mid')]) }}" class="nav-link m-tabs__link">
                                    任务
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a href="{{ route('project.dynamics',['project'=>$project->id,'mid'=>request('mid')]) }}" class="nav-link m-tabs__link" >
                                    日志
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-center" data-dropdown-toggle="hover" aria-expanded="true">
                                <a href="{{ route('project.questions',['project'=>$project->id,'mid'=>request('mid')]) }}" class="nav-link m-tabs__link active m-dropdown__toggle dropdown-toggle">
                                    问题
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--center"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav">
                                                    {{--<li class="m-nav__item">--}}
                                                        {{--<a href="{{ route('questions.create',['project_id'=>$project->id,'mid'=>request('mid')]) }}" id="question-add" class="m-nav__link">--}}
                                                            {{--<i class="m-nav__link-icon flaticon-add"></i>--}}
                                                            {{--<span class="m-nav__link-text">--}}
                                                                {{--发布问题--}}
                                                            {{--</span>--}}
                                                        {{--</a>--}}
                                                    {{--</li>--}}
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('project.questions',['project_id'=>$project->id,'mid'=>request('mid')]) }}" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-list"></i>
                                                            <span class="m-nav__link-text">
                                                                查看所有的
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('project.questions',['project_id'=>$project->id,'only'=>1,'mid'=>request('mid')]) }}" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-user"></i>
                                                            <span class="m-nav__link-text">
                                                                只看我的
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__separator m-nav__separator--fit"></li>
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('project.questions',['project_id'=>$project->id,'date'=>'day','mid'=>request('mid')]) }}" class="btn btn-outline-primary m-btn m-btn--pill m-btn--wide btn-sm">
                                                            日
                                                        </a>
                                                        <a href="{{ route('project.questions',['project_id'=>$project->id,'date'=>'week','mid'=>request('mid')]) }}" class="btn btn-outline-primary m-btn m-btn--pill m-btn--wide btn-sm">
                                                            周
                                                        </a>
                                                        <a href="{{ route('project.questions',['project_id'=>$project->id,'date'=>'month','mid'=>request('mid')]) }}" class="btn btn-outline-primary m-btn m-btn--pill m-btn--wide btn-sm">
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
                                <a href="{{ route('project.files',['project'=>$project->id,'mid'=>request('mid')]) }}" class="nav-link m-tabs__link ">
                                    文档
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a href="{{ route('project.users',['project'=>$project->id,'mid'=>request('mid')]) }}" class="nav-link m-tabs__link ">
                                    参与人
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body min-height-300">
                    <div class="m-actions">

                        <a href="{{ route('questions.create',['project_id'=>$project->id,'mid'=>request('mid')]) }}" id="question-add"
                           class="m-nav__link  btn btn-sm btn-primary  m--margin-bottom-20">
                            <i class="m-nav__link-icon flaticon-add"></i>
                            <span class="m-nav__link-text">
                                发布问题
                            </span>
                        </a>

                        <a href="{{ route('project.questions',['project_id'=>$project->id,'mid'=>request('mid')]) }}"
                           class="m-nav__link  btn btn-sm btn-secondary  m--margin-bottom-20">
                            <i class="m-nav__link-icon flaticon-list"></i>
                            <span class="m-nav__link-text">
                                所有的问题
                            </span>
                        </a>

                        <a href="{{ route('project.questions',['project_id'=>$project->id,'only'=>1,'mid'=>request('mid')]) }}"
                           class="m-nav__link  btn btn-sm btn-secondary  m--margin-bottom-20">
                            <i class="m-nav__link-icon flaticon-user"></i>
                            <span class="m-nav__link-text">
                                只看我的问题
                            </span>
                        </a>

                    </div>
                    <div class="m-widget5">
                        @foreach($questions as $question)
                        <div id="question-{{$question->id}}" class="m-widget5__item">
                            <div class="m-widget5__content m--padding-left-none" style="padding-left: 0;">
                                <h4 class="m-widget5__title">
                                    {{$question->title}}
                                </h4>
                                {{--<span class="m-widget5__desc">
                                    {{str_limit($question->content,150,'...')}}
                                </span>--}}
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
                                    <span class="m-widget5__info-action ">
                                        @if(check_project_owner($project,'del') || (!$question->status && $question->user_id == get_current_login_user_info()))
                                        <a href="{{ route('questions.edit',['question'=>$question->id,'mid'=>request('mid')])}}"
                                        class="btn btn-outline-primary m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill">
                                            <i class="la la-edit"></i>
                                        </a>
                                        <a href="{{ route('questions.destroy',['question'=>$question->id,'id'=>$question->id]) }}"
                                           class="btn btn-outline-danger m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill question-del">
                                            <i class="la la-trash"></i></a>
                                        @endif

                                        @if(($question->status < 3) && $question->receive_user_id == get_current_login_user_info())
                                        <a href="{{ route('questions.reply',['question'=>$question->id,'mid'=>request('mid')]) }}"  title="回复"
                                           class="question-reply btn btn-outline-primary m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill">
                                            <i class="la la-reply"></i></a>
                                        @else
                                            <a href="{{ route('questions.show',['question'=>$question->id]) }}"
                                               class="question-look btn btn-outline-info m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill">
                                        <i class="la la-eye"></i></a>
                                        @endif
                                        @if((check_project_owner($project,'del') || $question->user_id == get_current_login_user_info()) && $question->status <= 2)
                                            <a href="{{ route('questions.finished',['question'=>$question->id]) }}" title="关闭"
                                               class="question-close btn btn-outline-danger m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill">
                                            <i class="la la-power-off"></i></a>
                                        @endif
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
                    {{ $questions->appends(['mid' => request('mid'),'only' => request('only')])->fragment('lists')->links('vendor.pagination.bootstrap-4') }}
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
        <div class="modal-dialog modal-lg" role="document">
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
        var lookQuestion = function(url,type){
            $('#_modal').modal(type?type:'show');
            mAppExtend.ajaxGetHtml(
                '#_modal .modal-content',
                url,
                {},true);
        }
        $(document).ready(function(){
            $('.question-look,.question-reply').click(function(event){
                event.preventDefault();
                var url = $(this).attr('href');
                lookQuestion(url);
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
            $('.question-close').click(function(event) {
                event.preventDefault();
                var url = $(this).attr('href');
                mAppExtend.confirmControllData({
                    'title':'你确定要关闭问题吗？',
                    'url':url,
                    'data':{_method:'POST'}
                });
            });
        });
    </script>
@endsection
