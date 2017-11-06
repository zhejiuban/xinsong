@extends('layouts.app')

@section('content')
<div class="m-portlet m--margin-bottom-0">
    <div class="m-portlet__head">
        <div class="m-portlet__head-caption">
            <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="flaticon-list-2"></i>
                </span>
                <h3 class="m-portlet__head-text m--font-primary">
                    角色授权
                </h3>
            </div>
        </div>
        <div class="m-portlet__head-tools">
            <ul class="m-portlet__nav">
                <li class="m-portlet__nav-item">
                    <a href="{{route('groups.power',['group'=>$role->id])}}" id="submit-button" class="btn btn-primary btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
                        <span>
                            <i class="fa fa-edit"></i>
                            <span>
                                保存
                            </span>
                        </span>
                    </a>
                </li>
                <li class="m-portlet__nav-item">
                    <a href="{{ route('groups.index')  }}" class="btn btn-metal btn-sm m-btn  m-btn m-btn--icon m-btn--pill m-btn--air">
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
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
                <div class="m-btn-group m-btn-group--pill btn-group">
                    <button class="m-btn btn  btn-secondary" id="ztree_expandAll"><i class="fa fa-folder-open-o"></i> 展开/收起</button>
                    <button class="m-btn btn  btn-secondary" id="checkAllTrue"><i class="fa fa-check-square-o"></i> 全选</button>
                    <button class="m-btn btn  btn-secondary" id="checkAllFalse"><i class="fa fa-square-o"></i> 全不选</button>
                </div>
                <ul id="treeDemo" class="ztree"></ul>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script type="text/javascript">
        jQuery(document).ready(function() {
            //配置
            var setting = {
                check: {
                    enable: true,
                    chkboxType:  { "Y" : "ps", "N" : "s" },
                    chkStyle : "checkbox"
                },
                data: {
                    key: {
                        name: "title",
                        title: "title"
                    },
                    simpleData: {
                        enable: true,
                        idKey: "id",
                        pIdKey: "parent_id"
                    }
                }
            };
            var zNodes = {!!json_encode($node_list)!!};

            //勾选已有的权限
            var rules = {!! json_encode($haved) !!};
            $.each(zNodes, function(index, val) {
                if( $.inArray( parseInt(val.rules),rules )>-1 ){
                    zNodes[index].checked = true;
                }else{
                    zNodes[index].checked = false;
                }
            });
            //树形菜单初始化
            $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            var zTree = null;
            zTree = $.fn.zTree.getZTreeObj("treeDemo");
            zTree.expandAll(false);
            $("#ztree_expandAll").click(function(){
                if($(this).data("open")){
                    zTree.expandAll(false);
                    $(this).data("open",false);
                }else{
                    zTree.expandAll(true);
                    $(this).data("open",true);
                }
            });
            $('#checkAllTrue').click(function(){
                zTree.checkAllNodes(true);
                //$(".checkbox_false_full").removeClass('checkbox_false_full').addClass('checkbox_true_full');
            });
            $('#checkAllFalse').click(function(){
                zTree.checkAllNodes(false);
                //$(".checkbox_true_full").removeClass('checkbox_true_full').addClass('checkbox_false_full');
            });

            //数据提交
            $("#submit-button").click(function(e){
                e.preventDefault();
                var  nodes = zTree.getCheckedNodes(true);
                console.log(nodes);
                var  nodesValue = [];
                $.each(nodes,function(i,value){
                    nodesValue.push(value.permission);
                });
                var url = $(this).attr('href');
                var submitButton = $('#submit-button');
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {'_method': 'POST','permission':nodesValue},
                    beforeSend: function(){
                        submitButton.attr('disabled', 'disabled')
                            .addClass('m-loader m-loader--light m-loader--right');
                    },
                    complete:function(){
                        submitButton.removeAttr('disabled')
                            .removeClass('m-loader m-loader--light m-loader--right');
                    },
                    success: function(response, status, xhr, $form) {
                        if(response.status == 'success'){
                            $.notify({'message':response.message},{
                                type: 'success',
                                placement: {
                                    from: "top",
                                    align: "center"
                                },delay:500,
                                onClose:function() {
                                    mAppExtend.backUrl(response.url);
                                }
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
                    error:function (xhr, textStatus, errorThrown) {
                        _$error = xhr.responseJSON.errors;
                        var _err_mes = '未知错误，请重试';
                        if(_$error != undefined){
                            _err_mes = '';
                            $.each(_$error, function (i, v) {
                                _err_mes += v[0] + '<br>';
                            });
                        }
                        $.notify({'message':_err_mes},{
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
