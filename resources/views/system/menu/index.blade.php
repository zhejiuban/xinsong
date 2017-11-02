@extends('layouts.app')

@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-2"></i>
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
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <table class="table table-responsive table-bordered table-hover tree" id="menu_table">
                <tr>
                    <th scope="col" >菜单名称</th>
                    <th scope="col" width="200">描述</th>
                    <th scope="col" width="50">显示</th>
                    <th scope="col" width="80">状态</th>
                    <th scope="col" width="130">操作</th>
                </tr>

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
                alert(1);
            });
        });
    </script>
@endsection
