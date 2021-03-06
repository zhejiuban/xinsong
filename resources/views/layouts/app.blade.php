<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" >
    <!-- begin::Head -->
    <head>
        <meta charset="utf-8" />
        <title>{{ config('app.name', '新松项目管理系统') }}</title>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        {{--<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">--}}
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, minimum-scale=1, maximum-scale=1, user-scalable=no" >
        <!--begin::Base Styles -->
        <link href="{{ asset('assets/vendors/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/theme/default/base/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/vendors/custom/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/vendors/custom/jquery-treegrid/css/jquery.treegrid.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/vendors/custom/zTree_v3/css/metroStyle/metroStyle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/vendors/custom/lightbox2/css/lightbox.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- webuploader -->
        <link href="{{asset('assets/vendors/custom/webuploader/webuploader.css')}}" rel="stylesheet">

        <link href="{{ asset('assets/theme/default/base/custom.css') }}" rel="stylesheet" type="text/css" />
        <!--end::Base Styles -->
        <link rel="shortcut icon" href="{{ asset('assets/theme/default/media/img/logo/favicon.ico') }}" />    
    </head>
    <!-- end::Head -->
    <!-- end::Body -->
    <body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
        <!-- begin:: Page -->
        <div class="m-grid m-grid--hor m-grid--root m-page">
            <!-- BEGIN: Header -->
            <header class="m-grid__item m-header "  data-minimize-mobile="hide" data-minimize-offset="200" data-minimize-mobile-offset="200" data-minimize="minimize" >
                <div class="m-container m-container--fluid m-container--full-height">
                    <div class="m-stack m-stack--ver m-stack--desktop">
                        <!-- BEGIN: Brand -->
                        <div class="m-stack__item m-brand  m-brand--skin-dark ">
                            <div class="m-stack m-stack--ver m-stack--general">
                                <div class="m-stack__item m-stack__item--middle m-brand__logo">
                                    <a href="{{route('home')}}" class="m-brand__logo-wrapper">
                                        <img alt="" src="{{ asset('assets/theme/default/media/img/logo/logo_default_dark.png') }}"/>
                                    </a>
                                </div>
                                <div class="m-stack__item m-stack__item--middle m-brand__tools">
                                    <!-- BEGIN: Left Aside Minimize Toggle -->
                                    <a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block">
                                        <span></span>
                                    </a>
                                    <!-- END -->
                                    <!-- BEGIN: Responsive Aside Left Menu Toggler -->
                                    <a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                                        <span></span>
                                    </a>
                                    <!-- END -->
                                    <!-- BEGIN: Responsive Header Menu Toggler -->
                                    {{--<a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">--}}
                                        {{--<span></span>--}}
                                    {{--</a>--}}
                                    <!-- END -->
                                    <!-- BEGIN: Topbar Toggler -->
                                    <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                                        <i class="flaticon-more"></i>
                                    </a>
                                    <!-- BEGIN: Topbar Toggler -->
                                </div>
                            </div>
                        </div>
                        <!-- END: Brand -->
                        <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
                            <!-- BEGIN: Topbar -->
                            <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
                                <div class="m-stack__item m-topbar__nav-wrapper">
                                    <ul class="m-topbar__nav m-nav m-nav--inline">
                                        {{--<li class="m-nav__item m-dropdown m-dropdown--large m-dropdown--arrow m-dropdown--align-center m-dropdown--mobile-full-width m-dropdown--skin-light    m-list-search m-list-search--skin-light" data-dropdown-toggle="click" data-dropdown-persistent="true" id="m_quicksearch" data-search-type="dropdown">
                                            <a href="javascript:;" class="m-nav__link m-dropdown__toggle">
                                                <span class="m-nav__link-icon">
                                                    <i class="flaticon-search-1"></i>
                                                </span>
                                            </a>
                                            <div class="m-dropdown__wrapper">
                                                <span class="m-dropdown__arrow m-dropdown__arrow--center"></span>
                                                <div class="m-dropdown__inner ">
                                                    <div class="m-dropdown__header">
                                                        <form  class="m-list-search__form">
                                                            <div class="m-list-search__form-wrapper">
                                                                <span class="m-list-search__form-input-wrapper">
                                                                    <input id="m_quicksearch_input" autocomplete="off" type="text" name="q" class="m-list-search__form-input" value="" placeholder="搜索模块...">
                                                                </span>
                                                                <span class="m-list-search__form-icon-close" id="m_quicksearch_close">
                                                                    <i class="la la-remove"></i>
                                                                </span>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="m-dropdown__body">
                                                        <div class="m-dropdown__scrollable m-scrollable" data-scrollable="true" data-max-height="300" data-mobile-max-height="200">
                                                            <div class="m-dropdown__content"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>--}}
                                        @include('notification._breadcrumb_unread')
                                        <li class="m-nav__item m-topbar__quick-actions m-topbar__quick-actions--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--mobile-full-width m-dropdown--skin-light"  data-dropdown-toggle="click">
                                            <a href="javascript:;" class="m-nav__link m-dropdown__toggle">
                                                <span class="m-nav__link-badge m-badge m-badge--dot m-badge--info m--hide"></span>
                                                <span class="m-nav__link-icon">
                                                    <i class="flaticon-share"></i>
                                                </span>
                                            </a>
                                            <div class="m-dropdown__wrapper">
                                                <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                                <div class="m-dropdown__inner">
                                                    <div class="m-dropdown__header m--align-center" style="background: url({{ asset('assets/app/media/img/misc/quick_actions_bg.jpg') }}); background-size: cover;">
                                                        <span class="m-dropdown__header-title">
                                                            快捷菜单
                                                        </span>
                                                        <!--<span class="m-dropdown__header-subtitle">
                                                            Shortcuts
                                                        </span>-->
                                                    </div>
                                                    <div class="m-dropdown__body m-dropdown__body--paddingless">
                                                        <div class="m-dropdown__content">
                                                            <div class="m-scrollable" data-scrollable="false" data-max-height="380" data-mobile-max-height="200">
                                                                <div class="m-nav-grid m-nav-grid--skin-light">
                                                                    @php $c_project = check_permission('project/projects/create') @endphp
                                                                    @php $c_task = check_permission('task/tasks/create') @endphp
                                                                    @if($c_project || $c_task )
                                                                    <div class="m-nav-grid__row">
                                                                        @if($c_project)
                                                                        <a href="{{url('project/projects/create?mid=bd128edbfd250c9c5eff5396329011cd')}}"
                                                                           class="m-nav-grid__item">
                                                                            <i class="m-nav-grid__icon flaticon-file"></i>
                                                                            <span class="m-nav-grid__text">
                                                                                创建项目
                                                                            </span>
                                                                        </a>
                                                                        @endif
                                                                        @if($c_task)
                                                                        <a href="{{route('tasks.create',['default'=>1,'mid'=>'bd128edbfd250c9c5eff5396329011cd'])}}"
                                                                           class="m-nav-grid__item  quick-publish-task">
                                                                            <i class="m-nav-grid__icon flaticon-time"></i>
                                                                            <span class="m-nav-grid__text">
                                                                                发布任务
                                                                            </span>
                                                                        </a>
                                                                        @endif
                                                                    </div>
                                                                    @endif
                                                                    <div class="m-nav-grid__row">
                                                                        <a href="{{route('malfunctions.create',['mid'=>'c61c035b6d20678363396bcbf1ab0ff0'])}}" class="m-nav-grid__item">
                                                                            <i class="m-nav-grid__icon flaticon-interface-8"></i>
                                                                            <span class="m-nav-grid__text">
                                                                                故障录入
                                                                            </span>
                                                                        </a>
                                                                        <a href="{{url('question/create?direct=1&mid=5e5fa7160f2d8bf507f11ac18455f61e')}}" class="m-nav-grid__item">
                                                                            <i class="m-nav-grid__icon flaticon-clipboard"></i>
                                                                            <span class="m-nav-grid__text">
                                                                                发布问题
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                    <div class="m-nav-grid__row">
                                                                        <a href="{{route('project.dynamic.fill',['mid'=>'ad5aa83897ece9173edaba852f2065f6'])}}" class="m-nav-grid__item">
                                                                            <i class="m-nav-grid__icon flaticon-list-2"></i>
                                                                            <span class="m-nav-grid__text">
                                                                                日志补填
                                                                            </span>
                                                                        </a>
                                                                        <a href="{{route('product_faults.create',['mid'=>'343cb319697e6c567a2354bc67918987'])}}" class="m-nav-grid__item">
                                                                            <i class="m-nav-grid__icon flaticon-interface-1"></i>
                                                                            <span class="m-nav-grid__text">
                                                                                陪产故障录入
                                                                            </span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
                                            <?php $login_user = get_current_login_user_info(true); ?>
                                            <a href="javascript:;" class="m-nav__link m-dropdown__toggle">
                                                <span class="m-topbar__userpic">
                                                    <img src="{{ avatar($login_user->avatar) }}" class="m--img-rounded m--marginless m--img-centered" alt=""/>
                                                </span>
                                                <span class="m-topbar__username m--hide">
                                                    {{$login_user->name}}
                                                </span>
                                            </a>
                                            <div class="m-dropdown__wrapper m-dropdown__wrapper-right-10">
                                                <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                                <div class="m-dropdown__inner">
                                                    <div class="m-dropdown__header m--align-center" style="background: url({{ asset('assets/app/media/img/misc/user_profile_bg.jpg') }}); background-size: cover;">
                                                        <div class="m-card-user m-card-user--skin-dark">
                                                            <div class="m-card-user__pic">
                                                                <img src="{{ avatar($login_user->avatar) }}" class="m--img-rounded m--marginless" alt=""/>
                                                            </div>
                                                            <div class="m-card-user__details">
                                                                <span class="m-card-user__name m--font-weight-500">
                                                                  {{$login_user->name}}
                                                                </span>
                                                                <a href="javascript:;" class="m-card-user__email m--font-weight-300 m-link">
                                                                    {{$login_user->email}}
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="m-dropdown__body">
                                                        <div class="m-dropdown__content">
                                                            <ul class="m-nav m-nav--skin-light">
                                                                <li class="m-nav__section m--hide">
                                                                    <span class="m-nav__section-text">
                                                                    </span>
                                                                </li>
                                                                <li class="m-nav__item">
                                                                    <a href="javascript:;"
                                                                       class="m-nav__link user-profile-edit m_quick_sidebar_toggle">
                                                                        <i class="m-nav__link-icon flaticon-profile-1"></i>
                                                                        <span class="m-nav__link-title">
                                                                            <span class="m-nav__link-wrap">
                                                                                <span class="m-nav__link-text">
                                                                                    我的资料
                                                                                </span>
                                                                                <!--<span class="m-nav__link-badge">
                                                                                    <span class="m-badge m-badge--success">
                                                                                        2
                                                                                    </span>
                                                                                </span>-->
                                                                            </span>
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                <!--<li class="m-nav__item">
                                                                    <a href="header/profile.html" class="m-nav__link">
                                                                        <i class="m-nav__link-icon flaticon-share"></i>
                                                                        <span class="m-nav__link-text">
                                                                            Activity
                                                                        </span>
                                                                    </a>
                                                                </li>-->
                                                                <li class="m-nav__item">
                                                                    <a href="{{route('notifications.index')}}" class="m-nav__link">
                                                                        <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                        <span class="m-nav__link-text">
                                                                            消息通知
                                                                        </span>
                                                                    </a>
                                                                </li>
                                                                {{--<li class="m-nav__separator m-nav__separator--fit"></li>

                                                                <li class="m-nav__item">
                                                                    <a href="#" class="m-nav__link">
                                                                        <i class="m-nav__link-icon flaticon-exclamation-1"></i>
                                                                        <span class="m-nav__link-text">
                                                                            帮助文档
                                                                        </span>
                                                                    </a>
                                                                </li>--}}
                                                                <li class="m-nav__separator m-nav__separator--fit"></li>
                                                                <li class="m-nav__item">
                                                                    <a href="{{ route('logout') }}" class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
                                                                        退出
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        {{--<li id="m_quick_sidebar_toggle" class="m-nav__item m_quick_sidebar_toggle">
                                            <a href="#" class="m-nav__link m-dropdown__toggle">
                                                <span class="m-nav__link-icon">
                                                    <i class="flaticon-grid-menu"></i>
                                                </span>
                                            </a>
                                        </li>--}}
                                    </ul>
                                </div>
                            </div>
                            <!-- END: Topbar -->
                        </div>
                    </div>
                </div>
            </header>
            <!-- END: Header -->
            <!-- begin::Body -->
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
                @include('layouts.aside')
                <div class="m-grid__item m-grid__item--fluid m-wrapper">
                    <!-- BEGIN: Subheader -->
                    <div class="m-subheader ">
                        @include('layouts.breadcrumb')
                    </div>
                    <!-- END: Subheader -->
                    <div class="m-content">
                         @yield('content')
                    </div>
                </div>
            </div>
            <!-- end:: Body -->
            <!-- begin::Footer -->
            <footer class="m-grid__item     m-footer ">
                <div class="m-container m-container--fluid m-container--full-height m-page__container">
                    <div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
                        <div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
                            <span class="m-footer__copyright">
                                {{ date('Y') }} &copy;
                            </span>
                        </div>
                        <div class="m-stack__item m-stack__item--right m-stack__item--middle m-stack__item--first">
                            <ul class="m-footer__nav m-nav m-nav--inline m--pull-right">
                                <li class="m-nav__item m-nav__item">
                                    <a href="javascript:;" class="m-nav__link" data-toggle="m-tooltip" title="帮助" data-placement="left">
                                        <i class="m-nav__link-icon flaticon-info m--icon-font-size-lg3"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end::Footer -->
        </div>
        <!-- end:: Page -->
        <!-- begin::Quick Sidebar -->
        <div id="m_quick_sidebar" class="m-quick-sidebar m-quick-sidebar--tabbed m-quick-sidebar--skin-light">
            <div class="m-quick-sidebar__content m--hide">
                <span id="m_quick_sidebar_close" class="m-quick-sidebar__close">
                    <i class="la la-close"></i>
                </span>
                <ul id="m_quick_sidebar_tabs" class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--brand" role="tablist">
                    <li class="nav-item m-tabs__item ">
                        <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_quick_sidebar_tabs_profiles" role="tab">
                            个人资料
                        </a>
                    </li>
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_quick_sidebar_tabs_settings" role="tab">
                            设置
                        </a>
                    </li>
                    {{--<li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_quick_sidebar_tabs_logs" role="tab">
                            操作记录
                        </a>
                    </li>--}}
                </ul>
                <div class="tab-content">


                    <div class="tab-pane active " id="m_quick_sidebar_tabs_profiles" role="tabpanel">
                        <div class="user-profile-form m-scrollable">

                        </div>
                        <div class="m-separator m-separator--dashed "></div>
                        <div class="user-profile-form-action">
                            <button type="button" id="user-profile-form-submit" class="btn btn-primary btn-sm m-btn m-btn--icon m-btn--pill">
                                <span>
                                    <i class="fa fa-check"></i>
                                    <span>
                                        保存
                                    </span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="tab-pane  m-scrollable" id="m_quick_sidebar_tabs_settings" role="tabpanel">
                        <div class="m-list-settings">
                            <div class="m-list-settings__group">
                                <div class="m-list-settings__heading">
                                   通用设置
                                </div>
                                <div class="m-list-settings__item">
                                    <span class="m-list-settings__item-label">
                                        消息提醒
                                    </span>
                                    <span class="m-list-settings__item-control">
                                        <span class="m-switch m-switch--outline m-switch--icon-check m-switch--brand">
                                            <label>
                                                <input type="checkbox" checked="checked" name="notify_open">
                                                <span></span>
                                            </label>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane  m-scrollable" id="m_quick_sidebar_tabs_logs" role="tabpanel">
                        <div class="m-list-timeline">
                            <div class="m-list-timeline__group">
                                <div class="m-list-timeline__heading">
                                    System Logs
                                </div>
                                <div class="m-list-timeline__items">
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                                        <a href="" class="m-list-timeline__text">
                                            12 new users registered
                                            <span class="m-badge m-badge--warning m-badge--wide">
                                                important
                                            </span>
                                        </a>
                                        <span class="m-list-timeline__time">
                                            Just now
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                                        <a href="" class="m-list-timeline__text">
                                            System shutdown
                                        </a>
                                        <span class="m-list-timeline__time">
                                            11 mins
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-danger"></span>
                                        <a href="" class="m-list-timeline__text">
                                            New invoice received
                                        </a>
                                        <span class="m-list-timeline__time">
                                            20 mins
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-warning"></span>
                                        <a href="" class="m-list-timeline__text">
                                            Database overloaded 89%
                                            <span class="m-badge m-badge--success m-badge--wide">
                                                resolved
                                            </span>
                                        </a>
                                        <span class="m-list-timeline__time">
                                            1 hr
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                                        <a href="" class="m-list-timeline__text">
                                            System error
                                        </a>
                                        <span class="m-list-timeline__time">
                                            2 hrs
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                                        <a href="" class="m-list-timeline__text">
                                            Production server down
                                            <span class="m-badge m-badge--danger m-badge--wide">
                                                pending
                                            </span>
                                        </a>
                                        <span class="m-list-timeline__time">
                                            3 hrs
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                                        <a href="" class="m-list-timeline__text">
                                            Production server up
                                        </a>
                                        <span class="m-list-timeline__time">
                                            5 hrs
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="m-list-timeline__group">
                                <div class="m-list-timeline__heading">
                                    Applications Logs
                                </div>
                                <div class="m-list-timeline__items">
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                                        <a href="" class="m-list-timeline__text">
                                            New order received
                                            <span class="m-badge m-badge--info m-badge--wide">
                                                urgent
                                            </span>
                                        </a>
                                        <span class="m-list-timeline__time">
                                            7 hrs
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                                        <a href="" class="m-list-timeline__text">
                                            12 new users registered
                                        </a>
                                        <span class="m-list-timeline__time">
                                            Just now
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                                        <a href="" class="m-list-timeline__text">
                                            System shutdown
                                        </a>
                                        <span class="m-list-timeline__time">
                                            11 mins
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-danger"></span>
                                        <a href="" class="m-list-timeline__text">
                                            New invoices received
                                        </a>
                                        <span class="m-list-timeline__time">
                                            20 mins
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-warning"></span>
                                        <a href="" class="m-list-timeline__text">
                                            Database overloaded 89%
                                        </a>
                                        <span class="m-list-timeline__time">
                                            1 hr
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                                        <a href="" class="m-list-timeline__text">
                                            System error
                                            <span class="m-badge m-badge--info m-badge--wide">
                                                pending
                                            </span>
                                        </a>
                                        <span class="m-list-timeline__time">
                                            2 hrs
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                                        <a href="" class="m-list-timeline__text">
                                            Production server down
                                        </a>
                                        <span class="m-list-timeline__time">
                                            3 hrs
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="m-list-timeline__group">
                                <div class="m-list-timeline__heading">
                                    Server Logs
                                </div>
                                <div class="m-list-timeline__items">
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                                        <a href="" class="m-list-timeline__text">
                                            Production server up
                                        </a>
                                        <span class="m-list-timeline__time">
                                            5 hrs
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                                        <a href="" class="m-list-timeline__text">
                                            New order received
                                        </a>
                                        <span class="m-list-timeline__time">
                                            7 hrs
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                                        <a href="" class="m-list-timeline__text">
                                            12 new users registered
                                        </a>
                                        <span class="m-list-timeline__time">
                                            Just now
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                                        <a href="" class="m-list-timeline__text">
                                            System shutdown
                                        </a>
                                        <span class="m-list-timeline__time">
                                            11 mins
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-danger"></span>
                                        <a href="" class="m-list-timeline__text">
                                            New invoice received
                                        </a>
                                        <span class="m-list-timeline__time">
                                            20 mins
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-warning"></span>
                                        <a href="" class="m-list-timeline__text">
                                            Database overloaded 89%
                                        </a>
                                        <span class="m-list-timeline__time">
                                            1 hr
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                                        <a href="" class="m-list-timeline__text">
                                            System error
                                        </a>
                                        <span class="m-list-timeline__time">
                                            2 hrs
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                                        <a href="" class="m-list-timeline__text">
                                            Production server down
                                        </a>
                                        <span class="m-list-timeline__time">
                                            3 hrs
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-success"></span>
                                        <a href="" class="m-list-timeline__text">
                                            Production server up
                                        </a>
                                        <span class="m-list-timeline__time">
                                            5 hrs
                                        </span>
                                    </div>
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--state-info"></span>
                                        <a href="" class="m-list-timeline__text">
                                            New order received
                                        </a>
                                        <span class="m-list-timeline__time">
                                            1117 hrs
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end::Quick Sidebar -->
        <!-- begin::Scroll Top -->
        <div class="m-scroll-top m-scroll-top--skin-top" data-toggle="m-scroll-top" data-scroll-offset="500" data-scroll-speed="300">
            <i class="la la-arrow-up"></i>
        </div>
        <!-- end::Scroll Top -->

        <!--begin::Modal-->
        <div class="modal fade" id="_CommonModal" tabindex="-1" role="dialog" aria-labelledby="_CommonModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                </div>
            </div>
        </div>
        <!--end::Modal-->

        @if (config('app.debug'))
            @include('sudosu::user-selector')
        @endif

        <!--begin::Base Scripts -->
        <script src="{{ asset('assets/vendors/base/vendors.bundle.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/theme/default/base/scripts.bundle.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/vendors/custom/lodash/lodash.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/vendors/custom/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/vendors/custom/jquery-treegrid/js/jquery.treegrid.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/vendors/custom/jquery-treegrid/js/jquery.treegrid.bootstrap3.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/vendors/custom/zTree_v3/js/jquery.ztree.all.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/vendors/custom/select2/js/i18n/zh-CN.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/vendors/custom/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/vendors/custom/datepicker/locales/bootstrap-datepicker.zh-CN.min.js') }}" type="text/javascript"></script>
        <!-- webuploader -->
        <script src="{{asset('assets/vendors/custom/webuploader/webuploader.min.js')}}" type="text/javascript" ></script>
        <script src="{{asset('assets/vendors/custom/lightbox2/js/lightbox.min.js')}}" type="text/javascript" ></script>
        <script src="{{ asset('assets/vendors/custom/echarts/echarts.min.js') }}" type="text/javascript"></script>

        <script src="{{ asset('assets/theme/default/base/custom.js') }}" type="text/javascript"></script>
        <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery(document).ready(function () {
            mAppExtend.ajaxGetHtml(
                '.user-profile-form'
                ,"{{route('user.profile')}}"
                ,{},false);
            var avatarUploadCreate = 0;
            mQuickSidebar.init({
                'trigger':function () {
                    var initProfile = function() {
                        var init = function () {
                            var profiler = $('#m_quick_sidebar_tabs_profiles');
                            var profileForm = profiler.find('.user-profile-form');
                            var topbarAside = $('#m_quick_sidebar');
                            var topbarAsideTabs = $('#m_quick_sidebar_tabs');
                            var height = topbarAside.outerHeight(true) -
                                topbarAsideTabs.outerHeight(true) - 125;
                            // init messages scrollable content
                            profileForm.css('height', height);
                            mApp.initScroller(profileForm, {});
                        };
                        init();
                        // reinit on window resize
                        mUtil.addResizeHandler(init);

                        if (!avatarUploadCreate){
                            mAppExtend.singleImageUpload({
                                uploader: 'avatarUpload',
                                picker: 'avatar-upload',
                                swf: '{{ asset("assets/js/plugins/webuploader/Uploader.swf") }}',
                                server: '{{ route("image.upload") }}',
                                formData: {
                                    '_token': '{{ csrf_token() }}',
                                    'avatar_upload':1
                                },
                                errorMsgHiddenTime: 1000,
                                isAutoInsertInput: false,//上传成功是否自动创建input存储区域
                                isHiddenResult: true,
                                uploadComplete: function (file) {
                                },
                                uploadError: function (file) {
                                },
                                uploadSuccess: function (file, response) {
                                    //上传完成触发时间
                                    $img = $('.m-card-user__pic').find('img');
                                    if (!$img.length) {
                                        $img = $('.m-card-user__pic').html('<img src="' + response.data.url + '" class="m--img-rounded m--marginless">');
                                    } else {
                                        $img.attr({'src': response.data.url});
                                        $('.m-topbar__userpic').find('img').attr({'src': response.data.url});
                                    }
                                    $('input[name="avatar"]').val(response.data.path);
                                    window.setTimeout(function () {
                                        $('#' + file.id).remove();
                                    }, 1000);
                                }
                            });
                            avatarUploadCreate = 1;
                        }
                    };
                    initProfile();
                }
            });
        });
        </script>
        <!--end::Base Scripts -->
        <!--begin::Page Snippets -->
        @yield('js')
        <!--end::Page Snippets -->
    </body>
    <!-- end::Body -->
</html>
