@extends('layouts.app')

@section('content')
    <div class="m-alert m-alert--icon m-alert--air m-alert--square alert alert-dismissible m--margin-bottom-30" role="alert">
        <div class="m-alert__icon">
            <i class="flaticon-interface-7 m--font-accent"></i>
        </div>
        <div class="m-alert__text m--font-bolder">
            项目名称：{{$project->title}}
        </div>
    </div>

    <div class="m-portlet m-portlet--mobile m--margin-bottom-0">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-event-calendar-symbol"></i>
                </span>
                    <h3 class="m-portlet__head-text m--font-primary">
                        项目实施计划
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    @if(check_project_leader($project, 1) || check_project_leader($project, 2))
                    <li class="m-portlet__nav-item">
                        <a href="{{route('plans.create',['project'=>$project->id])}}"
                           class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air modal-action">
                        <span>
                            <i class="fa fa-plus"></i>
                            <span>
                                新增
                            </span>
                        </span>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="{{route('plans.import',['project'=>$project->id])}}"
                           class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air modal-action">
                        <span>
                            <i class="fa fa-upload"></i>
                            <span>
                                按模板导入
                            </span>
                        </span>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="{{route('plans.batch_delete',['project'=>$project->id])}}"
                           class="btn btn-danger btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air batch-delete">
                        <span>
                            <i class="fa fa-trash"></i>
                            <span>
                                删除
                            </span>
                        </span>
                        </a>
                    </li>
                    @endif
                    <li class="m-portlet__nav-item">
                        <a href="{{ menu_url_format(route('plans.export',['project'=>$project->id]),['mid'=>request('mid')])  }}" class="btn btn-info btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air"
                            target="_blank">
                            <span>
                                <i class="fa fa-download"></i>
                                <span>
                                    导出
                                </span>
                            </span>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        
                        <a href="{{ get_redirect_url() }}"
                           class="btn btn-secondary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
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
                                url: '{{ route("plans.index",['project'=>$project->id]) }}',
                                param: {}
                            }
                        },
                        pageSize: 100,
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
                        field: "sort",
                        title: "序号",
                        width: 80
                    }, {
                        field: "is_finished",
                        title: "按计划完成",
                        width: 100,template: function (row) {
                            if(row.is_finished == null){
                                return '';
                            }
                            var status = @json(config('common.plan_finish_status'));
                            var rowStatus = Number(row.is_finished);
                            return '<span class="m-badge ' + status[rowStatus].class + ' m-badge--wide">' + status[rowStatus].title + '</span>';
                        }
                    },{
                        width: 200,
                        field: "content", sortable: false,
                        title: "计划内容",
                        template: function (row) {
                            var is_edit = "{{is_administrator()}}";
                            var submit = ' <span class="m-badge m-badge--danger m-badge--wide">草稿</span>';
                            if(row.status && is_edit == ""){
                                return row.content;
                            }else{
                                return '<a href="' + mAppExtend.laravelRoute('{{route_uri("plans.edit")}}', {
                                project: row.project_id,plan: row.id,
                                mid: "{{request('mid')}}"
                                }) + '" class="action-edit" title="编辑">'+row.content+'</a>' + (!row.status?submit:''); 
                            }
                        }
                    },  {
                        field: "started_at",
                        title: "计划开始时间",
                        width: 150
                    },  {
                        field: "finished_at",
                        title: "计划完成时间",
                        width: 150
                    },  {
                        field: "user_id",
                        title: "执行人",sortable: false,
                        width: 80, template: function (row) {
                            if (row.user) {
                                return row.user.name;
                            }
                        }
                    }, {
                        field: "delay",
                        title: "延期(天)",
                        width: 80
                    }, {
                        field: "last_finished_at",
                        title: "实际完成时间",
                        width: 150
                    }, {
                        field: "reason",
                        title: "未按计划完成原因说明",sortable: false,
                        width: 300
                    },{
                        field: "actions",
                        width: 80,
                        title: "操作",
                        sortable: false,
                        // locked: {right: 'xl'},
                        overflow: 'visible',
                        template: function (row) {
                            var del = '<a href="' + mAppExtend.laravelRoute('{{route_uri("plans.destroy")}}', {
                                project: row.project_id,plan: row.id,mid: "{{request('mid')}}"
                            }) + '" class="action-delete m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="删除"><i class="la la-trash"></i></a>';
                            var edit = '<a href="' + mAppExtend.laravelRoute('{{route_uri("plans.edit")}}', {
                                project: row.project_id,plan: row.id,
                                mid: "{{request('mid')}}"
                            }) + '" class="action-edit m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="编辑">\
                                <i class="la la-edit"></i></a>';

                            var is_edit = "{{is_administrator()}}";
                            if(row.status && is_edit == ""){
                                return '<span class="m-badge m-badge--success m-badge--wide">已提交</span>';
                            }else{
                                return edit + del;
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

         
        function checkEndTime(startTime,endTime){  
            var start = new Date(startTime.replace("-", "/").replace("-", "/"));  
            var end = new Date(endTime.replace("-", "/").replace("-", "/"));  
            if(end > start){  
                return false;  
            }  
            return true;  
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
            $(".modal-action").click(function (event) {
                event.preventDefault();
                var url = $(this).attr('href');
                $('#_modal').modal('show');
                mAppExtend.ajaxGetHtml(
                    '#_modal .modal-content',
                    url,
                    {}, true);
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
            /*$(".m-datatable").on('blur','td.m-datatable__cell .edit-text-input',function (event) {
                var that = $(this);
                var field = that.attr('data-update-field');
                var id = that.attr('data-update-id');
                var value = that.val();
                var old_value = that.attr('data-old-value');
                mAppExtend.ajaxPostSubmit({
                    'el': '',
                    'url': '{{route("plans.field.update")}}',
                    'query': {
                        '_method':'PUT',
                        'id':id,
                        'field':field,
                        'value':value,
                    },
                    'showLoading': false,
                    'callback': function (data, textStatus, xhr) {
                        that.parent('span').text(value);
                    },
                    'errorCallback': function (xhr, textStatus, errorThrown) {
                        if (xhr.status == 401) { //未认证
                            mAppExtend.notification('登录超时'
                                , 'error', 'toastr', function () {
                                    mAppExtend.backUrl('reload');
                                });
                        } else {
                            var _err_mes = '未知错误，请重试';
                            if (xhr.responseJSON != undefined) {
                                _$error = xhr.responseJSON.errors;
                                if (_$error != undefined) {
                                    _err_mes = '';
                                    $.each(_$error, function (i, v) {
                                        _err_mes += v[0] + '<br>';
                                    });
                                }
                            }
                            mAppExtend.notification(_err_mes
                                , 'error');
                            that.parent('span').text(old_value);
                        }
                    }
                });
            });
            $(".m-datatable").on('change','td.m-datatable__cell .edit-date-input',function (event) {
                var that = $(this);
                var field = that.attr('data-update-field');
                var id = that.attr('data-update-id');
                var value = that.val();
                var old_value = that.attr('data-old-value');
            
                mAppExtend.ajaxPostSubmit({
                    'el': '',
                    'url': '{{route("plans.field.update")}}',
                    'query': {
                        '_method':'PUT',
                        'id':id,
                        'field':field,
                        'value':value,
                    },
                    'showLoading': false,
                    'callback': function (data, textStatus, xhr) {
                        that.parent('span').text(value);
                    },
                    'errorCallback': function (xhr, textStatus, errorThrown) {
                        if (xhr.status == 401) { //未认证
                            mAppExtend.notification('登录超时'
                                , 'error', 'toastr', function () {
                                    mAppExtend.backUrl('reload');
                                });
                        } else {
                            var _err_mes = '未知错误，请重试';
                            if (xhr.responseJSON != undefined) {
                                _$error = xhr.responseJSON.errors;
                                if (_$error != undefined) {
                                    _err_mes = '';
                                    $.each(_$error, function (i, v) {
                                        _err_mes += v[0] + '<br>';
                                    });
                                }
                            }
                            mAppExtend.notification(_err_mes
                                , 'error');
                            that.parent('span').text(old_value);
                        }
                    }
                });
            });

            $(".m-datatable").on('dblclick','td.m-datatable__cell',function (event) {
                var that = $(this);
                var row = that.parent('tr');
                var recordId = row.find('input[type="checkbox"]').val();
                var recordField = that.attr('data-field');
                var inputType = 'text';
                if(recordField == 'started_at' || recordField == 'finished_at' || recordField == 'last_finished_at'){
                    inputType = 'date';
                }
                if(recordField != 'actions'){
                    var old_value = that.children('span').text();
                    that.children('span').html('<input name="'+recordField+'" input-type="'+inputType+'" data-old-value="'+old_value+'" data-update-field="'+recordField+'" data-update-id="'+recordId+'" class="form-control '+inputType+' edit-'+inputType+'-input"  value="'+old_value+'">');
                    mAppExtend.datePickerInstance('.date',{clearBtn: false});
                    that.find('input').focus();
                }
            });*/
        });
    </script>
@endsection
