@extends('layouts.app')

@section('content')
    <div class="m-portlet m-portlet--mobile m--margin-bottom-0">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-interface-7"></i>
                </span>
                    <h3 class="m-portlet__head-text m--font-primary">
                        项目列表
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{{ menu_url_format(route('projects.create'),['mid'=>request('mid')])  }}"
                           class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
                        <span>
                            <i class="fa fa-plus"></i>
                            <span>
                                新增
                            </span>
                        </span>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="{{ menu_url_format(route('projects.export'),['mid'=>request('mid')])  }}"
                           class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air"
                        target="_blank">
                        <span>
                            <i class="fa fa-download"></i>
                            <span>
                                全部导出
                            </span>
                        </span>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="{{ menu_url_format(route('projects.export'),['mid'=>request('mid')])  }}" id="whereExport" class="btn btn-info btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air"
                            target="_blank">
                            <span>
                                <i class="fa fa-download"></i>
                                <span>
                                    按条件导出
                                </span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <form id="exportForm" action="{{ menu_url_format(route('projects.export'),['mid'=>request('mid')])  }}" method="get">
            <div class="m-form m-form--label-align-right  m--margin-bottom-20">

                <div class="m-form__group row align-items-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control m-bootstrap-select" name="status" id="m_form_status">
                                {!! project_status_select() !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control m-bootstrap-select" name="department_id" id="departments_id">
                                @if(check_user_role(null,'总部管理员'))
                                    {!! department_select(0,2,1) !!}
                                @else
                                    {!! department_select(0,2) !!}
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="m-input-icon m-input-icon--left">
                                <input type="text" name="search" class="form-control m-input" placeholder="关键字..." id="m_form_search">
                                <span class="m-input-icon__icon m-input-icon__icon--left">
                                    <span>
                                        <i class="fa fa-search"></i>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control m-bootstrap-select" name="phase_name" id="phase_name">
                                {!! project_phase_select2() !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control m-bootstrap-select" name="phase_status" id="phase_status">
                                {!! project_phases_status_select('',1) !!}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                             <select name="leader" class="form-control " id="user_id">
                                <option value="">请选择新松负责人</option>
                             </select>
                        </div>  
                    </div>
                </div>
            </div>
            </form>
            <div class="m-separator m-separator--dashed"></div>
            <!--end: Search Form -->
            <!--begin: Datatable -->
            <div class="m_datatable" id="ajax_data"></div>
            <!--end: Datatable -->
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        
        var datatable;
        var DatatableAjax = function () {
            //== Private functions
            // basic demo
            var DataList = function () {

                datatable = $('.m_datatable').mDatatable({
                    // datasource definition
                    data: {
                        type: 'remote',
                        source: {
                            type: 'get',
                            read: {
                                url: '{{ route("projects.index") }}',
                                param: {}
                            }
                        },
                        pageSize: {{config('common.page.per_page',10)}},
                        saveState: {
                            cookie: true,
                            webstorage: true
                        },
                        serverPaging: true,
                        serverFiltering: true,
                        serverSorting: true
                    },

                    // layout definition
                    layout: {
                        theme: 'default', // datatable theme
                        class: '', // custom wrapper class
                        //scroll: true, // enable/disable datatable scroll both horizontal and vertical when needed.
                        // height:600,
                        footer: false // display/hide footer
                    },

                    // column sorting
                    sortable: true,

                    // column based filtering
                    filterable: false,

                    pagination: true,

                    // columns definition
                    columns: [{
                        field: "id",
                        title: "ID",
                        width: 40,
                        textAlign: 'center',
                        sortable: false,
                        selector: {class: 'm-checkbox--solid m-checkbox--brand'}
                    }, {
                        field: "status",
                        title: "状态", width: 150,
                        template: function (row) {
                            var status = @json(config('common.project_status'));
                            var rowStatus = Number(row.status);
                            if (row.status == 1) {
                                mAppExtend.select2Instance();
                                var phase = mAppExtend.projectPhases(row.phases);
                                var phaseStatus = Number(phase['status']);
                                return '<span class="m-badge ' + status[phaseStatus].class + ' m-badge--wide">' + phase['name'] + ':' + status[phaseStatus].title + '</span>';
                            } else {
                                return '<span class="m-badge ' + status[rowStatus].class + ' m-badge--wide">' + status[rowStatus].title + '</span>';
                            }
                        }
                    }, {
                        field: "no",
                        title: "编号"
                    }, {
                        field: "title",
                        width: 240,
                        title: "项目名称",
                        template: function (row) {
                            return '<a href="' + mAppExtend.laravelRoute('{{route_uri("project.board")}}', {
                                project: row.id,
                                mid: "{{request('mid')}}"
                            }) + '" class="action-show m-portlet__nav-link" title="项目概况">' + row.title + '</a>';
                        }
                    },{
                        field: "plans", sortable: false,
                        title: "实施计划", template: function (row) {
                            return row.plans.length > 0 ? '<a href="' + mAppExtend.laravelRoute('{{route_uri("plans.index")}}', {
                                project: row.id,
                                mid: "{{request('mid')}}"
                            }) + '" title="项目实施计划"><span class="m-badge m-badge--brand m-badge--wide">详情</span></a>':'';
                        }
                    }, {
                        field: "leader", sortable: false,
                        title: "新松负责人", template: function (row) {
                            if (row.leader_user) {
                                return row.leader_user.name;
                            }
                        }
                    }, {
                        field: "subcompany_leader", sortable: false,
                        title: "办事处负责人", template: function (row) {
                            if (row.company_user) {
                                return row.company_user.name;
                            }
                        }
                    }, {
                        field: "agent", sortable: false,
                        title: "现场负责人", template: function (row) {
                            if (row.agent_user) {
                                return row.agent_user.name;
                            }
                        }
                    }, {
                        field: "department_id",
                        title: "所属办事处",
                        sortable: false, template: function (row) {
                            if (row.department) {
                                return row.department.name;
                            }
                        }
                    }, {
                        field: "customers",
                        title: "客户对接人"
                    }, {
                        field: "customers_tel",
                        title: "客户对接人电话"
                    }, {
                        field: "customers_address",
                        title: "客户地址"
                    }, {
                        field: "actions",
                        width: 110,
                        title: "操作",
                        sortable: false,
                        // locked: {right: 'xl'},
                        overflow: 'visible',
                        template: function (row) {
                            var del = '<a href="' + mAppExtend.laravelRoute('{{route_uri("projects.destroy")}}', {project: row.id}) + '" class="action-delete m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="删除"><i class="la la-trash"></i></a>';
                            var edit = '<a href="' + mAppExtend.laravelRoute('{{route_uri("projects.edit")}}', {
                                project: row.id,
                                mid: "{{request('mid')}}"
                            }) + '" class="action-edit m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="编辑"><i class="la la-edit"></i></a>';
                            if (row.status == 2) {
                                edit = '';
                            }
                            return edit + del;
                        }
                    }]
                });

                var query = datatable.getDataSourceQuery();

                $('#m_form_search').on('keyup', function (e) {
                    // shortcode to datatable.getDataSourceParam('query');
                    var query = datatable.getDataSourceQuery();
                    query.search = $(this).val();
                    // shortcode to datatable.setDataSourceParam('query', query);
                    datatable.setDataSourceQuery(query);
                    datatable.load();
                }).val(query.search);

                $('#m_form_status').on('change', function () {
                    datatable.search($(this).val(), 'status');
                }).val(typeof query.status !== 'undefined' ? query.status : '');

                $('#departments_id').on('change', function () {
                    datatable.search($(this).val(), 'department_id');
                }).val(typeof query.department_id !== 'undefined' ? query.department_id : '');

                $('#phase_name').on('change', function () {
                    datatable.search($(this).val(), 'phase_name');
                }).val(typeof query.phase_name !== 'undefined' ? query.phase_name : '');

                $('#phase_status').on('change', function () {
                    datatable.search($(this).val(), 'phase_status');
                }).val(typeof query.phase_status !== 'undefined' ? query.phase_status : '');

                $('#user_id').on('change', function () {
                    datatable.search($(this).val(), 'leader');
                }).val(typeof query.leader !== 'undefined' ? query.leader : '');
            };

            return {
                // public functions
                init: function () {
                    DataList();
                }
            };
        }();

        jQuery(document).ready(function () {
            $('#m_form_status,#phase_name').selectpicker();
            $('#departments_id,#phase_status').selectpicker();
            DatatableAjax.init();
            $('.m_datatable').on('click', 'a.action-delete', function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                mAppExtend.deleteData({
                    'url': url,
                    'callback': function () {
                        datatable.load();
                    }
                });
            });
            
            $("#whereExport").click(function(event) {
                event.preventDefault();
                $("#exportForm").submit();
            });

            $.get("{{route('users.selector.data',['type'=>'headquarters'])}}",{
                'per_page':100
            }, function(data) {
                var str = '';
                $.each(data.data, function(index, val) {
                    str += '<option value="'+val.id+'">'+val.name+'</option>';
                });
                $('#user_id').append(str);
                $('#user_id').selectpicker();
            });
            
        });
    </script>
@endsection
