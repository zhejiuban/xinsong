@php $user = get_current_login_user_info(true) @endphp
<li class="m-nav__item m-topbar__notifications m-topbar__notifications--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-right  m-dropdown--mobile-full-width" data-dropdown-toggle="click" data-dropdown-persistent="true">
    <a href="javascript:;" class="m-nav__link m-dropdown__toggle" id="m_topbar_notification_icon">
        @if($user->unreadNotifications()->count()) <span class="m-nav__link-badge m-animate-blink m-badge m-badge--dot m-badge--dot-small m-badge--danger"></span>@endif
        <span class="m-nav__link-icon @if($user->unreadNotifications()->count()) m-animate-shake @endif">
            <i class="flaticon-music-2"></i>
        </span>
    </a>
    <div class="m-dropdown__wrapper">
        <span class="m-dropdown__arrow m-dropdown__arrow--right"></span>
        <div class="m-dropdown__inner">
            <div class="m-dropdown__header m--align-center" style="background: url({{ asset('assets/app/media/img/misc/notification_bg.jpg') }}); background-size: cover;">
                <span class="m-dropdown__header-title">
                    {{intval($user->notification_count)}} 个未读消息
                </span>
                <span class="m-dropdown__header-subtitle">
                    消息提醒
                </span>
            </div>
            <div class="m-dropdown__body">
                <div class="m-dropdown__content">
                    <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--brand" role="tablist">
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link active" data-toggle="tab" href="#topbar_notifications_notifications" role="tab">
                                系统
                            </a>
                        </li>
                       {{-- <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#topbar_notifications_events" role="tab">
                                任务
                            </a>
                        </li>--}}
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="topbar_notifications_notifications" role="tabpanel">
                            <div class="m-scrollable" data-scrollable="true" data-max-height="250" data-mobile-max-height="200">
                                @if($user->unreadNotifications()->count())
                                <div class="m-list-timeline m-list-timeline--skin-light">
                                    <div class="m-list-timeline__items">
                                        @foreach($user->unreadNotifications()->get() as $notify)
                                        <div class="m-list-timeline__item">
                                            <span class="m-list-timeline__badge -m-list-timeline__badge--state-success"></span>
                                            <span class="m-list-timeline__text">
                                               <a href="{{route('notifications.read',['notification'=>$notify->id])}}">{{$notify->data['content']}}</a>
                                            </span>
                                            <span class="m-list-timeline__time">
                                                {{$notify->created_at->diffForHumans()}}
                                            </span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @else
                                    <div class="m-stack m-stack--ver m-stack--general" style="min-height: 180px;">
                                        <div class="m-stack__item m-stack__item--center m-stack__item--middle">
                                            <span class="">
                                                暂无消息
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane" id="topbar_notifications_events" role="tabpanel">
                            <div class="m-scrollable" data-scrollable="true" data-max-height="250" data-mobile-max-height="200">
                            <div class="m-stack m-stack--ver m-stack--general" style="min-height: 180px;">
                                <div class="m-stack__item m-stack__item--center m-stack__item--middle">
                                    <span class="">
                                        暂无消息
                                    </span>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</li>