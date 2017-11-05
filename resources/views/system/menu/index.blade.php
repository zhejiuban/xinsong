@extends('layouts.app')

@section('content')
    <div class="m-portlet m--margin-bottom-0">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-list"></i>
                    </span>
                    <h3 class="m-portlet__head-text m--font-primary">
                        菜单列表
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <a href="{{ route('menus.create')  }}" class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
                            <span>
                                <i class="fa fa-plus"></i>
                                <span>
                                    新增
                                </span>
                            </span>
                        </a>
                    </li>
                    <li class="m-portlet__nav-item">
                        <a href="{{ route('menus.sync')  }}" id="sync-permission" class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
                            <span>
                                <i class="fa fa-exchange"></i>
                                <span>
                                    同步权限
                                </span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-responsive  table-hover tree" id="menu_table">
                <thead class="thead-default">
                    <tr>
                        <th scope="col" >菜单名称</th>
                        <th scope="col" width="200">描述</th>
                        <th scope="col" width="50">显示</th>
                        <th scope="col" width="80">状态</th>
                        <th scope="col" width="130">操作</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($list as $key=>$val)
                    <tr id="list_{{$val['id']}}" class="treegrid-{{$val['id']}} @if($val['parent_id']) treegrid-parent-{{$val['parent_id']}} @endif">
                        <td class="middle"> {{$val['space']}}{{$val['title']}}</td>
                        <td class="middle">{{$val['tip']}}</td>
                        <td class="middle">
                            @if($val['status'])
                                <span class="m-badge m-badge--success m-badge--wide">是</span>
                            @else
                                <span class="m-badge m-badge--danger m-badge--wide">否</span>
                            @endif
                        </td>
                        <td class="middle">
                            @if($val['status'])
                                <span class="m-badge m-badge--success m-badge--wide">可用</span>
                            @else
                                <span class="m-badge m-badge--danger m-badge--wide">不可用</span>
                            @endif
                        </td>
                        <td class="middle">
                            <a href="{{ url('system/menus/create?parent_id='.$val['id'])  }}" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="添加子菜单">
                                <i class="la la-plus"></i>
                            </a>
                            <a href="{{ route('menus.edit',['menu'=>$val['id']])  }}" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="编辑">
                                <i class="la la-edit"></i>
                            </a>
                            <a href="{{ route('menus.destroy',['menu'=>$val['id']])  }}" class="delete-action m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="删除">
                                <i class="la la-trash"></i>
                            </a>
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
        $(document).ready(function() {
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
                function(){
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        data: {'_method': 'DELETE'},
                        success:function(response, status, xhr) {
                            if(response.status == 'success'){
                                mAppExtend.backUrl(response.url,1100);
                                swal({
                                    timer: 1000,
                                    title:'删除成功',
                                    text:"您的操作数据已被删除",
                                    type:'success'
                                });
                            }else{
                                swal({
                                    timer: 2000,
                                    title:'删除失败',
                                    text:response.message,
                                    type:'error'
                                });
                            }
                        },error:function(xhr, textStatus, errorThrown) {
                            _$error = xhr.responseJSON.errors;
                            var _err_mes = '未知错误，请重试';
                            if(_$error != undefined){
                                _err_mes = '';
                                $.each(_$error, function (i, v) {
                                    _err_mes += v[0] + '<br>';
                                });
                            }
                            swal({
                                timer: 2000,
                                title:'删除失败',
                                text:_err_mes,
                                type:'error'
                            });
                        }
                    });
                });
            });
            $('#sync-permission').click(function (e) {
                e.preventDefault();
                var url = $(this).attr('href');
                jQuery.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {},
                    beforeSend: function () {
                        mApp.block('body',{
                            overlayColor: '#000000',
                            type: 'loader',
                            state: 'primary',
                            message: '正在同步,请不要关闭浏览器...'
                        });
                    },
                    complete: function (xhr, textStatus) {
                        mApp.unblock('body');
                    },
                    success: function (response, textStatus, xhr) {
                        if(response.status == 'success'){
                            $.notify({'message':response.message},{
                                type: 'success',
                                placement: {
                                    from: "top",
                                    align: "center"
                                },delay:500
                            });
                        }else{
                            $.notify({'message':response.message},{
                                type: 'danger',
                                placement: {
                                    from: "top",
                                    align: "center"
                                },delay:1000
                            });
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        $.notify({'message':'请求错误，请重试'},{
                            type: 'danger',
                            placement: {
                                from: "top",
                                align: "center"
                            },delay:1000
                        });
                    }
                });
            });
        });
    </script>
@endsection
