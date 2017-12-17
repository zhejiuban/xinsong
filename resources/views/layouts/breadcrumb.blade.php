<div class="d-flex align-items-center">
    <div class="mr-auto">
		<?php $breadcrumb = breadcrumb(request('mid'));?>
        <h3 class="m-subheader__title m-subheader__title--separator  m--font-brand">
            {{end($breadcrumb)['title']?end($breadcrumb)['title']:'控制面板'}}
        </h3>
		@if(current($breadcrumb))
			<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
				<li class="m-nav__item m-nav__item--home">
				<a href="{{route('home')}}" class="m-nav__link m-nav__link--icon">
					<i class="m-nav__link-icon la la-home"></i>
				</a>
			</li>
			@foreach($breadcrumb as $k=>$v)
				<li class="m-nav__separator">
					/
				</li>
				<li class="m-nav__item">
					<a href="@if($loop->first || $loop->last) javascript:; @else {{menu_url_format($v['url'],['mid'=>$v['uniqid']])}} @endif" class="m-nav__link">
						<span class="m-nav__link-text">
							{{$v['title']}}
						</span>
					</a>
				</li>
			@endforeach
			</ul>
		@endif
    </div>
    <div>
		<div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
			<a href="javascript:;" class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--outline-2x m-btn--air m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle">
				<i class="la la-plus m--hide"></i>
				<i class="la la-ellipsis-h"></i>
			</a>
			<div class="m-dropdown__wrapper">
				<span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
				<div class="m-dropdown__inner">
					<div class="m-dropdown__body">
						<div class="m-dropdown__content">
							<ul class="m-nav">
								<li class="m-nav__section m-nav__section--first ">
									<span class="m-nav__section-text">
										快捷操作
									</span>
								</li>
								@if(check_company_admin() || check_user_role(null,'总部管理员'))
								<li class="m-nav__item">
									<a href="{{url('project/projects?mid=bd128edbfd250c9c5eff5396329011cd')}}" class="m-nav__link">
										<i class="m-nav__link-icon flaticon-share"></i>
										<span class="m-nav__link-text">
											项目汇总
										</span>
									</a>
								</li>
								@else
									<li class="m-nav__item">
										<a href="{{url('project/personal?mid=2083c82948c9226afa0026c2ff3933b3')}}" class="m-nav__link">
											<i class="m-nav__link-icon flaticon-share"></i>
											<span class="m-nav__link-text">
											我参与的项目
										</span>
										</a>
									</li>
								@endif
								@if(check_company_admin() || check_user_role(null,'总部管理员'))
								<li class="m-nav__item">
									<a href="{{url('dynamic/dynamics?mid=0fb5fefdfc8b8731301c222636bf2934')}}" class="m-nav__link">
										<i class="m-nav__link-icon flaticon-chat-1"></i>
										<span class="m-nav__link-text">
											工作日志汇总
										</span>
									</a>
								</li>
								@else
									<li class="m-nav__item">
										<a href="{{url('dynamic/personal?mid=9609eb5f4cae930f15d2deb1061fbe0d')}}" class="m-nav__link">
											<i class="m-nav__link-icon flaticon-chat-1"></i>
											<span class="m-nav__link-text">
											我的日志
										</span>
										</a>
									</li>
								@endif
								@if(check_company_admin() || check_user_role(null,'总部管理员'))
								<li class="m-nav__item">
									<a href="{{url('question/questions?mid=3affc334d19fc914c4d667a01848f55d')}}" class="m-nav__link">
										<i class="m-nav__link-icon flaticon-info"></i>
										<span class="m-nav__link-text">
											协作汇总
										</span>
									</a>
								</li>
								@else
								<li class="m-nav__item">
									<a href="{{url('question/personal?mid=c41b512b04286cc8f479176a466d23ac')}}" class="m-nav__link">
										<i class="m-nav__link-icon flaticon-info"></i>
										<span class="m-nav__link-text">
										我的协作
									</span>
									</a>
								</li>
								@endif
									<li class="m-nav__item">
									<a href="" class="m-nav__link">
										<i class="m-nav__link-icon flaticon-lifebuoy"></i>
										<span class="m-nav__link-text">
											帮助
										</span>
									</a>
								</li>
								<li class="m-nav__separator m-nav__separator--fit"></li>
								<li class="m-nav__item">
									<a title="返回上一页" href="javascript:history.back(-1);" class="btn btn-outline-primary m-btn m-btn--pill m-btn--wide btn-sm">
										<i class="la la-reply"></i>
									</a>
									<a title="刷新" href="javascript:location.reload();" class="btn btn-outline-primary m-btn m-btn--pill m-btn--wide btn-sm">
										<i class="la la-refresh"></i>
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
