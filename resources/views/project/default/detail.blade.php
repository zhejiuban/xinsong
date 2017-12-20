<!-- 基本信息 -->
<!--Begin::Main Portlet-->
<div class="m-portlet">
    <div class="m-portlet__body  m-portlet__body--no-padding">
        <div class="row m-row--no-padding m-row--col-separator-xl">
            <div class="col-xl-4">
                <!--begin:: Widgets/Daily Sales-->
                <div class="m-widget1">
                    <div class="m-widget19">
                        <div class="m-widget19__content">
                            <h5 class="m-widget19__title" style="padding:1.1rem 0;">
                                <a class="look-project" href="{{ route('projects.show',['project'=>$project->id,'mid'=>request('mid')]) }}">
                                    {{$project->title}}
                                </a>
                            </h5>
                            <div class="m-widget19__body">
                                {{str_limit($project->remark,60,'...')}}
                            </div>
                            <br>
                            <span class="m-widget19__username">
                                新松负责人：{{$project->leaderUser ? $project->leaderUser->name : null}}
                            </span>
                            <br>
                            <span class="m-widget19__username">
                                项目负责人：{{$project->companyUser ? $project->companyUser->name : null}}
                            </span>
                            <br>
                            <span class="m-widget19__username">
                                现场负责人：{{$project->agentUser?$project->agentUser->name:null}}
                                @if(check_project_owner($project, 'edit'))
                                <a href="{{route('project.agent_leader.update',['project'=>$project->id])}}" title="更改现场负责人"
                                   id="agent-leader-change" class="btn btn-outline-primary m-btn m-btn--icon btn-sm m-btn--icon-only m-btn--pill">
                                    <i class="la la-edit"></i>
                                </a>
                                @endif
                            </span>
                            <div class="m-widget1__item">
                                <h3 class="m-widget1__title">
                                    参与人：
                                </h3>
                                <span class="m-widget1__desc">
                                {{format_project_users($project->users,'name')}}
                                </span>
                            </div>
                        </div>
                        <div class="m-widget19__action">
                            @if(check_project_owner($project,'del'))
                                <a href="{{ route('projects.edit',['project'=>$project->id,'mid'=>request('mid')]) }}"
                                   class="btn btn-default m-btn m-btn--icon m-btn--icon-only"
                                   data-container="body" data-toggle="m-tooltip" data-placement="top"
                                   data-original-title="编辑项目" title="编辑项目">
                                    <i class="la la-edit"></i>
                                </a>
                                <a href="{{route('projects.destroy',['project'=>$project->id,'calendar'=>1,'mid'=>request('mid')])}}"
                                   id="project-delete" class="btn btn-danger m-btn m-btn--icon m-btn--icon-only "
                                   data-container="body" data-toggle="m-tooltip" data-placement="top"
                                   data-original-title="删除项目" title="删除项目">
                                    <i class="la la-trash"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <!--end:: Widgets/Daily Sales-->
            </div>
            <div class="col-xl-4">
                <!--begin:: Widgets/Stats2-1 -->
                <div class="m-widget1">

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
                                    日志
                                </h3>
                                <span class="m-widget1__desc">
                                    今日日志数：+{{ $project->dynamics()->whereBetween('created_at',[date_start_end(),date_start_end(null,'end')])->count() }}
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
                                    问题
                                </h3>
                                <span class="m-widget1__desc">
                                    今日问题数：+{{ $project->questions()->whereBetween('created_at',[date_start_end(),date_start_end(null,'end')])->count() }}
                                </span>
                            </div>
                            <div class="col m--align-right">
                                <span class="m-widget1__number m--font-success">
                                    {{$project->questions()->count()}}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="m-widget1__item">
                        <div class="row m-row--no-padding align-items-center">
                            <div class="col">
                                <h3 class="m-widget1__title">
                                    文档
                                </h3>
                                <span class="m-widget1__desc">
                                    今日文档数：+{{ $project->files()->whereBetween('created_at',[date_start_end(),date_start_end(null,'end')])->count() }}
                                </span>
                            </div>
                            <div class="col m--align-right">
                                <span class="m-widget1__number m--font-success">
                                    {{$project->files()->count()}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end:: Widgets/Stats2-1 -->
            </div>
            <div class="col-xl-4">
                <!--begin:: Widgets/Profit Share-->
                <div class="m-widget14">
                    <div class="m-widget14__header">
                        <h3 class="m-widget14__title">
                            项目状态
                        </h3>
                        <span class="m-widget14__desc">
                        </span>
                    </div>

                    <div class="row">
                        <div class="m-list-timeline col">
                            <div class="m-list-timeline__items">
                                @foreach($project->phases as $phase)
                                    <div class="m-list-timeline__item">
                                        <span class="m-list-timeline__badge m-list-timeline__badge--{{project_phases_status($phase->status,'color')}}"></span>
                                        <span class="m-list-timeline__text">
                                        <a href="{{route('project.phases.update',['phase'=>$phase->id])}}" class="phase_status" data-container="body" data-html="true" data-toggle="m-tooltip"
                                           data-placement="top"
                                           data-original-title="开始：{{$phase->started_at}}<br>完成：{{$phase->finished_at}}">{{$phase->name}}</a>
                                        <span class="m-badge m-badge--{{project_phases_status($phase->status,'color')}} m-badge--wide">
                                            {{project_phases_status($phase->status,'title')}}
                                        </span> 
                                    </span>

                                    </div>
                                @endforeach
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
<!--begin::Modal-->
<div class="modal fade" id="_phaseModal" tabindex="-1" role="dialog" aria-labelledby="_PhaseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
<!--end::Modal-->
<!--begin::Modal-->
<div class="modal fade" id="_lookProjectModal" tabindex="-1" role="dialog" aria-labelledby="_ProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>
<!--end::Modal-->