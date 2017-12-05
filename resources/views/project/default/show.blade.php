@extends('layouts.app')

@section('content')
    <!-- 基本信息 -->
    <!--Begin::Main Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__body  m-portlet__body--no-padding">
            <div class="row m-row--no-padding m-row--col-separator-xl">
                <div class="col-xl-4">
                    <!--begin:: Widgets/Stats2-1 -->
                    <div class="m-widget1">
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">
                                        参与人：
                                    </h3>
                                    <span class="m-widget1__desc">
                                        张三、李四....
                                    </span>
                                </div>
                                <div class="col m--align-right">
                                    <span class="m-widget1__number m--font-danger">
                                        10
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">
                                        任务
                                    </h3>
                                    <span class="m-widget1__desc">
                                        今日任务数：30
                                    </span>
                                </div>
                                <div class="col m--align-right">
                                    <span class="m-widget1__number m--font-success">
                                        30
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">
                                        动态
                                    </h3>
                                    <span class="m-widget1__desc">
                                        今日动态数：+3
                                    </span>
                                </div>
                                <div class="col m--align-right">
                                    <span class="m-widget1__number m--font-success">
                                        20
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="m-widget1__item">
                            <div class="row m-row--no-padding align-items-center">
                                <div class="col">
                                    <h3 class="m-widget1__title">
                                        协作
                                    </h3>
                                    <span class="m-widget1__desc">
                                        今日动态数：+3
                                    </span>
                                </div>
                                <div class="col m--align-right">
                                    <span class="m-widget1__number m--font-success">
                                        20
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:: Widgets/Stats2-1 -->
                </div>
                <div class="col-xl-4">
                    <!--begin:: Widgets/Daily Sales-->
                    <div class="m-widget1">
                        <div class="m-widget19">
                            <div class="m-widget19__content">
                                <div class="m-widget19__header">
                                    <div class="m-widget19__user-img">
                                        <img class="m-widget19__img" src="../../assets/app/media/img//users/user1.jpg" alt="">
                                    </div>
                                    <div class="m-widget19__info">
                                        <span class="m-widget19__username">
                                            负责人：Anna Krox
                                        </span>
                                        <br>
                                        <span class="m-widget19__time">
                                            UX/UI Designer, Google
                                        </span>
                                    </div>
                                </div>
                                <h4 class="m-widget19__title">
                                    项目名称
                                </h4>
                                <div class="m-widget19__body">
                                    项目描述项目描述 项目描述项目描述 项目描述项目描述 项目描述项目描述 项目描述项目描述 项目描述项目描述 项目描述项目描述 项目描述项目描述 项目描述项目描述
                                </div>
                            </div>
                            <div class="m-widget19__action">
                                <a href="#" title="编辑" class="btn btn-default m-btn m-btn--icon m-btn--icon-only">
                                    <i class="la la-edit"></i>
                                </a>
                                <a href="#" title="开始" class="btn btn-success m-btn m-btn--icon m-btn--icon-only">
                                    <i class="la la-play-circle"></i>
                                </a>
                                {{--  <a href="#" title="暂停" class="btn btn-warning m-btn m-btn--icon m-btn--icon-only">
                                    <i class="la la-pause"></i>
                                </a>  --}}
                                <a href="#" title="完成" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only">
                                    <i class="la la-power-off"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--end:: Widgets/Daily Sales-->
                </div>
                <div class="col-xl-4">
                    <!--begin:: Widgets/Profit Share-->
                    <div class="m-widget14">
                        <div class="m-widget14__header">
                            <h3 class="m-widget14__title">
                                完成情况
                            </h3>
                            <span class="m-widget14__desc">
                                
                            </span>
                        </div>
                        <div class="row  align-items-center">
                            <div class="col">
                                <div id="m_chart_profit_share" class="m-widget14__chart" style="height: 160px">
                                    <div class="m-widget14__stat">
                                        45%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end:: Widgets/Profit Share-->
                </div>
            </div>
        </div>
    </div>
    <!--End::Main Portlet-->
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
                                任务
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_widget2_tab1_content" role="tab">
                                    日
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget2_tab2_content1" role="tab">
                                    周
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget2_tab3_content1" role="tab">
                                    月
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="m_widget2_tab1_content">
                            <div class="m-widget2">
                                <div class="m-widget2__item m-widget2__item--primary">
                                    <div class="m-widget2__checkbox">
                                        <label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="m-widget2__desc">
                                        <span class="m-widget2__text">
                                            Make Metronic Great  Again.Lorem Ipsum Amet
                                        </span>
                                        <br>
                                        <span class="m-widget2__user-name">
                                            <a href="#" class="m-widget2__link">
                                                By Bob
                                            </a>
                                        </span>
                                    </div>
                                    <div class="m-widget2__actions">
                                        <div class="m-widget2__actions-nav">
                                            <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover">
                                                <a href="#" class="m-dropdown__toggle">
                                                    <i class="la la-ellipsis-h"></i>
                                                </a>
                                                <div class="m-dropdown__wrapper">
                                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                                    <div class="m-dropdown__inner">
                                                        <div class="m-dropdown__body">
                                                            <div class="m-dropdown__content">
                                                                <ul class="m-nav">
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-share"></i>
                                                                            <span class="m-nav__link-text">
                                                                                Activity
                                                                            </span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                            <span class="m-nav__link-text">
                                                                                Messages
                                                                            </span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-info"></i>
                                                                            <span class="m-nav__link-text">
                                                                                FAQ
                                                                            </span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                            <span class="m-nav__link-text">
                                                                                Support
                                                                            </span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-widget2__item m-widget2__item--warning">
                                    <div class="m-widget2__checkbox">
                                        <label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="m-widget2__desc">
                                        <span class="m-widget2__text">
                                            Prepare Docs For Metting On Monday
                                        </span>
                                        <br>
                                        <span class="m-widget2__user-name">
                                            <a href="#" class="m-widget2__link">
                                                By Sean
                                            </a>
                                        </span>
                                    </div>
                                    <div class="m-widget2__actions">
                                        <div class="m-widget2__actions-nav">
                                            <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover">
                                                <a href="#" class="m-dropdown__toggle">
                                                    <i class="la la-ellipsis-h"></i>
                                                </a>
                                                <div class="m-dropdown__wrapper">
                                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                                    <div class="m-dropdown__inner">
                                                        <div class="m-dropdown__body">
                                                            <div class="m-dropdown__content">
                                                                <ul class="m-nav">
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-share"></i>
                                                                            <span class="m-nav__link-text">
																									Activity
																								</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                            <span class="m-nav__link-text">
																									Messages
																								</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-info"></i>
                                                                            <span class="m-nav__link-text">
																									FAQ
																								</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                            <span class="m-nav__link-text">
																									Support
																								</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-widget2__item m-widget2__item--brand">
                                    <div class="m-widget2__checkbox">
                                        <label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="m-widget2__desc">
															<span class="m-widget2__text">
																Make Widgets Great Again.Estudiat Communy Elit
															</span>
                                        <br>
                                        <span class="m-widget2__user-name">
																<a href="#" class="m-widget2__link">
																	By Aziko
																</a>
															</span>
                                    </div>
                                    <div class="m-widget2__actions">
                                        <div class="m-widget2__actions-nav">
                                            <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover">
                                                <a href="#" class="m-dropdown__toggle">
                                                    <i class="la la-ellipsis-h"></i>
                                                </a>
                                                <div class="m-dropdown__wrapper">
                                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                                    <div class="m-dropdown__inner">
                                                        <div class="m-dropdown__body">
                                                            <div class="m-dropdown__content">
                                                                <ul class="m-nav">
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-share"></i>
                                                                            <span class="m-nav__link-text">
																									Activity
																								</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                            <span class="m-nav__link-text">
																									Messages
																								</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-info"></i>
                                                                            <span class="m-nav__link-text">
																									FAQ
																								</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                            <span class="m-nav__link-text">
																									Support
																								</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-widget2__item m-widget2__item--success">
                                    <div class="m-widget2__checkbox">
                                        <label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="m-widget2__desc">
															<span class="m-widget2__text">
																Make Metronic Great Again.Lorem Ipsum
															</span>
                                        <br>
                                        <span class="m-widget2__user-name">
																<a href="#" class="m-widget2__link">
																	By James
																</a>
															</span>
                                    </div>
                                    <div class="m-widget2__actions">
                                        <div class="m-widget2__actions-nav">
                                            <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover">
                                                <a href="#" class="m-dropdown__toggle">
                                                    <i class="la la-ellipsis-h"></i>
                                                </a>
                                                <div class="m-dropdown__wrapper">
                                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                                    <div class="m-dropdown__inner">
                                                        <div class="m-dropdown__body">
                                                            <div class="m-dropdown__content">
                                                                <ul class="m-nav">
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-share"></i>
                                                                            <span class="m-nav__link-text">
																									Activity
																								</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                            <span class="m-nav__link-text">
																									Messages
																								</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-info"></i>
                                                                            <span class="m-nav__link-text">
																									FAQ
																								</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                            <span class="m-nav__link-text">
																									Support
																								</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-widget2__item m-widget2__item--danger">
                                    <div class="m-widget2__checkbox">
                                        <label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="m-widget2__desc">
															<span class="m-widget2__text">
																Completa Financial Report For Emirates Airlines
															</span>
                                        <br>
                                        <span class="m-widget2__user-name">
																<a href="#" class="m-widget2__link">
																	By Bob
																</a>
															</span>
                                    </div>
                                    <div class="m-widget2__actions">
                                        <div class="m-widget2__actions-nav">
                                            <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover">
                                                <a href="#" class="m-dropdown__toggle">
                                                    <i class="la la-ellipsis-h"></i>
                                                </a>
                                                <div class="m-dropdown__wrapper">
                                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                                    <div class="m-dropdown__inner">
                                                        <div class="m-dropdown__body">
                                                            <div class="m-dropdown__content">
                                                                <ul class="m-nav">
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-share"></i>
                                                                            <span class="m-nav__link-text">
																									Activity
																								</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                            <span class="m-nav__link-text">
																									Messages
																								</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-info"></i>
                                                                            <span class="m-nav__link-text">
																									FAQ
																								</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                            <span class="m-nav__link-text">
																									Support
																								</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-widget2__item m-widget2__item--info">
                                    <div class="m-widget2__checkbox">
                                        <label class="m-checkbox m-checkbox--solid m-checkbox--single m-checkbox--brand">
                                            <input type="checkbox">
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="m-widget2__desc">
															<span class="m-widget2__text">
																Completa Financial Report For Emirates Airlines
															</span>
                                        <br>
                                        <span class="m-widget2__user-name">
																<a href="#" class="m-widget2__link">
																	By Sean
																</a>
															</span>
                                    </div>
                                    <div class="m-widget2__actions">
                                        <div class="m-widget2__actions-nav">
                                            <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover">
                                                <a href="#" class="m-dropdown__toggle">
                                                    <i class="la la-ellipsis-h"></i>
                                                </a>
                                                <div class="m-dropdown__wrapper">
                                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                                    <div class="m-dropdown__inner">
                                                        <div class="m-dropdown__body">
                                                            <div class="m-dropdown__content">
                                                                <ul class="m-nav">
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-share"></i>
                                                                            <span class="m-nav__link-text">
																									Activity
																								</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                            <span class="m-nav__link-text">
																									Messages
																								</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-info"></i>
                                                                            <span class="m-nav__link-text">
																									FAQ
																								</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="m-nav__item">
                                                                        <a href="" class="m-nav__link">
                                                                            <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                            <span class="m-nav__link-text">
																									Support
																								</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="m_widget2_tab2_content"></div>
                        <div class="tab-pane" id="m_widget2_tab3_content"></div>
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
                                项目动态
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="m-portlet__nav">
                            <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                                <a href="#" class="m-portlet__nav-link m-portlet__nav-link--icon m-portlet__nav-link--icon-xl m-dropdown__toggle">
                                    <i class="la la-ellipsis-h m--font-brand"></i>
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav">
                                                    <li class="m-nav__item">
                                                        <a href="" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-share"></i>
                                                            <span class="m-nav__link-text">
                                                                Activity
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                            <span class="m-nav__link-text">
																					Messages
																				</span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-info"></i>
                                                            <span class="m-nav__link-text">
																					FAQ
																				</span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                            <span class="m-nav__link-text">
																					Support
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
                    <div class="m-widget3">
                        <div class="m-widget3__item">
                            <div class="m-widget3__header">
                                <div class="m-widget3__user-img">
                                    <img class="m-widget3__img" src="assets/app/media/img/users/user1.jpg" alt="">
                                </div>
                                <div class="m-widget3__info">
														<span class="m-widget3__username">
															Melania Trump
														</span>
                                    <br>
                                    <span class="m-widget3__time">
                                        2 day ago
                                    </span>
                                </div>
                                <span class="m-widget3__status m--font-info">
                                    Pending
                                </span>
                            </div>
                            <div class="m-widget3__body">
                                <p class="m-widget3__text">
                                    Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.
                                </p>
                            </div>
                        </div>
                        <div class="m-widget3__item">
                            <div class="m-widget3__header">
                                <div class="m-widget3__user-img">
                                    <img class="m-widget3__img" src="assets/app/media/img/users/user4.jpg" alt="">
                                </div>
                                <div class="m-widget3__info">
														<span class="m-widget3__username">
															Lebron King James
														</span>
                                    <br>
                                    <span class="m-widget3__time">
															1 day ago
														</span>
                                </div>
                                <span class="m-widget3__status m--font-brand">
														Open
													</span>
                            </div>
                            <div class="m-widget3__body">
                                <p class="m-widget3__text">
                                    Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.Ut wisi enim ad minim veniam,quis nostrud exerci tation ullamcorper.
                                </p>
                            </div>
                        </div>
                        <div class="m-widget3__item">
                            <div class="m-widget3__header">
                                <div class="m-widget3__user-img">
                                    <img class="m-widget3__img" src="assets/app/media/img/users/user5.jpg" alt="">
                                </div>
                                <div class="m-widget3__info">
														<span class="m-widget3__username">
															Deb Gibson
														</span>
                                    <br>
                                    <span class="m-widget3__time">
															3 weeks ago
														</span>
                                </div>
                                <span class="m-widget3__status m--font-success">
														Closed
													</span>
                            </div>
                            <div class="m-widget3__body">
                                <p class="m-widget3__text">
                                    Lorem ipsum dolor sit amet,consectetuer edipiscing elit,sed diam nonummy nibh euismod tinciduntut laoreet doloremagna aliquam erat volutpat.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Support Tickets -->
        </div>
    </div>
    <!--End::Main Portlet-->
    <!-- 工作计划，项目日志 -->
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
                                    <a href="#" class="m-portlet__nav-link m-portlet__nav-link--icon m-portlet__nav-link--icon-xl m-dropdown__toggle">
                                        <i class="la la-plus m--hide"></i>
                                        <i class="la la-ellipsis-h m--font-brand"></i>
                                    </a>
                                    <div class="m-dropdown__wrapper">
                                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                        <div class="m-dropdown__inner">
                                            <div class="m-dropdown__body">
                                                <div class="m-dropdown__content">
                                                    <ul class="m-nav">
                                                        <li class="m-nav__section m-nav__section--first">
																				<span class="m-nav__section-text">
																					Quick Actions
																				</span>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-share"></i>
                                                                <span class="m-nav__link-text">
																						Create Post
																					</span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                <span class="m-nav__link-text">
																						Send Messages
																					</span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-multimedia-2"></i>
                                                                <span class="m-nav__link-text">
																						Upload File
																					</span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__section">
																				<span class="m-nav__section-text">
																					Useful Links
																				</span>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-info"></i>
                                                                <span class="m-nav__link-text">
																						FAQ
																					</span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                <span class="m-nav__link-text">
																						Support
																					</span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__separator m-nav__separator--fit m--hide"></li>
                                                        <li class="m-nav__item m--hide">
                                                            <a href="#" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm">
                                                                Submit
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
                    <div class="m-widget5">
                                <div class="m-widget5__item">
                                    <div class="m-widget5__pic">
                                        <img class="m-widget7__img" src="assets/app/media/img//products/product6.jpg" alt="">
                                    </div>
                                    <div class="m-widget5__content">
                                        <h4 class="m-widget5__title">
                                            Great Logo Designn
                                        </h4>
                                        <span class="m-widget5__desc">
																Make Metronic Great  Again.Lorem Ipsum Amet
															</span>
                                        <div class="m-widget5__info">
																<span class="m-widget5__author">
																	Author:
																</span>
                                            <span class="m-widget5__info-label">
																	author:
																</span>
                                            <span class="m-widget5__info-author-name">
																	Fly themes
																</span>
                                            <span class="m-widget5__info-label">
																	Released:
																</span>
                                            <span class="m-widget5__info-date m--font-info">
																	23.08.17
																</span>
                                        </div>
                                    </div>
                                    <div class="m-widget5__stats1">
															<span class="m-widget5__number">
																19,200
															</span>
                                        <br>
                                        <span class="m-widget5__sales">
																sales
															</span>
                                    </div>
                                    <div class="m-widget5__stats2">
															<span class="m-widget5__number">
																1046
															</span>
                                        <br>
                                        <span class="m-widget5__votes">
																votes
															</span>
                                    </div>
                                </div>
                                <div class="m-widget5__item">
                                    <div class="m-widget5__pic">
                                        <img class="m-widget7__img" src="assets/app/media/img//products/product10.jpg" alt="">
                                    </div>
                                    <div class="m-widget5__content">
                                        <h4 class="m-widget5__title">
                                            Branding Mockup
                                        </h4>
                                        <span class="m-widget5__desc">
																Make Metronic Great  Again.Lorem Ipsum Amet
															</span>
                                        <div class="m-widget5__info">
																<span class="m-widget5__author">
																	Author:
																</span>
                                            <span class="m-widget5__info-author m--font-info">
																	Fly themes
																</span>
                                            <span class="m-widget5__info-label">
																	Released:
																</span>
                                            <span class="m-widget5__info-date m--font-info">
																	23.08.17
																</span>
                                        </div>
                                    </div>
                                    <div class="m-widget5__stats1">
															<span class="m-widget5__number">
																24,583
															</span>
                                        <br>
                                        <span class="m-widget5__sales">
																sales
															</span>
                                    </div>
                                    <div class="m-widget5__stats2">
															<span class="m-widget5__number">
																3809
															</span>
                                        <br>
                                        <span class="m-widget5__votes">
																votes
															</span>
                                    </div>
                                </div>
                                <div class="m-widget5__item">
                                    <div class="m-widget5__pic">
                                        <img class="m-widget7__img" src="assets/app/media/img//products/product11.jpg" alt="">
                                    </div>
                                    <div class="m-widget5__content">
                                        <h4 class="m-widget5__title">
                                            Awesome Mobile App
                                        </h4>
                                        <span class="m-widget5__desc">
																Make Metronic Great  Again.Lorem Ipsum Amet
															</span>
                                        <div class="m-widget5__info">
																<span class="m-widget5__author">
																	Author:
																</span>
                                            <span class="m-widget5__info-author m--font-info">
																	Fly themes
																</span>
                                            <span class="m-widget5__info-label">
																	Released:
																</span>
                                            <span class="m-widget5__info-date m--font-info">
																	23.08.17
																</span>
                                        </div>
                                    </div>
                                    <div class="m-widget5__stats1">
															<span class="m-widget5__number">
																10,054
															</span>
                                        <br>
                                        <span class="m-widget5__sales">
																sales
															</span>
                                    </div>
                                    <div class="m-widget5__stats2">
															<span class="m-widget5__number">
																1103
															</span>
                                        <br>
                                        <span class="m-widget5__votes">
																votes
															</span>
                                    </div>
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
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_widget4_tab1_content" role="tab">
                                    日
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget4_tab2_content" role="tab">
                                    周
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget4_tab3_content" role="tab">
                                    月
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="m_widget4_tab1_content">
                            <div class="m-scrollable" data-scrollable="true" data-max-height="400" style="height: 400px; overflow: hidden;">
                                <div class="m-list-timeline m-list-timeline--skin-light">
                                    <div class="m-list-timeline__items">
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--success"></span>
                                            <span class="m-list-timeline__text">
																	12 new users registered
																</span>
                                            <span class="m-list-timeline__time">
																	Just now
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--info"></span>
                                            <span class="m-list-timeline__text">
																	System shutdown
																	<span class="m-badge m-badge--success m-badge--wide">
																		pending
																	</span>
																</span>
                                            <span class="m-list-timeline__time">
																	14 mins
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--danger"></span>
                                            <span class="m-list-timeline__text">
																	New invoice received
																</span>
                                            <span class="m-list-timeline__time">
																	20 mins
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--accent"></span>
                                            <span class="m-list-timeline__text">
																	DB overloaded 80%
																	<span class="m-badge m-badge--info m-badge--wide">
																		settled
																	</span>
																</span>
                                            <span class="m-list-timeline__time">
																	1 hr
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--warning"></span>
                                            <span class="m-list-timeline__text">
																	System error -
																	<a href="#" class="m-link">
																		Check
																	</a>
																</span>
                                            <span class="m-list-timeline__time">
																	2 hrs
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--brand"></span>
                                            <span class="m-list-timeline__text">
																	Production server down
																</span>
                                            <span class="m-list-timeline__time">
																	3 hrs
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--info"></span>
                                            <span class="m-list-timeline__text">
																	Production server up
																</span>
                                            <span class="m-list-timeline__time">
																	5 hrs
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--success"></span>
                                            <span href="" class="m-list-timeline__text">
																	New order received
																	<span class="m-badge m-badge--danger m-badge--wide">
																		urgent
																	</span>
																</span>
                                            <span class="m-list-timeline__time">
																	7 hrs
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--success"></span>
                                            <span class="m-list-timeline__text">
																	12 new users registered
																</span>
                                            <span class="m-list-timeline__time">
																	Just now
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--info"></span>
                                            <span class="m-list-timeline__text">
																	System shutdown
																	<span class="m-badge m-badge--success m-badge--wide">
																		pending
																	</span>
																</span>
                                            <span class="m-list-timeline__time">
																	14 mins
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--danger"></span>
                                            <span class="m-list-timeline__text">
																	New invoice received
																</span>
                                            <span class="m-list-timeline__time">
																	20 mins
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--accent"></span>
                                            <span class="m-list-timeline__text">
																	DB overloaded 80%
																	<span class="m-badge m-badge--info m-badge--wide">
																		settled
																	</span>
																</span>
                                            <span class="m-list-timeline__time">
																	1 hr
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--danger"></span>
                                            <span class="m-list-timeline__text">
																	New invoice received
																</span>
                                            <span class="m-list-timeline__time">
																	20 mins
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--accent"></span>
                                            <span class="m-list-timeline__text">
																	DB overloaded 80%
																	<span class="m-badge m-badge--info m-badge--wide">
																		settled
																	</span>
																</span>
                                            <span class="m-list-timeline__time">
																	1 hr
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--warning"></span>
                                            <span class="m-list-timeline__text">
																	System error -
																	<a href="#" class="m-link">
																		Check
																	</a>
																</span>
                                            <span class="m-list-timeline__time">
																	2 hrs
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--brand"></span>
                                            <span class="m-list-timeline__text">
																	Production server down
																</span>
                                            <span class="m-list-timeline__time">
																	3 hrs
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--info"></span>
                                            <span class="m-list-timeline__text">
																	Production server up
																</span>
                                            <span class="m-list-timeline__time">
																	5 hrs
																</span>
                                        </div>
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge m-list-timeline__badge--success"></span>
                                            <span href="" class="m-list-timeline__text">
																	New order received
																	<span class="m-badge m-badge--danger m-badge--wide">
																		urgent
																	</span>
																</span>
                                            <span class="m-list-timeline__time">
																	7 hrs
																</span>
                                        </div>
                                    </div>
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
                                <li class="m-portlet__nav-item m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover">
                                    <a href="#" class="m-portlet__nav-link m-dropdown__toggle dropdown-toggle btn btn--sm m-btn--pill btn-secondary m-btn m-btn--label-brand">
                                        Today
                                    </a>
                                    <div class="m-dropdown__wrapper">
                                        <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                        <div class="m-dropdown__inner">
                                            <div class="m-dropdown__body">
                                                <div class="m-dropdown__content">
                                                    <ul class="m-nav">
                                                        <li class="m-nav__section m-nav__section--first">
                                                            <span class="m-nav__section-text">
                                                                Quick Actions
                                                            </span>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-share"></i>
                                                                <span class="m-nav__link-text">
                                                                    Activity
                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-chat-1"></i>
                                                                <span class="m-nav__link-text">
                                                                    Messages
                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-info"></i>
                                                                <span class="m-nav__link-text">
                                                                    FAQ
                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__item">
                                                            <a href="" class="m-nav__link">
                                                                <i class="m-nav__link-icon flaticon-lifebuoy"></i>
                                                                <span class="m-nav__link-text">
                                                                    Support
                                                                </span>
                                                            </a>
                                                        </li>
                                                        <li class="m-nav__separator m-nav__separator--fit"></li>
                                                        <li class="m-nav__item">
                                                            <a href="#" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm">
                                                                Cancel
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
                        <!--begin::m-widget4-->
                        <div class="m-widget4">
                            <div class="m-widget4__item">
                                <div class="m-widget4__img m-widget4__img--icon">
                                    <img src="../../assets/app/media/img/files/doc.svg" alt="">
                                </div>
                                <div class="m-widget4__info">
                                    <span class="m-widget4__text">
                                        Metronic Documentation
                                    </span>
                                </div>
                                <div class="m-widget4__ext">
                                    <a href="#" class="m-widget4__icon">
                                        <i class="la la-download"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="m-widget4__item">
                                <div class="m-widget4__img m-widget4__img--icon">
                                    <img src="../../assets/app/media/img/files/jpg.svg" alt="">
                                </div>
                                <div class="m-widget4__info">
                                    <span class="m-widget4__text">
                                        Make JPEG Great Again
                                    </span>
                                </div>
                                <div class="m-widget4__ext">
                                    <a href="#" class="m-widget4__icon">
                                        <i class="la la-download"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="m-widget4__item">
                                <div class="m-widget4__img m-widget4__img--icon">
                                    <img src="../../assets/app/media/img/files/pdf.svg" alt="">
                                </div>
                                <div class="m-widget4__info">
                                    <span class="m-widget4__text">
                                        Full Deeveloper Manual For 4.7
                                    </span>
                                </div>
                                <div class="m-widget4__ext">
                                    <a href="#" class="m-widget4__icon">
                                        <i class="la la-download"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="m-widget4__item">
                                <div class="m-widget4__img m-widget4__img--icon">
                                    <img src="../../assets/app/media/img/files/javascript.svg" alt="">
                                </div>
                                <div class="m-widget4__info">
                                    <span class="m-widget4__text">
                                        Make JS Great Again
                                    </span>
                                </div>
                                <div class="m-widget4__ext">
                                    <a href="#" class="m-widget4__icon">
                                        <i class="la la-download"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="m-widget4__item">
                                <div class="m-widget4__img m-widget4__img--icon">
                                    <img src="../../assets/app/media/img/files/zip.svg" alt="">
                                </div>
                                <div class="m-widget4__info">
                                    <span class="m-widget4__text">
                                        Download Ziped version OF 5.0
                                    </span>
                                </div>
                                <div class="m-widget4__ext">
                                    <a href="#" class="m-widget4__icon">
                                        <i class="la la-download"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="m-widget4__item">
                                <div class="m-widget4__img m-widget4__img--icon">
                                    <img src="../../assets/app/media/img/files/pdf.svg" alt="">
                                </div>
                                <div class="m-widget4__info">
                                    <span class="m-widget4__text">
                                        Finance Report 2016/2017
                                    </span>
                                </div>
                                <div class="m-widget4__ext">
                                    <a href="#" class="m-widget4__icon">
                                        <i class="la la-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!--end::Widget 9-->
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
                    <!--begin::Widget 14-->
                    <div class="m-widget4">
                        <!--begin::Widget 14 Item-->
                        <div class="m-widget4__item">
                            <div class="m-widget4__img m-widget4__img--pic">
                                <img src="../../assets/app/media/img/users/100_4.jpg" alt="">
                            </div>
                            <div class="m-widget4__info">
                                <span class="m-widget4__title">
                                    Anna Strong
                                </span>
                                <br>
                                <span class="m-widget4__sub">
                                    Visual Designer,Google Inc
                                </span>
                            </div>
                            <div class="m-widget4__ext">
                                <a href="#"  class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">
                                    Follow
                                </a>
                            </div>
                        </div>
                        <!--end::Widget 14 Item-->
                        <!--begin::Widget 14 Item-->
                        <div class="m-widget4__item">
                            <div class="m-widget4__img m-widget4__img--pic">
                                <img src="../../assets/app/media/img/users/100_14.jpg" alt="">
                            </div>
                            <div class="m-widget4__info">
                                <span class="m-widget4__title">
                                    Milano Esco
                                </span>
                                <br>
                                <span class="m-widget4__sub">
                                    Product Designer, Apple Inc
                                </span>
                            </div>
                            <div class="m-widget4__ext">
                                <a href="#" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">
                                    Follow
                                </a>
                            </div>
                        </div>
                        <!--end::Widget 14 Item-->
                        <!--begin::Widget 14 Item-->
                        <div class="m-widget4__item">
                            <div class="m-widget4__img m-widget4__img--pic">
                                <img src="../../assets/app/media/img/users/100_11.jpg" alt="">
                            </div>
                            <div class="m-widget4__info">
                                <span class="m-widget4__title">
                                    Nick Bold
                                </span>
                                <br>
                                <span class="m-widget4__sub">
                                    Web Developer, Facebook Inc
                                </span>
                            </div>
                            <div class="m-widget4__ext">
                                <a href="#" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">
                                    Follow
                                </a>
                            </div>
                        </div>
                        <!--end::Widget 14 Item-->
                        <!--begin::Widget 14 Item-->
                        <div class="m-widget4__item">
                            <div class="m-widget4__img m-widget4__img--pic">
                                <img src="../../assets/app/media/img/users/100_1.jpg" alt="">
                            </div>
                            <div class="m-widget4__info">
                                <span class="m-widget4__title">
                                    Wiltor Delton
                                </span>
                                <br>
                                <span class="m-widget4__sub">
                                    Project Manager, Amazon Inc
                                </span>
                            </div>
                            <div class="m-widget4__ext">
                                <a href="#" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">
                                    Follow
                                </a>
                            </div>
                        </div>
                        <!--end::Widget 14 Item-->
                        <!--begin::Widget 14 Item-->
                        <div class="m-widget4__item">
                            <div class="m-widget4__img m-widget4__img--pic">
                                <img src="../../assets/app/media/img/users/100_5.jpg" alt="">
                            </div>
                            <div class="m-widget4__info">
                                <span class="m-widget4__title">
                                    Nick Stone
                                </span>
                                <br>
                                <span class="m-widget4__sub">
                                    Visual Designer, Github Inc
                                </span>
                            </div>
                            <div class="m-widget4__ext">
                                <a href="#"  class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-secondary">
                                    Follow
                                </a>
                            </div>
                        </div>
                        <!--end::Widget 14 Item-->
                    </div>
                    <!--end::Widget 14-->
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
                    <div class="m-widget4">
                        <div class="m-widget4__item">
                            <div class="m-widget4__img m-widget4__img--logo">
                                <img src="assets/app/media/img/client-logos/logo5.png" alt="">
                            </div>
                            <div class="m-widget4__info">
													<span class="m-widget4__title">
														Trump Themes
													</span>
                                <br>
                                <span class="m-widget4__sub">
														Make Metronic Great Again
													</span>
                            </div>
                            <span class="m-widget4__ext">
													<span class="m-widget4__number m--font-brand">
														+$2500
													</span>
												</span>
                        </div>
                        <div class="m-widget4__item">
                            <div class="m-widget4__img m-widget4__img--logo">
                                <img src="assets/app/media/img/client-logos/logo4.png" alt="">
                            </div>
                            <div class="m-widget4__info">
													<span class="m-widget4__title">
														StarBucks
													</span>
                                <br>
                                <span class="m-widget4__sub">
														Good Coffee & Snacks
													</span>
                            </div>
                            <span class="m-widget4__ext">
													<span class="m-widget4__number m--font-brand">
														-$290
													</span>
												</span>
                        </div>
                        <div class="m-widget4__item">
                            <div class="m-widget4__img m-widget4__img--logo">
                                <img src="assets/app/media/img/client-logos/logo3.png" alt="">
                            </div>
                            <div class="m-widget4__info">
													<span class="m-widget4__title">
														Phyton
													</span>
                                <br>
                                <span class="m-widget4__sub">
														A Programming Language
													</span>
                            </div>
                            <span class="m-widget4__ext">
													<span class="m-widget4__number m--font-brand">
														+$17
													</span>
												</span>
                        </div>
                        <div class="m-widget4__item">
                            <div class="m-widget4__img m-widget4__img--logo">
                                <img src="assets/app/media/img/client-logos/logo2.png" alt="">
                            </div>
                            <div class="m-widget4__info">
													<span class="m-widget4__title">
														GreenMakers
													</span>
                                <br>
                                <span class="m-widget4__sub">
                                    Make Green Great Again
                                </span>
                            </div>
                            <span class="m-widget4__ext">
                                <span class="m-widget4__number m--font-brand">
                                    -$2.50
                                </span>
                            </span>
                        </div>
                        <div class="m-widget4__item">
                            <div class="m-widget4__img m-widget4__img--logo">
                                <img src="assets/app/media/img/client-logos/logo1.png" alt="">
                            </div>
                            <div class="m-widget4__info">
                                <span class="m-widget4__title">
                                    FlyThemes
                                </span>
                                <br>
                                <span class="m-widget4__sub">
                                    A Let's Fly Fast Again Language
                                </span>
                            </div>
                            <span class="m-widget4__ext">
                                <span class="m-widget4__number m--font-brand">
                                    +$200
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/Authors Profit-->
        </div>
    </div>
    <!--End::Main Portlet-->
    <!--Begin::Main Portlet-->
    <div class="row">
        <div class="col-xl-12">
            <!--begin:: Widgets/User Progress -->
            <div class="m-portlet m-portlet--full-height">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                任务进度
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-pills nav-pills--brand m-nav-pills--align-right m-nav-pills--btn-pill m-nav-pills--btn-sm" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_widget4_tab1_content" role="tab">
                                    Today
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget4_tab2_content" role="tab">
                                    Week
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#m_widget4_tab3_content" role="tab">
                                    Month
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="m_widget4_tab1_content">
                            <div class="m-widget4 m-widget4--progress">
                                <div class="m-widget4__item">
                                    <div class="m-widget4__img m-widget4__img--pic">
                                        <img src="../../assets/app/media/img/users/100_4.jpg" alt="">
                                    </div>
                                    <div class="m-widget4__info">
                                        <span class="m-widget4__title">
                                            Anna Strong
                                        </span>
                                        <br>
                                        <span class="m-widget4__sub">
                                            Visual Designer,Google Inc
                                        </span>
                                    </div>
                                    <div class="m-widget4__progress">
                                        <div class="m-widget4__progress-wrapper">
                                            <span class="m-widget17__progress-number">
                                                63%
                                            </span>
                                            <span class="m-widget17__progress-label">
                                                London
                                            </span>
                                            <div class="progress m-progress--sm">
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: 63%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="63"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-widget4__ext">
                                        <a href="#" class="m-btn m-btn--hover-brand m-btn--pill btn btn-sm btn-secondary">
                                            Follow
                                        </a>
                                    </div>
                                </div>
                                <div class="m-widget4__item">
                                    <div class="m-widget4__img m-widget4__img--pic">
                                        <img src="../../assets/app/media/img/users/100_14.jpg" alt="">
                                    </div>
                                    <div class="m-widget4__info">
                                        <span class="m-widget4__title">
                                            Milano Esco
                                        </span>
                                        <br>
                                        <span class="m-widget4__sub">
                                            Product Designer, Apple Inc
                                        </span>
                                    </div>
                                    <div class="m-widget4__progress">
                                        <div class="m-widget4__progress-wrapper">
                                            <span class="m-widget17__progress-number">
                                                33%
                                            </span>
                                            <span class="m-widget17__progress-label">
                                                Paris
                                            </span>
                                            <div class="progress m-progress--sm">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 33%;" aria-valuenow="45" aria-valuemin="0" aria-valuemax="33"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-widget4__ext">
                                        <a href="#" class="m-btn m-btn--hover-brand m-btn--pill btn btn-sm btn-secondary">
                                            Follow
                                        </a>
                                    </div>
                                </div>
                                <div class="m-widget4__item">
                                    <div class="m-widget4__img m-widget4__img--pic">
                                        <img src="../../assets/app/media/img/users/100_11.jpg" alt="">
                                    </div>
                                    <div class="m-widget4__info">
                                        <span class="m-widget4__title">
                                            Nick Bold
                                        </span>
                                        <br>
                                        <span class="m-widget4__sub">
                                            Web Developer, Facebook Inc
                                        </span>
                                    </div>
                                    <div class="m-widget4__progress">
                                        <div class="m-widget4__progress-wrapper">
                                            <span class="m-widget17__progress-number">
                                                13%
                                            </span>
                                            <span class="m-widget17__progress-label">
                                                London
                                            </span>
                                            <div class="progress m-progress--sm">
                                                <div class="progress-bar bg-brand" role="progressbar" style="width: 13%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="13"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-widget4__ext">
                                        <a href="#" class="m-btn m-btn--hover-brand m-btn--pill btn btn-sm btn-secondary">
                                            Follow
                                        </a>
                                    </div>
                                </div>
                                <div class="m-widget4__item">
                                    <div class="m-widget4__img m-widget4__img--pic">
                                        <img src="../../assets/app/media/img/users/100_1.jpg" alt="">
                                    </div>
                                    <div class="m-widget4__info">
                                        <span class="m-widget4__title">
                                            Wiltor Delton
                                        </span>
                                        <br>
                                        <span class="m-widget4__sub">
                                            Project Manager, Amazon Inc
                                        </span>
                                    </div>
                                    <div class="m-widget4__progress">
                                        <div class="m-widget4__progress-wrapper">
                                            <span class="m-widget17__progress-number">
                                                93%
                                            </span>
                                            <span class="m-widget17__progress-label">
                                                New York
                                            </span>
                                            <div class="progress m-progress--sm">
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: 93%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="93"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-widget4__ext">
                                        <a href="#" class="m-btn m-btn--hover-brand m-btn--pill btn btn-sm btn-secondary">
                                            Follow
                                        </a>
                                    </div>
                                </div>
                                <div class="m-widget4__item">
                                    <div class="m-widget4__img m-widget4__img--pic">
                                        <img src="../../assets/app/media/img/users/100_6.jpg" alt="">
                                    </div>
                                    <div class="m-widget4__info">
                                        <span class="m-widget4__title">
                                            Sam Stone
                                        </span>
                                        <br>
                                        <span class="m-widget4__sub">
                                            Project Manager, Kilpo Inc
                                        </span>
                                    </div>
                                    <div class="m-widget4__progress">
                                        <div class="m-widget4__progress-wrapper">
                                            <span class="m-widget17__progress-number">
                                                50%
                                            </span>
                                            <span class="m-widget17__progress-label">
                                                New York
                                            </span>
                                            <div class="progress m-progress--sm">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="50"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-widget4__ext">
                                        <a href="#" class="m-btn m-btn--hover-brand m-btn--pill btn btn-sm btn-secondary">
                                            Follow
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="m_widget4_tab2_content"></div>
                        <div class="tab-pane" id="m_widget4_tab3_content"></div>
                    </div>
                </div>
            </div>
            <!--end:: Widgets/User Progress -->
        </div>
    </div>
    <!--End::Main Portlet-->
@endsection

@section('js')
    <script type="text/javascript">
    var profitShare = function() {
        if ($('#m_chart_profit_share').length == 0) {
            return;
        }

        var chart = new Chartist.Pie('#m_chart_profit_share', {
            series: [{
                    value: 10,
                    className: 'custom',
                    meta: {
                        color: mUtil.getColor('danger')
                    }
                },
                {
                    value: 90,
                    className: 'custom',
                    meta: {
                        color: mUtil.getColor('brand')
                    }
                }
            ],
            labels: ['未完成', '已完成']
        }, {
            donut: true,
            donutWidth: 17,
            showLabel: false
        });

        chart.on('draw', function(data) {
            if (data.type === 'slice') {
                // Get the total path length in order to use for dash array animation
                var pathLength = data.element._node.getTotalLength();

                // Set a dasharray that matches the path length as prerequisite to animate dashoffset
                data.element.attr({
                    'stroke-dasharray': pathLength + 'px ' + pathLength + 'px'
                });

                // Create animation definition while also assigning an ID to the animation for later sync usage
                var animationDefinition = {
                    'stroke-dashoffset': {
                        id: 'anim' + data.index,
                        dur: 1000,
                        from: -pathLength + 'px',
                        to: '0px',
                        easing: Chartist.Svg.Easing.easeOutQuint,
                        // We need to use `fill: 'freeze'` otherwise our animation will fall back to initial (not visible)
                        fill: 'freeze',
                        'stroke': data.meta.color
                    }
                };

                // If this was not the first slice, we need to time the animation so that it uses the end sync event of the previous animation
                if (data.index !== 0) {
                    animationDefinition['stroke-dashoffset'].begin = 'anim' + (data.index - 1) + '.end';
                }

                // We need to set an initial value before the animation starts as we are not in guided mode which would do that for us

                data.element.attr({
                    'stroke-dashoffset': -pathLength + 'px',
                    'stroke': data.meta.color
                });

                data.element.animate(animationDefinition, false);
            }
        });
    }
    profitShare();
    </script>
@endsection
