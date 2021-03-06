@extends('layouts.app')

@section('content')
    <div class="m-portlet m--margin-bottom-0">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-sitemap"></i>
                    </span>
                    <h3 class="m-portlet__head-text m--font-primary">
                        组织机构
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    @if(check_permission('user/departments/create'))
                        <li class="m-portlet__nav-item">
                            <a href="{{ menu_url_format(route('departments.create'),['mid'=>request('mid')]) }}"
                               class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
                            <span>
                                <i class="fa fa-plus"></i>
                                <span>
                                    新增分部
                                </span>
                            </span>
                            </a>
                        </li>
                    @endif
                    @if(check_permission('user/departments/sub/create'))
                        <li class="m-portlet__nav-item">
                            <a href="{{ menu_url_format(route('departments.sub'),['mid'=>request('mid')]) }}"
                               class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
                            <span>
                                <i class="fa fa-plus"></i>
                                <span>
                                    新增部门
                                </span>
                            </span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-responsive  table-hover tree" id="menu_table">
                <thead class="thead-default">
                <tr>
                    <th scope="col" style="min-width: 200px;">名称</th>
                    <th scope="col" style="min-width: 50px;" width="50">级别</th>
                    <th scope="col" style="min-width: 150px;" width="200">描述</th>
                    <th scope="col" style="min-width: 50px;" width="50">人数</th>
                    <th scope="col" style="min-width: 80px;" width="80">状态</th>
                    <th scope="col" style="min-width: 130px;" width="130">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($list as $key=>$val)
                    <tr id="list_{{$val['id']}}"
                        class="treegrid-{{$val['id']}} @if($val['parent_id']) treegrid-parent-{{$val['parent_id']}} @endif">
                        <td class="middle"> {{$val['space']}}{{$val['name']}}</td>
                        <td class="middle">
                            {{department_level($val['level'])}}
                        </td>
                        <td class="middle">{{$val['remark']}}</td>
                        <td class="middle">
                            {{get_department_user_count($val['id'],$val['level'])}}
                        </td>
                        <td class="middle">
                            @if($val['status'])
                                <span class="m-badge m-badge--success m-badge--wide">可用</span>
                            @else
                                <span class="m-badge m-badge--danger m-badge--wide">不可用</span>
                            @endif
                        </td>
                        <td class="middle">
                            @if(is_administrator())
                                <a href="{{ menu_url_format(url('user/departments/sub_create?parent_id='.$val['id']),['mid'=>request('mid')])  }}"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                   data-container="body" data-toggle="m-tooltip" data-placement="top"
                                   data-original-title="添加子部门" title="添加子部门">
                                    <i class="la la-plus"></i>
                                </a>
                                @if($val['level'] != 3)
                                    <a href="{{ menu_url_format(route('departments.edit',['department_id'=>$val['id']]),['mid'=>request('mid')]) }}"
                                       class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                       data-container="body" data-toggle="m-tooltip" data-placement="top"
                                       data-original-title="编辑" title="编辑">
                                        <i class="la la-edit"></i>
                                    </a>
                                @else
                                    <a href="{{ menu_url_format(route('departments.sub.edit',['department_id'=>$val['id']]),['mid'=>request('mid')])  }}"
                                       class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                       data-container="body" data-toggle="m-tooltip" data-placement="top"
                                       data-original-title="编辑" title="编辑">
                                        <i class="la la-edit"></i>
                                    </a>
                                @endif
                                @if($val['level'] != 1)
                                    <a href="{{ route('departments.destroy',['department'=>$val['id'],'mid'=>request('mid')])  }}"
                                       class="delete-action m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                       data-container="body" data-toggle="m-tooltip" data-placement="top"
                                       data-original-title="删除" title="删除">
                                        <i class="la la-trash"></i>
                                    </a>
                                @endif
                            @else
                                <a href="{{ menu_url_format(url('user/departments/sub_create?parent_id='.$val['id']),['mid'=>request('mid')])  }}"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="添加子部门">
                                    <i class="la la-plus"></i>
                                </a>
                                @if($val['level'] == 3)
                                    <a href="{{ menu_url_format(route('departments.sub.edit',['department_id'=>$val['id']]),['mid'=>request('mid')])  }}"
                                       class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                       title="编辑">
                                        <i class="la la-edit"></i>
                                    </a>
                                    <a href="{{ route('departments.destroy',['department'=>$val['id'],'mid'=>request('mid')])  }}"
                                       class="delete-action m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                       title="删除">
                                        <i class="la la-trash"></i>
                                    </a>
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.tree').treegrid();
            $('.delete-action').click(function (e) {
                e.preventDefault();
                var url = $(this).attr('href');
                swal({
                        title: "你确定要删除吗?",
                        text: "删除的数据无法撤销，请谨慎操作!",
                        type: "warning",
                        cancelButtonText: '取消',
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "确定",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    },
                    function () {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            dataType: 'json',
                            data: {'_method': 'DELETE'},
                            success: function (response, status, xhr) {
                                if (response.status == 'success') {
                                    mAppExtend.backUrl('reload', 1100);
                                    swal({
                                        timer: 1000,
                                        title: '删除成功',
                                        text: "您的操作数据已被删除",
                                        type: 'success'
                                    });
                                } else {
                                    swal({
                                        timer: 2000,
                                        title: '删除失败',
                                        text: response.message,
                                        type: 'error'
                                    });
                                }
                            }, error: function (xhr, textStatus, errorThrown) {
                                _$error = xhr.responseJSON.errors;
                                var _err_mes = '未知错误，请重试';
                                if (_$error != undefined) {
                                    _err_mes = '';
                                    $.each(_$error, function (i, v) {
                                        _err_mes += v[0] + '<br>';
                                    });
                                }
                                swal({
                                    timer: 2000,
                                    title: '删除失败',
                                    text: _err_mes,
                                    type: 'error'
                                });
                            }
                        });
                    });
            });
        });
    </script>
@endsection
