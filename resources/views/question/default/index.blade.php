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
                        问题汇总
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{{ menu_url_format(route('questions.create'),['mid'=>request('mid')]) }}"
                           class="btn btn-sm btn-primary m-btn m-btn--icon m-btn--air m-btn--pill">
                                <span>
                                    <i class="fa fa-plus"></i>
                                    <span>
                                        新增
                                    </span>
                                </span>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="{{ menu_url_format(route('questions.delete'),['mid'=>request('mid')]) }}"
                           class="batch-delete btn btn-sm btn-danger m-btn m-btn--icon m-btn--air m-btn--pill">
                            <span>
                                <i class="fa fa-trash"></i>
                                <span>
                                    删除
                                </span>
                            </span>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="{{ menu_url_format(route('questions.export'),['mid'=>request('mid')])  }}" class="btn btn-info btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air"
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
                        <a href="{{ menu_url_format(route('questions.export'),['mid'=>request('mid')])  }}" id="whereExport" class="btn btn-info btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air"
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
            <form id="exportForm" action="{{ menu_url_format(route('questions.export'),['mid'=>request('mid')])  }}" method="get">
            <div class="m-form m-form--label-align-right  m--margin-bottom-20">
                <div class="m-form__group row align-items-center">
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="m-input-icon m-input-icon--left">
                                <input type="text" name="search" class="form-control m-input" placeholder="关键字..."
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <select class="form-control m-bootstrap-select" name="status" id="m_form_status">
                                {!! question_status_select() !!}
                            </select>
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
                            <select name="user_id" class="form-control" id="user_id">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                             <select name="receive_user_id" class="form-control" id="receive_user_id">
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
                                url: '{{ url("question/questions") }}',
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
                        title: "状态", width: 80,
                        template: function (row) {
                            var status = @json(config('common.question_status'));
                            var rowStatus = Number(row.status);
                            return '<span class="m-badge ' + status[rowStatus].class + ' m-badge--wide">' + status[rowStatus].title + '</span>';
                        }
                    }, {
                        field: "title",
                        title: "标题",
                        width: 200,
                        template: function (row) {
                            return '<a href="' + mAppExtend.laravelRoute('{{route_uri("questions.show")}}', {question: row.id}) + '" data-toggle="modal" data-target="#question_modal" class="action-show">' + row.title + '</a>';
                        }
                    }, {
                        field: "project_id",
                        width: 240,
                        title: "所属项目", sortable: false,
                        template: function (row) {
                            if (row.project) {
                                return row.project.title;
                            }
                        }
                    },{
                        field: "question_category_id",
                        title: "所属分类",
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
                    }, /*{
                        field: "received_at",
                        title: "接收时间", width: 150
                    },*/ {
                        field: "replied_at",
                        title: "回复时间", width: 150
                    }, {
                        field: "user_id",
                        title: "上报人",
                        sortable: false,
                        template: function (row) {
                            return row.user ? row.user.name : null;
                        }
                    }, {
                        field: "created_at",
                        title: "创建时间", width: 150
                    }, {
                        field: "actions",
                        width: 140,
                        title: "操作",
                        sortable: false,
                        // locked: {right: 'xl'},
                        overflow: 'visible',
                        template: function (row) {
                            var del = '<a href="' + mAppExtend.laravelRoute('{{route_uri("questions.destroy")}}', {question: row.id}) + '" class="action-delete m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="删除"><i class="la la-trash"></i></a>';
                            var edit = '<a href="' + mAppExtend.laravelRoute('{{route_uri("questions.edit")}}', {
                                question: row.id,
                                mid: "{{request('mid')}}"
                            }) + '" class="action-edit m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="编辑">\
                                <i class="la la-edit"></i></a>';
                            var reply = '<a href="' + mAppExtend.laravelRoute('{{route_uri("questions.reply")}}', {
                                question: row.id,
                                mid: "{{request('mid')}}"
                            }) + '" class="action-reply m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="接收并回复">\
		                        <i class="la la-reply"></i></a>';
                            var finish = '<a href="' + mAppExtend.laravelRoute('{{route_uri("questions.finished")}}', {
                                question: row.id,
                                mid: "{{request('mid')}}"
                            }) + '" class="action-finished m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" title="关闭">\
		                        <i class="la la-check"></i></a>';
                            var look = '<a href="'+mAppExtend.laravelRoute('{{route_uri("questions.show")}}',{question:row.id,mid:"{{request('mid')}}" })+'" class="action-show m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" title="查看">\
                                <i class="la la-eye"></i></a>';
                            var admin = '{{is_administrator()}}';
                            if(row.status = 3){
                                reply = '';
                                edit = '';
                                finish = '';
                            }
                            if(admin == ''){
                                var head = "{{check_user_role(null,'总部管理员')}}";
                                if((row.status <= 1) && head != ''){
                                    return reply;
                                }else{
                                    return look;
                                }
                            }else{
                                return look+edit + del + reply + finish;
                            }
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
                $('#user_id').on('change', function () {
                    datatable.search($(this).val(), 'user_id');
                }).val(typeof query.user_id !== 'undefined' ? query.user_id : '');
                $('#receive_user_id').on('change', function () {
                    datatable.search($(this).val(), 'receive_user_id');
                }).val(typeof query.receive_user_id !== 'undefined' ? query.receive_user_id : '');

            };

            return {
                // public functions
                init: function () {
                    DataList();
                }
            };
        }();

        function formatRepos(repo) {
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

        function formatReposSelection(repo) {
            return repo.name || repo.text;
        }

        function formatReceiveUser(repo) {
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

        function formatReceiveUserSelection(repo) {
            return repo.name || repo.text;
        }

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
                            all:'company',
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
            //上报人
            $("#user_id").select2({
                language: 'zh-CN',
                placeholder: "选择上报人",
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{route('users.selector.data')}}",
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
                templateResult: formatRepos, // omitted for brevity, see the source of this page
                templateSelection: formatReposSelection // omitted for brevity, see the source of this page
            });
            //接收人
            var $receiveUserSelector = $("#receive_user_id").select2({
                language: 'zh-CN',
                placeholder: "选择接收人",
                allowClear: true,
                width: '100%',
                ajax: {
                    url: "{{route('users.selector.data',['type'=>check_company_admin() ? 'headquarters' : 'sub_and_headquarters'])}}",
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
                templateResult: formatReceiveUser, // omitted for brevity, see the source of this page
                templateSelection: formatReceiveUserSelection // omitted for brevity, see the source of this page
            });
            $('.m_datatable').on('click', 'a.action-show,a.action-reply', function (event) {
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
            $(".batch-delete").click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                var _data = datatable.setSelectedRecords().getSelectedRecords();
                if (_data.length < 1) {
                    mAppExtend.notification('请选择要操作的数据', 'error');
                    return;
                }
                var id = [];
                $.each(_data, function (i, v) {
                    var ids = $(v).find('input').val();
                    id.push(ids);
                });
                mAppExtend.deleteData({
                    'url': url,
                    'data': {id: id},
                    'callback': function () {
                        datatable.load();
                    }
                });
            });
            $("#whereExport").click(function(event) {
                event.preventDefault();
                $("#exportForm").submit();
                /*var url = $(this).attr('href');
                mAppExtend.ajaxPostSubmit({
                    'url':url,
                    'query':{
                        project_id:$('#project_id').val(),
                        date:$('#date').val(),
                        status:$('#m_form_status').val(),
                        user_id:$('#user_id').val(),
                        receive_user_id:$('#receive_user_id').val(),
                        search:$("#m_form_search").val()
                    }
                });*/
            });
        });
    </script>
@endsection
