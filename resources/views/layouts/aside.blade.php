<!-- BEGIN: Left Aside -->
<div id="m_aside_left" class="m-grid__item  m-aside-left  m-aside-left--skin-dark ">
    <!-- BEGIN: Aside Menu -->
    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " data-menu-vertical="true" data-menu-scrollable="false" data-menu-dropdown-timeout="500" >
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
            <li class="m-menu__item  {{ active_class(if_route('home'),'m-menu__item--active') }}" aria-haspopup="true" >
                <a  href="{{ url('home') }}" class="m-menu__link ">
                    <i class="m-menu__link-icon flaticon-line-graph"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-wrap">
                            <span class="m-menu__link-text">
                                控制面板
                            </span>
                            <span class="m-menu__link-badge">
                                <span class="m-badge m-badge--danger">
                                    2
                                </span>
                            </span>
                        </span>
                    </span>
                </a>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ active_class(if_uri_pattern('user/*'),'m-menu__item--expanded m-menu__item--open')}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a  href="javascript:;" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon flaticon-map"></i>
                    <span class="m-menu__link-text">
                        组织权限中心
                    </span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item " aria-haspopup="true" >
                            <a  href="" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">
                                    权限管理
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ active_class(if_uri_pattern('user/departments*'),'m-menu__item--active')}}" aria-haspopup="true" >
                            <a  href="{{route('departments.index')}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">
                                    组织机构
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item {{ active_class(if_uri_pattern('user/groups*'),'m-menu__item--active')}}" aria-haspopup="true" >
                            <a  href="{{route('groups.index')}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">
                                    角色管理
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="m-menu__item  m-menu__item--submenu {{ active_class(if_uri_pattern('system/*'),'m-menu__item--expanded m-menu__item--open')}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                <a  href="javascript:;" class="m-menu__link m-menu__toggle">
                    <i class="m-menu__link-icon flaticon-settings"></i>
                    <span class="m-menu__link-text">
                        系统设置
                    </span>
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                </a>
                <div class="m-menu__submenu">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        <li class="m-menu__item  {{ active_class(if_uri_pattern('system/menus*'),'m-menu__item--active')}}" aria-haspopup="true" >
                            <a  href="{{route('menus.index')}}" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">
                                    菜单管理
                                </span>
                            </a>
                        </li>
                        <li class="m-menu__item " aria-haspopup="true" >
                            <a  href="components/base/bootstrap-notify.html" class="m-menu__link ">
                                <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">
                                    参数设置
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    <!-- END: Aside Menu -->
</div>
<!-- END: Left Aside -->
