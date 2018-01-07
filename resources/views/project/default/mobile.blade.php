@extends('layouts.app')
@section('content')
    <div class="m-portlet">
        <form method="get" action="{{route('projects.index',['mid'=>request('mid')])}}"
              class="m-form m-form--fit m-form--group-seperator-dashed m-form-group-padding-bottom-10">
            <div class="m-portlet__body ">
                <div class="row m-form__group">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <select class="form-control m-bootstrap-select" name="status" id="m_form_status">
                                {!! project_status_select(request('status','')) !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <select class="form-control m-bootstrap-select" name="department_id" id="departments_id">
                                @if(check_user_role(null,'总部管理员'))
                                    {!! department_select(request('department_id'),2,1) !!}
                                @else
                                    {!! department_select(request('department_id'),2) !!}
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <input type="text" value="{{request('search')}}" class="form-control m-input" name="search" placeholder="关键字..." id="m_form_search">
                        </div>
                    </div>

                </div>
                <div class="row m-form__group">
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control m-bootstrap-select" name="phase_name" id="phase_name">
                                {!! project_phase_select2(request('phase_name')) !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control m-bootstrap-select" name="phase_status" id="phase_status">
                                {!! project_phases_status_select(request('phase_status',''),1) !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group ">
                            <input type="hidden" name="mid" value="{{request('mid')}}">
                            <button type="submit" class="btn btn-brand  m-btn m-btn--pill m-btn--icon">
                                <span>
                                    <i class="la la-search"></i>
                                    <span>搜索</span>
                                </span>
                            </button>
                            <a href="{{ route('projects.create',['mid'=>request('mid')] ) }}"
                               class="btn btn-primary  m-btn  m-btn m-btn--icon m-btn--pill ">
                                <span>
                                    <i class="fa fa-plus"></i>
                                    <span>
                                        新增
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <div class="row">
        @foreach($list as $project)
            <div class="col-xl-6">
                <div class="m-portlet">
                    <div class="m-portlet__body m-portlet__body--no-padding">
                        <div class="row m-row--no-padding m-row--col-separator-xl">
                            <div class="col-xl-12">
                                <div class="m-widget m--padding-20">
                                    <div class="m-widget-body">
                                        <div class="m-section m-section-none">
                                            <h3 class="m-section__heading">
                                                <a class="m-line-height-25" href="{{ route('project.board',['project'=>$project->id,'mid'=>request('mid')]) }}"
                                                   title="{{$project->title}}">{{str_limit($project->title,24,'...')}}</a>
                                                @if($project->status == 1 && $phase = get_project_current_phase($project))
                                                    <span class="m-badge {{project_status($phase->status,'class')}} m-badge--wide pull-right">
                                                        {{$phase->name}} : {{project_status($phase->status)}}
                                                    </span>
                                                @else
                                                    <span class="m-badge {{project_status($project->status,'class')}} m-badge--wide pull-right">
                                                    {{project_status($project->status)}}
                                                    </span>
                                                @endif
                                            </h3>
                                            <span class="m-section__sub">
                                            {{str_limit($project->remark,100,'...')}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet-action row">
                        <div class="btn-group m-btn-group col" role="group" aria-label="">
                            @php $check = check_project_owner($project,'company'); @endphp
                            <a href="{{ route('projects.edit',['project'=>$project->id,'mid'=>request('mid')]) }}"
                               class="m-btn--square m-btn--icon btn btn-secondary
                                col m-btn--icon-center @if(!$check || $project->status == 2) disabled @endif m-btn-left-bottom-border-none"  >
                                    <span>
                                        <i class="la la-edit"></i>
                                        <span>编辑</span>
                                    </span>
                            </a>
                            <a href="{{ route('projects.destroy',['project'=>$project->id,'mid'=>request('mid')]) }}"
                               class="m-btn--square m-btn--icon btn btn-secondary
                                col m-btn--icon-center action-delete @if(!$check) disabled @endif m-btn-bottom-border-none"  >
                                    <span>
                                        <i class="la la-trash"></i>
                                        <span>删除</span>
                                    </span>
                            </a>
                            <a href="{{ route('project.board',['project'=>$project->id,'mid'=>request('mid')]) }}"
                               class="m-btn--square m-btn--icon btn btn-secondary
                                col m-btn--icon-center  m-btn-right-bottom-border-none"  >
                                    <span>
                                        <i class="la la-eye"></i>
                                        <span>详情</span>
                                    </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $list->appends([
        'mid' => request('mid'),
        'status' => request('status') !== null ? 0 : '',
        'department_id' => request('department_id'),
        'search' => request('search'),
    ])->links('vendor.pagination.bootstrap-4') }}
    <!--begin::Modal-->
    <div class="modal fade" id="_modal" tabindex="-1" role="dialog" aria-labelledby="_ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!--end::Modal-->
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#departments_id,#m_form_status,#phase_name,#phase_status').selectpicker();
            $('a.action-delete').click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                mAppExtend.deleteData({
                    'url': url
                });
            });
        });
    </script>
@endsection