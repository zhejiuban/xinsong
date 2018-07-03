@extends('layouts.app')

@section('content')
<div class="m-portlet m-portlet--mobile m--margin-bottom-0">
	<div class="m-portlet__body">
		<!--begin: Search Form -->
		<div class="m-form m-form--label-align-right  m--margin-bottom-20">
			<div class="row align-items-center">
				<div class="col-xl-8 order-2 order-xl-1">
					<div class="form-group m-form__group row align-items-center">
						<div class="col-md-4">
							<div class="m-input-icon m-input-icon--left">
								<input type="text" class="form-control m-input" placeholder="关键字..." id="m_form_search">
								<span class="m-input-icon__icon m-input-icon__icon--left">
									<span>
										<i class="fa fa-search"></i>
									</span>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 order-1 order-xl-2 m--align-right">
					<a href="{{ menu_url_format(route('rules.create'),['mid'=>request('mid')]) }}" data-toggle="modal" data-target="#m_role_modal" class="btn btn-sm btn-primary m-btn m-btn--icon m-btn--air m-btn--pill">
						<span>
							<i class="fa fa-plus"></i>
							<span>
								新增
							</span>
						</span>
					</a>
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
		<div class="modal fade" id="m_role_modal" tabindex="-1" role="dialog" aria-labelledby="RoleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
				</div>
			</div>
		</div>
    <div class="modal fade" id="m_role_modal_edit" tabindex="-1" role="dialog" aria-labelledby="RoleModalEditLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog" role="document">
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
            type:'get',
            read: {
              url: '{{ route("rules.index") }}',
              param:{}
            }
          },
          pageSize:  {{config('common.page.per_page',10)}},
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
          width: 60,
          textAlign: 'center'
        },{
            field: "status",
            title: "状态",width: 60,
            template:function (row) {
                var status = {
                    1: {'title': '启用', 'class': ' m-badge--success'},
                    0: {'title': '禁用', 'class': ' m-badge--danger'}
                };
                return '<span class="m-badge ' + status[row.status].class + ' m-badge--wide">' + status[row.status].title + '</span>';
            }
        }, {
          field: "name",
          title: "考核项目",
          filterable: false
        },{
          width: 200,
          field: "content",
          title: "考核内容",
          filterable: false
        },{
          field: "score",
          title: "分值",
          filterable: false
        },{
          field: "remark",
          title: "备注",
          filterable: false
        },/*{
          field: "created_at",
          title: "创建时间",width:150
        }, {
          field: "updated_at",
          title: "更新时间",width:150
        }, */{
          field: "actions",
          width: 110,
          title: "操作",
          sortable:false,
          // locked: {right: 'xl'},
          overflow: 'visible',
          template: function (row) {
						var del = '<a href="'+mAppExtend.laravelRoute('{{route_uri("rules.destroy")}}',{rule:row.id })+'" class="action-delete m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="删除"><i class="la la-trash"></i></a>';
						if(row.is_system == 1){ del = ''};
            return '<a href="'+mAppExtend.laravelRoute('{{route_uri("rules.edit")}}',{rule:row.id})+'" class="action-edit m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="编辑">\
                <i class="la la-edit"></i></a>'+del;
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
        "{{ route('rules.create') }}",
        {},true);
    })
    $('.m_datatable').on('click', 'a.action-edit', function(event) {
      event.preventDefault();
      var url = $(this).attr('href');
      $('#m_role_modal_edit').modal('show');
      mAppExtend.ajaxGetHtml(
        '#m_role_modal_edit .modal-content',
        url,
        {},true);
    });
    $('.m_datatable').on('click', 'a.action-delete', function(event) {
      event.preventDefault();
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
              datatable.load();
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
  });
  </script>
@endsection
