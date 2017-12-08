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
                                {{format_project_users($project->users,'name',2)}}...
                                </span>
                            </div>
                            <div class="col m--align-right">
                                <span class="m-widget1__number m--font-danger">
                                    {{$project->users()->count()}}
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
                                    今日任务数：+{{ $project->tasks()->whereBetween('created_at',[date_start_end(),date_start_end(null,'end')])->count() }}
                                </span>
                            </div>
                            <div class="col m--align-right">
                                <span class="m-widget1__number m--font-success">
                                    {{$project->tasks()->count()}}
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
                                    今日动态数：+{{ $project->dynamics()->whereBetween('created_at',[date_start_end(),date_start_end(null,'end')])->count() }}
                                </span>
                            </div>
                            <div class="col m--align-right">
                                <span class="m-widget1__number m--font-success">
                                    {{$project->dynamics()->count()}}
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
                                    今日协作数：+{{ $project->questions()->whereBetween('created_at',[date_start_end(),date_start_end(null,'end')])->count() }}
                                </span>
                            </div>
                            <div class="col m--align-right">
                                <span class="m-widget1__number m--font-success">
                                    {{$project->questions()->count()}}
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
                                    <img class="m-widget19__img" src="{{ avatar($project->leaderUser->avatar) }}" alt="">
                                </div>
                                <div class="m-widget19__info">
                                    <span class="m-widget19__username">
                                        负责人：{{$project->leaderUser->name}}
                                    </span>
                                    <br>
                                    <span class="m-widget19__time">
                                        {{$project->leaderUser->department?$project->leaderUser->department->name:null}}
                                    </span>
                                </div>
                            </div>
                            <h5 class="m-widget19__title">
                                <a href="{{ route('projects.show',['project'=>$project->id,'mid'=>request('mid')]) }}">
                                {{$project->title}}
                                </a>
                            </h5>
                            <div class="m-widget19__body">
                            {{str_limit($project->remark,150,'...')}}
                            </div>
                        </div>
                        <div class="m-widget19__action">
                            <a href="{{ route('projects.edit',['project'=>$project->id,'mid'=>request('mid')]) }}" class="btn btn-default m-btn m-btn--icon m-btn--icon-only"
                            data-container="body" data-toggle="m-tooltip" data-placement="top" data-original-title="编辑项目" title="编辑项目">
                                <i class="la la-edit"></i>
                            </a>
                            @if($project->status == 0 || $project->status == 2)
                            <a href="{{ route('project.start_or_finish',['project'=>$project->id,'status'=>1,'mid'=>request('mid')]) }}" id="project-start" class="btn btn-success m-btn m-btn--icon m-btn--icon-only"
                            data-container="body" data-toggle="m-tooltip" data-placement="top" data-original-title="启动项目" title="启动项目">
                                <i class="la la-play-circle"></i>
                            </a>
                            @endif

                            <!--  <a href="#" title="暂停" class="btn btn-warning m-btn m-btn--icon m-btn--icon-only">
                                <i class="la la-pause"></i>
                            </a>-->
                            @if($project->status == 1)
                            <a href="{{ route('project.start_or_finish',['project'=>$project->id,'status'=>2,'mid'=>request('mid')]) }}" id="project-finish" class="btn btn-primary m-btn m-btn--icon m-btn--icon-only"
                            data-container="body" data-toggle="m-tooltip" data-placement="top" data-original-title="设置已完成" title="设置已完成">
                                <i class="la la-power-off"></i>
                            </a>
                            @endif
                            <a href="{{route('projects.destroy',['project'=>$project->id,'calendar'=>1,'mid'=>request('mid')])}}" id="project-delete" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only " data-container="body" data-toggle="m-tooltip" data-placement="top" data-original-title="删除项目" title="删除项目">
                                <i class="la la-trash"></i>
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
                                    {{ $project->finishPrecent() }}%
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