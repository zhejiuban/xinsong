@extends('layouts.app')

@section('content')
    <div class="m-portlet m-portlet--mobile m--margin-bottom-0">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-statistics"></i>
                </span>
                    <h3 class="m-portlet__head-text m--font-primary">
                        考核记录
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        @if(check_permission('assessment/assessments/create'))
                            <a href="{{ menu_url_format(route('assessments.create'),['mid'=>request('mid')]) }}"
                               data-toggle="modal" data-target="#m_role_modal"
                               class="btn btn-sm btn-primary m-btn m-btn--icon m-btn--air m-btn--pill">
                                <span>
                                    <i class="fa fa-plus"></i>
                                    <span>
                                        新增
                                    </span>
                                </span>
                            </a>
                        @endif
                    </li>
                    <li class="m-portlet__nav-item">
                        @if(check_permission('assessment/scores/create'))
                            <button data-toggle="modal" data-target="#m_power_modal"
                                    class="btn btn-sm btn-primary m-btn m-btn--icon m-btn--air m-btn--pill">
                                <span>
                                    <i class="fa fa-cog"></i>
                                    <span>
                                        考核初始化
                                    </span>
                                </span>
                            </button>
                        @endif
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="{{ menu_url_format(route('assessments.export'),['mid'=>request('mid')])  }}" class="btn btn-info btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
                            <span>
                                <i class="fa fa-download"></i>
                                <span>
                                    全部导出
                                </span>
                            </span>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="{{ menu_url_format(route('assessments.export'),['mid'=>request('mid')])  }}" id="whereExport" class="btn btn-info btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air"
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
            <form id="exportForm" action="{{ menu_url_format(route('assessments.export'),['mid'=>request('mid')])  }}" method="get">
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right  m--margin-bottom-20">
                <div class="row align-items-center">
                    <div class="col-xl-12 order-2 order-xl-1">
                        <div class="m-form__group row align-items-center">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <select name="department_id" id="department_id" class="form-control m-input select2 m-select2">
                                        {!! department_select(0,1,1) !!}
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" placeholder="开始时间" readonly="true" name="start" id="start" class="form-control m-input month">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" placeholder="结束时间" readonly="true" name="end" id="end" class="form-control m-input month">
                                </div>
                            </div>
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
            <!--begin::Modal-->
            <div class="modal fade" id="m_role_modal" tabindex="-1" role="dialog" aria-labelledby="RoleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    </div>
                </div>
            </div>
            <div class="modal fade" id="m_role_modal_edit" role="dialog" aria-labelledby="RoleModalEditLabel"
                 aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    </div>
                </div>
            </div>
            <div class="modal fade" id="m_power_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    </div>
                </div>
            </div>
            <!--end::Modal-->
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        function countScore(data){
            var sum = 0;
            $.each(data, function(index, val) {
                 sum += val.score;
            });
            return sum;
        }
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
                                url: '{{ url("assessment/assessments") }}',
                                param: {}
                            }
                        },
                        pageSize: 10,
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
                        field: "name",
                        title: "姓名",
                        filterable: false,template: function (row) {
                            if (row.avatar) {
                                output = '<div class="m-card-user m-card-user--sm">\
                                        <div class="m-card-user__pic">\
                                            <img src="/'+row.avatar+'" class="m--img-rounded m--marginless" alt="photo">\
                                        </div>\
                                        <div class="m-card-user__details">\
                                            <span class="m-card-user__name">' + row.name + '</span>\
                                        </div>\
                                    </div>';
                            } else {
                                var stateNo = mUtil.getRandomInt(0, 7);
                                var states = ['success', 'brand', 'danger', 'accent', 'warning', 'metal', 'primary', 'info'];
                                var state = states[stateNo];

                                output = '<div class="m-card-user m-card-user--sm">\
                                        <div class="m-card-user__pic">\
                                            <div class="m-card-user__no-photo m--bg-fill-' + state + '"><span>' + row.name.substring(0, 1) + '</span></div>\
                                        </div>\
                                        <div class="m-card-user__details">\
                                            <span class="m-card-user__name">' + row.name + '</span>\
                                        </div>\
                                    </div>';
                            }

                            return output;
                        }
                    }, {
                        field: "department_id",
                        title: "所属部门",
                        template: function (row) {
                            if (row.department || row.company) {
                                 return (row.company ? row.company.name : '') + (row.department && row.department.level == 3 ? '/' + row.department.name : '');
                            }
                        }
                    }, {
                        field: "score",
                        title: "考核结果", 
                        width: 150,
                        sortable: false
                    }, {
                        field: "actions",
                        width: 110,
                        title: "考核详情",
                        sortable: false,
                        // locked: {right: 'xl'},
                        overflow: 'visible',
                        template: function (row) {
                            var edit = '<a href="' + mAppExtend.laravelRoute('{{route_uri("user.assessments")}}', {user: row.id}) + '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="详情">\
								<i class="la la-eye"></i>\
							</a>';
                            return edit;
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

                $('#department_id').on('change', function () {
                  // shortcode to datatable.getDataSourceParam('query');
                  var query = datatable.getDataSourceQuery();
                  query.department_id = $(this).val();
                  // shortcode to datatable.setDataSourceParam('query', query);
                  datatable.setDataSourceQuery(query);
                  datatable.load();
                }).val(typeof query.department_id !== 'undefined' ? query.department_id : '');
                $('#start').on('change', function () {
                  // shortcode to datatable.getDataSourceParam('query');
                  var query = datatable.getDataSourceQuery();
                  query.start = $(this).val();
                  // shortcode to datatable.setDataSourceParam('query', query);
                  datatable.setDataSourceQuery(query);
                  datatable.load();
                }).val(typeof query.start !== 'undefined' ? query.start : '');
                $('#end').on('change', function () {
                  // shortcode to datatable.getDataSourceParam('query');
                  var query = datatable.getDataSourceQuery();
                  query.end = $(this).val();
                  // shortcode to datatable.setDataSourceParam('query', query);
                  datatable.setDataSourceQuery(query);
                  datatable.load();
                }).val(typeof query.end !== 'undefined' ? query.end : '');
            };

            return {
                // public functions
                init: function () {
                    DataList();
                }
            };
        }();

        jQuery(document).ready(function () {
            DatatableAjax.init(); //加载数据列表
            mAppExtend.datePickerInstance('.month',{
              language: "zh-CN",
              todayBtn: false,
              clearBtn: true,
              // orientation: "bottom left",
              zIndexOffset: 200,
              todayHighlight: true,
              startView: 1,
              maxViewMode:2,
              minViewMode:1,
              format: "yyyy-mm",
              autoclose: true,
              templates: {
                  leftArrow: '<i class="la la-angle-left"></i>',
                  rightArrow: '<i class="la la-angle-right"></i>'
              }
            });
            //添加角色弹窗打开时，加载添加页面
            $('#m_role_modal').on('shown.bs.modal', function (e) {
                mAppExtend.ajaxGetHtml(
                    '#m_role_modal .modal-content',
                    "{{ route('assessments.create') }}",
                    {}, true);
            })
            $('.m_datatable').on('click', 'a.action-edit', function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                $('#m_role_modal_edit').modal('show');
                mAppExtend.ajaxGetHtml(
                    '#m_role_modal_edit .modal-content',
                    url,
                    {}, true);
            });
            $('.m_datatable').on('click', 'a.action-delete', function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                mAppExtend.deleteData({
                    'url':url,
                    'data':{},
                    'callback':function(){
                        datatable.load();
                    }
                });
            });
            
            $('#m_power_modal').on('shown.bs.modal', function (e) {
                mAppExtend.ajaxGetHtml(
                    '#m_power_modal .modal-content',
                    "{{ route('scores.create') }}",
                    {}, true);
            })

            $("#whereExport").click(function(event) {
                event.preventDefault();
                $("#exportForm").submit();
            });

        });
    </script>
@endsection
