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
                            <li class="nav-item m-tabs__item">
                                <a href="{{ route('project.questions',['project'=>$project->id,'mid'=>request('mid')]) }}" class="nav-link m-tabs__link ">
                                    协作
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right " data-dropdown-toggle="hover" aria-expanded="true">
                                <a href="{{ route('project.files',['project'=>$project->id,'mid'=>request('mid')]) }}" class="nav-link m-tabs__link active m-dropdown__toggle dropdown-toggle">
                                    文档
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav">
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('project.files.create',['project'=>$project->id,'mid'=>request('mid')]) }}" id="file-add" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-add"></i>
                                                            <span class="m-nav__link-text">
                                                                上传文档
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
                                <a href="{{ route('project.users',['project'=>$project->id,'mid'=>request('mid')]) }}" class="nav-link m-tabs__link ">
                                    参与人
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body min-height-100">
                    <div class="m-widget4">
                        @foreach($files as $file)
                            <div class="m-widget4__item" id="file-{{$file->id}}">
                                <div class="m-widget4__info m--padding-left-0">
                                    <span class="m-widget4__text">
                                        <a href="{{ route('file.download',['file'=>$file->uniqid]) }}" title="下载" target="_blank" >{{$file->old_name}}</a>
                                         (上传人：{{$file->user->name}} 时间：{{$file->created_at->diffForHumans()}})
                                    </span>
                                </div>
                                <div class="m-widget4__ext">
                                    <a href="{{ route('file.download',['file'=>$file->uniqid]) }}" title="下载" target="_blank" class="m-widget4__icon">
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
                    {{ $files->appends(['mid' => request('mid')])->fragment('lists')->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
            <!--end:: Widgets/Tasks -->
        </div>
    </div>
    <!--begin::Modal-->
    <div class="modal fade" id="_modal" tabindex="-1" role="dialog" aria-labelledby="_ModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
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
        var modalFile = function(url,type){
            $('#_modal').modal(type?type:'show');
            mAppExtend.ajaxGetHtml(
                '#_modal .modal-content',
                url,
                {},true);
        }
        $(document).ready(function(){
            $('#file-add').click(function(event){
                event.preventDefault();
                var url = $(this).attr('href');
                modalFile(url);
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
