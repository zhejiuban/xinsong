@extends('layouts.app')

@section('content')
    <div class="m-portlet m-portlet--mobile m--margin-bottom-0">
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right  m--margin-bottom-20">
                <div class="row align-items-center">
                    <div class="col-xl-8 order-2 order-xl-1">
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
                                    <select name="department_id" id="department_id" class="form-control m-input select2 m-select2">
                                        {!! department_select(0,1,1) !!}
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                        @if(check_permission('user/users/create'))
                            <a href="{{ menu_url_format(route('users.create'),['mid'=>request('mid')]) }}"
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
                        @if(check_permission('user/users/power'))
                            <button data-toggle="modal" data-target="#m_power_modal"
                                    class="btn btn-sm btn-primary m-btn m-btn--icon m-btn--air m-btn--pill">
                                <span>
                                    <i class="fa fa-eye"></i>
                                    <span>
                                        授权
                                    </span>
                                </span>
                            </button>
                        @endif
                        @if(check_permission('user/users/edit'))
                            <button data-toggle="modal" data-target="#m_editpwd_modal"
                                    class="btn btn-sm btn-primary m-btn m-btn--icon m-btn--air m-btn--pill">
                                <span>
                                    <i class="fa fa-lock"></i>
                                    <span>
                                        重置密码
                                    </span>
                                </span>
                            </button>
                        @endif
                        <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
                </div>
            </div>
            <div class="m-separator m-separator--dashed"></div>
            <!--end: Search Form -->
            <!--begin: Datatable -->
            <div class="m_datatable" id="ajax_data"></div>
            <!--end: Datatable -->
            <!--begin::Modal-->
            <div class="modal fade" id="m_role_modal" role="dialog" aria-labelledby="RoleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    </div>
                </div>
            </div>
            <div class="modal fade" id="m_role_modal_edit" role="dialog" aria-labelledby="RoleModalEditLabel"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                    </div>
                </div>
            </div>
            <div class="modal fade" id="m_power_modal" role="dialog" aria-labelledby="PowerModalEditLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                用户授权
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <form class="m-form m-form--fit">
                                <div class="form-group">
                                    <label>
                                        授权角色:
                                    </label>
                                    <div>
                                        <select multiple name="roles_id[]" id="roles_id"
                                                class="form-control m-input select2 m-select2">
                                            {!! role_select('',1,'name') !!}
                                        </select>
                                    </div>
                                    <span class="m-form__help">请选择授权角色（会清除用户原有角色授权新角色），为空时表示清空用户角色</span>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                关闭
                            </button>
                            <button type="button" class="btn btn-primary" id="power-button">
                                提交
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="m_editpwd_modal" role="dialog" aria-labelledby="EditpwdModalEditLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">
                                重置密码
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>
                                    新密码:
                                </label>
                                <input type="text" value="" id="reset-pwd" name="pwd" class="form-control m-input">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                关闭
                            </button>
                            <button type="button" class="btn btn-primary" id="pwd-button">
                                提交
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Modal-->
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
                                url: '{{ url("user/users") }}',
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
                        field: "status",
                        title: "状态", width: 80,
                        template: function (row) {
                            var status = {
                                1: {'title': '启用', 'class': ' m-badge--success'},
                                0: {'title': '禁用', 'class': ' m-badge--danger'}
                            };
                            return '<span class="m-badge ' + status[row.status].class + ' m-badge--wide">' + status[row.status].title + '</span>';
                        }
                    }, {
                        field: "username",
                        title: "用户名",template:function (row) {
                            return '<a href="' + mAppExtend.laravelRoute('{{route_uri("users.edit")}}', {user: row.id}) + '" class="action-edit " title="编辑">' +
                                row.username+'</a>';
                        }
                    }, {
                        field: "roles",
                        title: "所属角色",
                        sortable: false,
                        template: function (row) {
                            if (row.id == {{config('auth.administrator')}}) {
                                return '<span class="m-badge  m-badge--brand m-badge--wide">超级管理员</span>';
                            } else {
                                var role = [];
                                $.each(row.roles, function (i, v) {
                                    var str = '<span class="m-badge  m-badge--brand m-badge--wide">' + v.name + '</span>'
                                    role.push(str);
                                });
                                return role.join('');
                            }
                        }
                    }, {
                        field: "name",
                        title: "姓名",
                        filterable: false
                    }, {
                        field: "tel",
                        title: "手机号"
                    }, {
                        field: "email",
                        title: "邮箱"
                    }, {
                        field: "department_id",
                        title: "所属部门",
                        template: function (row) {
                            if (row.department || row.company) {
                                 return (row.company ? row.company.name : '') + (row.department && row.department.level == 3 ? '/' + row.department.name : '');
                            }
                        }
                    }, {
                        field: "created_at",
                        title: "创建时间", width: 150
                    }, {
                        field: "updated_at",
                        title: "更新时间", width: 150
                    }, {
                        field: "actions",
                        width: 110,
                        title: "操作",
                        sortable: false,
                        // locked: {right: 'xl'},
                        overflow: 'visible',
                        template: function (row) {
                            var edit = '<a href="' + mAppExtend.laravelRoute('{{route_uri("users.edit")}}', {user: row.id}) + '" class="action-edit m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="编辑">\
								<i class="la la-edit"></i>\
							</a>';
                            var del = '<a href="' + mAppExtend.laravelRoute('{{route_uri("users.destroy")}}', {user: row.id}) + '" class="action-delete m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="删除">\
							<i class="la la-trash"></i>\
						</a>';
                            if (row.id != {{config('auth.administrator')}} && row.id != {{get_current_login_user_info()}}) {
                                return edit + del;
                            } else {
                                return edit;
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

                $('#department_id').on('change', function () {
                  // shortcode to datatable.getDataSourceParam('query');
                  var query = datatable.getDataSourceQuery();
                  query.department_id = $(this).val();
                  // shortcode to datatable.setDataSourceParam('query', query);
                  datatable.setDataSourceQuery(query);
                  datatable.load();
                }).val(typeof query.department_id !== 'undefined' ? query.department_id : '');
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
            //添加角色弹窗打开时，加载添加页面
            $('#m_role_modal').on('shown.bs.modal', function (e) {
                mAppExtend.ajaxGetHtml(
                    '#m_role_modal .modal-content',
                    "{{ route('users.create') }}",
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
            $("#power-button").click(function (event) {
                var _data = datatable.setSelectedRecords().getSelectedRecords();
                if (_data.length < 1) {
                    mAppExtend.notification('请选择要操作的数据'
                        ,'error');
                    return;
                }
                var id = [];
                $.each(_data, function (i, v) {
                    var ids = $(v).find('input').val();
                    id.push(ids);
                });

                mAppExtend.ajaxPostSubmit({
                    'url': '{{route('users.power')}}',
                    'el': '#m_power_modal .modal-content',
                    'query': {
                        'id': id,
                        'roles': $('#roles_id').val()
                    },
                    'callback': function (data, textStatus, xhr) {
                        if (data.status == 'success') {
                            mAppExtend.notification(data.message
                                ,'success','toastr',function() {
                                    $('#m_power_modal').modal('hide');
                                    datatable.reload();
                                });
                        } else {
                            mAppExtend.notification(data.message
                                ,'error');
                        }
                    }
                });
            });
            $("#pwd-button").click(function (event) {
                var _data = datatable.setSelectedRecords().getSelectedRecords();
                if (_data.length < 1) {
                    mAppExtend.notification('请选择要操作的数据'
                        ,'error');
                    return;
                }
                var id = [];
                $.each(_data, function (i, v) {
                    var ids = $(v).find('input').val();
                    id.push(ids);
                });

                mAppExtend.ajaxPostSubmit({
                    'url': '{{route('users.edit_pwd')}}',
                    'el': '#m_power_modal .modal-content',
                    'query': {
                        'id': id,
                        'password': $('#reset-pwd').val()
                    },
                    'callback': function (data, textStatus, xhr) {
                        if (data.status == 'success') {
                            mAppExtend.notification(data.message
                                ,'success','toastr',function() {
                                    $('#m_editpwd_modal').modal('hide');
                                });
                        } else {
                            mAppExtend.notification(data.message
                                ,'error');
                        }
                    }
                });
            });
        });
    </script>
@endsection
