@extends('layouts.app')

@section('content')
    <div class="m-portlet m-portlet--mobile m--margin-bottom-0">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-info"></i>
                </span>
                    <h3 class="m-portlet__head-text m--font-primary">
                        我发布的问题
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{{ route('question.personal.create',['back'=>'personal','mid'=>'5e5fa7160f2d8bf507f11ac18455f61e']) }}"
                           class="btn btn-sm btn-primary m-btn m-btn--icon m-btn--air m-btn--pill">
                    <span>
                        <i class="fa fa-plus"></i>
                        <span>
                            新增
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
                <div class="m-form__group row align-items-center">
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control m-input m-date"
                                   placeholder="上传日期" name="date" id="date"
                                   readonly value="{{request('date')}}"/>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <select name="project_id" class="form-control m-input" id="project_id">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control m-bootstrap-select" id="m_form_status">
                                {!! question_status_select() !!}
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-separator m-separator--dashed"></div>
            <!--end: Search Form -->
            <!--begin: Datatable -->
            <div class="m_datatable" id="ajax_data"></div>
            <!--end: Datatable -->
            <!--begin::Modal-->
            <div class="modal fade" id="question_modal" tabindex="-1" role="dialog" aria-labelledby="QuestionModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
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
                                url: '{{ url("question/personal") }}',
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
                        // scroll: true, // enable/disable datatable scroll both horizontal and vertical when needed.
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
                            var status = @json(config('common.question_status'));
                            var rowStatus = Number(row.status);
                            return '<span class="m-badge ' + status[rowStatus].class + ' m-badge--wide">' + status[rowStatus].title + '</span>';
                        }
                    }, {
                        field: "title",
                        title: "标题",
                        filterable: false,
                        width: 200,
                        template: function (row) {
                            return '<a href="' + mAppExtend.laravelRoute('{{route_uri("questions.show")}}', {question: row.id}) + '" data-toggle="modal" data-target="#question_modal" class="action-show">' + row.title + '</a>';
                        }
                    }, {
                        field: "question_category_id",
                        title: "所属版块",
                        sortable: false,
                        template: function (row) {
                            return row.category ? row.category.name : null;
                        }
                    }, {
                        field: "receive_user_id",
                        title: "接收人",
                        sortable: false,
                        template: function (row) {
                            return row.receive_user ? row.receive_user.name : null;
                        }
                    }, {
                        field: "received_at",
                        title: "接收时间", width: 150
                    }, {
                        field: "replied_at",
                        title: "回复时间", width: 150
                    }, {
                        field: "created_at",
                        title: "创建时间", width: 150
                    }, {
                        field: "actions",
                        width: 110,
                        title: "操作",
                        sortable: false,
                        // locked: {right: 'xl'},
                        overflow: 'visible',
                        template: function (row) {
                            var look = '<a href="' + mAppExtend.laravelRoute('{{route_uri("questions.show")}}', {
                                question: row.id,
                                mid: "{{request('mid')}}"
                            }) + '" class="action-show m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" title="查看">\
                <i class="la la-eye"></i></a>';
                            var del = '<a href="' + mAppExtend.laravelRoute('{{route_uri("questions.destroy")}}', {question: row.id}) + '" class="action-delete m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="删除"><i class="la la-trash"></i></a>';
                            var edit = '<a href="' + mAppExtend.laravelRoute('{{route_uri("questions.edit")}}', {
                                question: row.id,
                                mid: "{{request('mid')}}"
                            }) + '" class="action-edit m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="编辑">\
                <i class="la la-edit"></i></a>';
                            var finish = '<a href="' + mAppExtend.laravelRoute('{{route_uri("questions.finished")}}', {
                                question: row.id,
                                mid: "{{request('mid')}}"
                            }) + '" class="action-finished m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" title="关闭">\
                <i class="la la-check"></i></a>';
                            if (row.status > 0) {
                                del = '';
                                edit = '';
                            }
                            if (row.status != 2) {
                                finish = '';
                            }
                            return look + edit + del + finish;
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

                $('#project_id').on('change', function () {
                    datatable.search($(this).val(), 'project_id');
                }).val(typeof query.project_id !== 'undefined' ? query.project_id : '');
                $('#date').on('change', function () {
                    datatable.search($(this).val(), 'date');
                }).val(typeof query.date !== 'undefined' ? query.date : '');
                $('#m_form_status').on('change', function () {
                    datatable.search($(this).val(), 'status');
                }).val(typeof query.status !== 'undefined' ? query.status : '');

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
            $('#m_form_status').selectpicker();
            var $projectSelector = $("#project_id").select2({
                language:'zh-CN',
                placeholder: "输入项目编号、名称等关键字搜索，选择项目",
                allowClear: true,
                width:'100%',
                ajax: {
                    url: "{{route('projects.selector.data')}}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
                            per_page: {{config('common.page.per_page')}}
                        };
                    },
                    processResults: function(data, params) {
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
                escapeMarkup: function(markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 0,
                templateResult: formatProjectRepo, // omitted for brevity, see the source of this page
                templateSelection: formatProjectRepoSelection // omitted for brevity, see the source of this page
            });
            $('.m_datatable').on('click', 'a.action-show', function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                $('#question_modal').modal('show');
                mAppExtend.ajaxGetHtml(
                    '#question_modal .modal-content',
                    url,
                    {}, true);
            });
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

            $('.m_datatable').on('click', 'a.action-finished', function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                mAppExtend.confirmControllData({
                    'title': '你确定要关闭问题吗？',
                    'url': url,
                    'data': {_method: 'POST'},
                    'callback': function () {
                        datatable.load();
                    }
                });
            });

        });
    </script>
@endsection
