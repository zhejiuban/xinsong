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
                        任务列表
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right  m--margin-bottom-20">
                <div class="row align-items-center">
                    <div class="col-xl-12 order-2 order-xl-1">
                        <div class="m-form__group row align-items-center">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select name="project_id" class="form-control" id="project_id">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <select class="form-control m-bootstrap-select" id="m_form_status">
                                        <option value="">
                                            所有状态
                                        </option>
                                        <option value="0">
                                            进行中
                                        </option>
                                        <option value="1">
                                            已完成
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text" class="form-control m-input" placeholder="关键字..."
                                               id="m_form_search">
                                        <span class="m-input-icon__icon m-input-icon__icon--left">
  									<span>
  										<i class="fa fa-search"></i>
  									</span>
  								</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-separator m-separator--dashed"></div>
            <!--end: Search Form -->
            <!--begin: Datatable -->
            <div class="m_datatable" id="ajax_data"></div>
            <!--end: Datatable -->
        </div>
    </div>
    <!--begin::Modal-->
    <div class="modal fade" id="_modal" tabindex="-1" role="dialog" aria-labelledby="_ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!--end::Modal-->
    <!--begin::Modal-->
    <div class="modal fade" id="_editModal" tabindex="-1" role="dialog" aria-labelledby="_EditModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            </div>
        </div>
    </div>
    <!--end::Modal-->
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
                                url: '{{ route("tasks.index") }}',
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
                        title: "状态", width: 60,
                        template: function (row) {
                            var status = @json(config('common.task_status'));
                            var rowStatus = Number(row.status);
                            return '<span class="m-badge ' + status[rowStatus].class + ' m-badge--wide">' + status[rowStatus].title + '</span>';
                        }
                    }, {
                        width: 340,
                        field: "content", sortable: false,
                        title: "任务内容", template: function (row) {
                            return '<a href="' + mAppExtend.laravelRoute('{{route_uri("tasks.show")}}', {
                                    task: row.id,
                                    mid: "{{request('mid')}}"
                                }) + '" class="action-show m-portlet__nav-link" title="详情">' +
                                (row.content.length > 50 ? row.content.substr(0, 50) + '...' : row.content)
                                + '</a>';
                        }
                    },{
                        field: "project_id",
                        width: 240,
                        title: "所属项目", sortable: false,
                        template: function (row) {
                            if (row.project) {
                                return row.project.title;
                            }
                        }
                    }, {
                        field: "leader", sortable: false,
                        title: "执行人", template: function (row) {
                            if (row.leader_user) {
                                return row.leader_user.name;
                            }
                        }
                    },  {
                        field: "start_at",
                        title: "开始时间"
                    }, {
                        field: "finished_at",
                        title: "完成时间"
                    }, {
                        field: "user_id", sortable: false,
                        title: "分配人", template: function (row) {
                            if (row.user) {
                                return row.user.name;
                            }
                        }
                    }, {
                        field: "actions",
                        width: 110,
                        title: "操作",
                        sortable: false,
                        // locked: {right: 'xl'},
                        overflow: 'visible',
                        template: function (row) {
                            var del = '<a href="' + mAppExtend.laravelRoute('{{route_uri("tasks.destroy")}}', {task: row.id}) + '" class="action-delete m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="删除"><i class="la la-trash"></i></a>';
                            var edit = '<a href="' + mAppExtend.laravelRoute('{{route_uri("tasks.edit")}}', {
                                task: row.id,
                                mid: "{{request('mid')}}"
                            }) + '" class="action-edit m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="编辑">\
                <i class="la la-edit"></i></a>';
                            if (row.status == 1) {
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
                $('#project_id').on('change', function () {
                    datatable.search($(this).val(), 'project_id');
                }).val(typeof query.project_id !== 'undefined' ? query.project_id : '');

            };

            return {
                // public functions
                init: function () {
                    DataList();
                }
            };
        }();

        function formatRepo(repo) {
            if (repo.loading) return repo.text;
            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>" + repo.name + "</div>";
            if (repo.department) {
                markup += "<div class='select2-result-repository__description'>所在部门：" + repo.department.name + "</div>";
            }
            markup += "</div></div>";
            return markup;
        }

        function formatRepoSelection(repo) {
            return repo.name || repo.text;
        }

        function formatProjectRepo(repo) {
            if (repo.loading) return repo.text;
            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>" + repo.title + "</div>";
            markup += "<div class='select2-result-repository__description'>项目编号：" + repo.no + "</div>";
            markup += "</div></div>";
            return markup;
        }

        function formatProjectRepoSelection(repo) {
            return repo.title || repo.text;
        }

        jQuery(document).ready(function () {
            $('#m_form_status').selectpicker();
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
            $('.m_datatable').on('click', 'a.action-edit,a.action-show', function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                $('#_modal').modal('show');
                mAppExtend.ajaxGetHtml(
                    '#_modal .modal-content',
                    url,
                    {}, true);
            });
            var $projectSelector = $("#project_id").select2({
                language: 'zh-CN',
                placeholder: "输入项目编号、名称等关键字搜索，选择项目",
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{route('projects.selector.data')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
                            per_page: {{config('common.page.per_page')}}
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.data,
                            pagination: {
                                more: (params.page * data.per_page) < data.total
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 0,
                templateResult: formatProjectRepo, // omitted for brevity, see the source of this page
                templateSelection: formatProjectRepoSelection // omitted for brevity, see the source of this page
            });
        });
    </script>
@endsection
