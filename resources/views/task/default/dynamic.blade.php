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
                        任务日志详情
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{{ get_redirect_url() }}"
                           class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
                        <span>
                            <i class="fa fa-reply"></i>
                            <span>
                                返回
                            </span>
                        </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right  m--margin-bottom-20">
                <div class="row align-items-center">
                    <div class="col-xl-12 order-2 order-xl-1">
                        <div class=" row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <b class=" m--font-brand">
                                        任务内容：<a href="{{route('tasks.show',['task'=>$task->id])}}" class="look-task">{{$task->content}} </a><br>
                                    </b>
                                    执行人：{{$task->leaderUser?$task->leaderUser->name : null}}
                                    ，开始时间：{{$task->start_at}}
                                    ，完成时间：{{$task->finished_at?$task->finished_at:'进行中'}}
                                    <br>
                                    所属项目：{{$task->project ? $task->project->title : null}}
                                </div>
                            </div>
                        </div>
                        <div class="m-form__group row align-items-center">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control m-input m-date"
                                           placeholder="上传日期" name="date" id="date"
                                           readonly value=""/>
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
    <div class="modal fade" id="_modal" tabindex="-1" role="dialog" aria-labelledby="_ModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
                                url: '{{ route("task.dynamics",['task'=>$task->id]) }}',
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
                        width: 340,
                        field: "content", sortable: false,
                        title: "日志内容", template: function (row) {
                            return '<a href="' + mAppExtend.laravelRoute('{{route_uri("dynamics.show")}}', {dynamic: row.id}) + '" class="action-show m-portlet__nav-link" title="详情">' +
                                (row.content.length > 50 ? row.content.substr(0, 50) + '...' : row.content)
                                + '</a>';
                        }
                    },  {
                        field: "created_at",
                        title: "上报时间",
                        width: 150
                    }, {
                        field: "actions",
                        width: 50,
                        title: "操作",
                        sortable: false,
                        // locked: {right: 'xl'},
                        overflow: 'visible',
                        template: function (row) {
                            var del = '<a href="' + mAppExtend.laravelRoute('{{route_uri("dynamics.destroy")}}', {dynamic: row.id}) + '" class="action-delete m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="删除"><i class="la la-trash"></i></a>';
                            return del;
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

                $('#date').on('change', function () {
                    datatable.search($(this).val(), 'date');
                }).val(typeof query.date !== 'undefined' ? query.date : '');

            };

            return {
                // public functions
                init: function () {
                    DataList();
                }
            };
        }();

        function formatTaskRepo(repo) {
            if (repo.loading) return repo.text;
            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>" + repo.content + "</div>";
            if (repo.project) {
                markup += "<div class='select2-result-repository__description'>所在项目：" + repo.project.title + "</div>";
            }
            markup += "</div></div>";
            return markup;
        }

        function formatTaskRepoSelection(repo) {
            return repo.content || repo.text;
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
            $(".look-task").click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                $('#_modal').modal('show');
                mAppExtend.ajaxGetHtml(
                    '#_modal .modal-content',
                    url,
                    {}, true);
            });
        });
    </script>
@endsection
