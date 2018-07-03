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
                    <div class="m-portlet__head-tools project-tab">
                        <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary " role="tablist">
                            <li class="nav-item m-tabs__item ">
                                <a href="{{ route('project.tasks',['project_id'=>$project->id,'mid'=>request('mid')]) }}"
                                   class="nav-link m-tabs__link active " data-toggle="tab" role="tab" aria-expanded="true"
                                   data-load-html="true" data-target="#project-body" data-loading="#project-body">
                                    任务
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item " >
                                <a href="{{ route('project.dynamics',['project'=>$project->id,'mid'=>request('mid')]) }}"
                                   class="nav-link m-tabs__link " data-toggle="tab" role="tab" aria-expanded="false"
                                   data-load-html="true" data-target="#project-body" data-loading="#project-body">
                                    日志
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a href="{{ route('project.questions',['project'=>$project->id,'mid'=>request('mid')]) }}"
                                   class="nav-link m-tabs__link"data-toggle="tab" role="tab" aria-expanded="false"
                                   data-load-html="true" data-target="#project-body" data-loading="#project-body">
                                    问题
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a href="{{ route('project.malfunctions',['project'=>$project->id,'mid'=>request('mid')]) }}"
                                   class="nav-link m-tabs__link"data-toggle="tab" role="tab" aria-expanded="false"
                                   data-load-html="true" data-target="#project-body" data-loading="#project-body">
                                    故障
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a href="{{ route('project.files',['project'=>$project->id,'mid'=>request('mid')]) }}"
                                   class="nav-link m-tabs__link " data-toggle="tab" role="tab" aria-expanded="false"
                                   data-load-html="true" data-target="#project-body" data-loading="#project-body">
                                    文档
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a href="{{ route('project.users',['project'=>$project->id,'mid'=>request('mid')]) }}"
                                   class="nav-link m-tabs__link " data-toggle="tab" role="tab" aria-expanded="false"
                                   data-load-html="true" data-target="#project-body" data-loading="#project-body">
                                    参与人
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a href="{{ route('plans.index',['project'=>$project->id,'mid'=>request('mid')]) }}"
                                   class="nav-link m-tabs__link ">
                                    实施计划
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body min-height-300" id="project-body">
                </div>
            </div>
            <!--end:: Widgets/Tasks -->
        </div>
    </div>
    <!--begin::Modal-->
    <div class="modal fade" id="_modal" tabindex="-1" role="dialog" aria-labelledby="_ModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!--end::Modal-->
    <!--begin::Modal-->
    <div class="modal fade" id="_editModal" tabindex="-1" role="dialog" aria-labelledby="_EditModalLabel"
         aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!--end::Modal-->
    <!--begin::Modal-->
    <div class="modal fade" id="_planModal" tabindex="-1" role="dialog" aria-labelledby="_planModalLabel"
         aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
        $(document).ready(function () {
            mAppExtend.ajaxGetHtml(
                "#project-body","{{route('project.tasks',['project'=>$project->id,'mid'=>request('mid')])}}"
                , {}, "#project-body");
            $(".project-tab .m-tabs__link").click(function () {
                $(".project-tab .m-tabs__link").removeClass('active');
                $(this).addClass('active');
            });
        })
    </script>
@endsection
