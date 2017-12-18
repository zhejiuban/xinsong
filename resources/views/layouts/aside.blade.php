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
                            {{--<span class="m-menu__link-badge">--}}
                                {{--<span class="m-badge m-badge--danger">--}}
                                    {{--2--}}
                                {{--</span>--}}
                            {{--</span>--}}
                        </span>
                    </span>
                </a>
            </li>
            @foreach (get_user_menu() as $key => $value)
              <li class="m-menu__item  m-menu__item--submenu {{ active_class(if_uri_pattern(active_menu_pattern_str($value['url'])),'m-menu__item--expanded m-menu__item--open')}}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
                  <a  href="@if (isset($value['_child']) && $value['_child']) javascript:; @else {{url($value['url'],['mid'=>$value['uniqid']])}} @endif" target="{{$value['target']}}" class="m-menu__link m-menu__toggle">
                      <i class="m-menu__link-icon {{$value['icon_class']}}"></i>
                      <span class="m-menu__link-text">
                          {{$value['title']}}
                      </span>
                      <i class="m-menu__ver-arrow la la-angle-right"></i>
                  </a>
                  @if (isset($value['_child']) && $value['_child'])
                    <div class="m-menu__submenu">
                        <span class="m-menu__arrow"></span>
                        <ul class="m-menu__subnav">
                            @foreach ($value['_child'] as $sk => $sv)
                              <li class="m-menu__item {{ active_class(check_active_url($sv['url']),'m-menu__item--active')}}" aria-haspopup="true" >
                                  <a  href="{{menu_url_format($sv['url'],['mid'=>$sv['uniqid']])}}" target="{{$sv['target']}}" class="m-menu__link ">
                                      <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                          <span></span>
                                      </i>
                                      <span class="m-menu__link-text">
                                          {{$sv['title']}}
                                      </span>
                                  </a>
                              </li>
                            @endforeach
                        </ul>
                    </div>
                  @endif
              </li>
            @endforeach
        </ul>
    </div>

    <!-- END: Aside Menu -->
</div>
<!-- END: Left Aside -->
