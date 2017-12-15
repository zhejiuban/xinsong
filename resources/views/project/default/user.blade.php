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
                                <a href="{{ route('project.tasks',['project'=>$project->id,'mid'=>request('mid')]) }}"
                                   class="nav-link m-tabs__link ">
                                    任务
                                </a>
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
                            <li class="nav-item m-tabs__item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-left"
                                data-dropdown-toggle="hover" aria-expanded="true">
                                <a href="{{ route('project.users',['project'=>$project->id,'mid'=>request('mid')]) }}"
                                   class="nav-link m-tabs__link active m-dropdown__toggle dropdown-toggle">
                                    参与人
                                </a>
                                @if(check_project_owner($project, 'edit'))
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--left"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav">
                                                    <li class="m-nav__item">
                                                        <a href="{{ route('project.users.create',['project_id'=>$project->id,'mid'=>request('mid')]) }}"
                                                           id="user-add" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-add"></i>
                                                            <span class="m-nav__link-text">
                                                            增加参与人
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body min-height-300">
                    <!--begin::Widget 14-->
                    <div class="m-widget4 row">
                    @foreach($users as $user)
                        <!--begin::Widget 14 Item-->
                        <div class="m-widget4__item col-md-4 m-widget4__item-border-bottom" id="user-{{$user->id}}">
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
                            @if(check_project_owner($project, 'edit'))
                            <div class="m-widget4__ext">
                                <a href="{{route('project.users.destroy',['project'=>$project->id,'user'=>$user->id,'mid'=>request('mid')])}}"
                                   class="user-del m-btn m-btn--pill  btn btn-sm btn-danger">
                                    移除
                                </a>
                            </div>
                            @endif
                        </div>
                        <!--end::Widget 14 Item-->
                    @endforeach
                    </div>
                    <!--end::Widget 14-->
                    {{ $users->appends([
                        'mid' => request('mid'),
                        ])->fragment('lists')->links('vendor.pagination.bootstrap-4') }}
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
            </div>
        </div>
    </div>
    <!--end::Modal-->
@endsection

@section('js')
    @component('project.default.finished_precent',['project'=>$project])
    @endcomponent()
    <script type="text/javascript">
        var modal = function (url, type) {
            $('#_modal').modal(type ? type : 'show');
            mAppExtend.ajaxGetHtml(
                '#_modal .modal-content',
                url,
                {}, true);
        };
        $(document).ready(function () {
            $("#user-add").click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                modal(url);
            });
            $('.user-del').click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                mAppExtend.deleteData({
                    'url': url,
                    callback: function (response, status, xhr) {
                        if (response.data.id) {
                            $("#user-" + response.data.id).fadeOut('slow');
                        }
                    }
                });
            });
        });
    </script>
@endsection
